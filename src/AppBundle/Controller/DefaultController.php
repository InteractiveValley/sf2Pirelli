<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Contacto;
use AppBundle\Form\ContactoType;
use Exception;

class DefaultController extends Controller {

    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        
        $establecimientos = $em->getRepository('YDIBackendBundle:Establecimiento')
                ->findAll();
        $imagenes = $em->getRepository('YDIBackendBundle:Imagen')->findAll();
        $estados = $em->getRepository('YDIBackendBundle:Estado')->findAll();
        $mensaje = $em->getRepository('YDIBackendBundle:Publicacion')->find(1);
        
        return array(
            'establecimientos'  =>  $establecimientos,
            'imagenes'          =>  $imagenes,
            'estados'           =>  $estados,
            'mensaje'           =>  $mensaje
        );
    }

    /**
     * @Route("/v2", name="homepage_anterior")
     * @Template()
     */
    public function nuevoAction(Request $request) {
        $contacto = new Contacto();
        $form = $this->createForm(new ContactoType(), $contacto);
        $status = 'new';
        $mensaje = "";
        return array(
                'form' => $form->createView(),
                'status' => $status,
                'mensaje' => $mensaje
        );
    }

    /**
     * @Route("/telefono", name="post_telefono")
     * @Method({"GET", "POST"})
     */
    public function postTelefonosAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $telefono1 = null;
        $telefono2 = null;
        if ($request->getMethod() == "POST") {
            //$content = $request->getContent();
            //$data = json_decode($content, true);
            $data = $request->request->all();
            $logger = $this->get('logger');
            $logger->info("POST Telefono: " . implode(';', $data));
            try {
                $this->compruebaArreglo(array('regid', 'idusuario', 'fecha'), $data);
                $usuario = $em->getRepository('YDIBackendBundle:Usuario')
                        ->find($data['idusuario']);
                $telefono1 = $em->getRepository('YDIBackendBundle:Telefono')->findOneBy(array(
                    'gcmid' => $data['regid']
                ));
                if (!$usuario) {
                    $response = new JsonResponse(array(
                        'estatus' => 'error',
                        'motivo' => 'No existe registro de usuario con ID: ' . $data['idusuario']));
                }
                if ($usuario->getId() != 1) {
                    $telefonos = $em->getRepository('YDIBackendBundle:Telefono')->findBy(array(
                        'usuario' => $usuario->getId(),
                        'registroBorrado' => false
                    ));
                    foreach ($telefonos as $telefono) {
                        $telefono->setRegistroBorrado(true);
                        $em->flush();
                    }
                }
                if (!$telefono1) {
                    $telefono1 = new \YDI\BackendBundle\Entity\Telefono();
                }
                $telefono1->setGcmid($data['regid']);
                $telefono1->setUsuario($usuario);
                //$fecha = date_create_from_format('j/m/Y', $data['fecha']);
                $telefono1->setFechaDescarga(new \DateTime($data['fecha']));
                $telefono1->setRegistroBorrado(false);
                $telefono1->setInactivo(false);
                if (!$telefono1->getId()) {
                    $em->persist($telefono1);
                }
                $em->flush();
                $response = new JsonResponse(array('estatus' => 'ok'));
            } catch (Exception $e) {
                $response = new JsonResponse(array('estatus' => 'error', 'motivo' => $e->getMessage()));
            }
        } else {
            $response = new JsonResponse(array('estatus' => 'error', 'motivo' => 'Ingresa los datos correctamente: regid,idusuario,fecha'));
        }
        return $response;
    }

    /**
     * @Route("/consulta_gcmid", name="get_status_telefono")
     * @Method({"GET", "POST"})
     */
    public function getStatusTelefonoAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $telefono1 = null;
        if ($request->getMethod() == "POST") {
            //$content = $request->getContent();
            //$data = json_decode($content, true);
            $data = $request->request->all();
            $logger = $this->get('logger');
            $logger->info("POST Status Telefono: " . implode(';', $data));
            try {
                $this->compruebaArreglo(array('regid'), $data);
                $telefono1 = $em->getRepository('YDIBackendBundle:Telefono')->findOneBy(array(
                    'gcmid' => $data['regid']
                ));
                if (!$telefono1) {
                    $response = new JsonResponse(array('code' => 0, 'registroborrado' => 0, 'inactivo' => 0));
                } else {
                    $response = new JsonResponse(array('code' => 1, 'registroborrado' => ($telefono1->getRegistroBorrado() ? 1 : 0), 'inactivo' => ($telefono1->getInactivo() ? 1 : 0)));
                }
            } catch (Exception $e) {
                $response = new JsonResponse(array('estatus' => 'error', 'motivo' => $e->getMessage()));
            }
        } else {
            $response = new JsonResponse(array('estatus' => 'error', 'motivo' => 'Ingresa los datos correctamente: regid,idusuario,fecha'));
        }
        return $response;
    }

    /**
     * @Route("/usuario", name="post_usuario")
     * @Method({"GET", "POST"})
     */
    public function postUsuariosAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if ($request->getMethod() == "POST") {
            //$content = $request->getContent();
            //$data = json_decode($content, true);
            $data = $request->request->all();
            $logger = $this->get('logger');
            $logger->info("POST Usuarios: " . implode(';', $data));
            try {
                $em->getConnection()->beginTransaction();
                $this->compruebaArreglo(array('email', 'nombre', 'fecha', 'url', 'codigop', 'gcmid'), $data);
                $usuario = $em->getRepository('YDIBackendBundle:Usuario')
                        ->findOneBy(array('email' => $data['email']));
                $codigop = $em->getRepository('YDIBackendBundle:Codigop')
                        ->find($data['codigop']);
                if (!$codigop) {
                    throw new Exception('No existe registro de Codigo Postal con numero: ' . $data['codigop']);
                } else if (!$usuario) {
                    $usuario = new \YDI\BackendBundle\Entity\Usuario();
                } else {
                    $this->eliminarTarjetas($usuario, $em);
                    $this->eliminarPreferencias($usuario, $em);
                }

                $usuario->setNombre($data['nombre']);
                $usuario->setUrl($data['url']);
                $usuario->setEmail($data['email']);
                $usuario->setCodigoPostal($codigop);
                $usuario->setFechaSettings(new \DateTime($data['fecha']));
                if (!$usuario->getId()) {
                    $em->persist($usuario);
                }
                $em->flush();
                if (isset($data['ultimosdigitos'])) {
                    $tarjetas = $this->parsearTarjetasDeUsuario($data);
                    foreach ($tarjetas as $tarjeta) {
                        $this->altaTarjeta($tarjeta, $em, $usuario);
                    }
                }
                if (isset($data['idpreferencia'])) {
                    $preferencias = $this->parsearPreferenciasUsuario($data);
                    foreach ($preferencias as $preferencia) {
                        $this->altaPreferencia($preferencia, $em, $usuario);
                    }
                }
                $this->actualizarGCMID($data, $usuario, $em);
                $em->getConnection()->commit();
                $response = new JsonResponse(array('estatus' => 'ok', 'id' => $usuario->getId()));
            } catch (Exception $e) {
                $em->getConnection()->rollback();
                $em->close();
                $response = new JsonResponse(array('estatus' => 'error', 'motivo' => $e->getMessage()));
            }
        } else {
            $response = new JsonResponse(array('estatus' => 'error', 'motivo' => 'Ingresa los datos correctamente: email,nombre,fecha,url,codigop,gcmid'));
        }
        return $response;
    }

    /**
     * @Route("/altapuntosusuario", name="post_altapuntosusuario")
     * @Method({"GET", "POST"})
     */
    public function postAltaPuntosUsuarioAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if ($request->getMethod() == "POST") {
            //$content = $request->getContent();
            //$data = json_decode($content, true);
            $data = $request->request->all();
            $logger = $this->get('logger');
            $logger->info("POST Puntos Usuario: " . implode(';', $data));
            try {
                $this->compruebaArreglo(array('idusuario', 'idanuncio', 'puntos',
                    'fecha', 'cestablecimiento', 'cydi', 'comentario', 'ticket',
                    'id_tipotarjeta', 'id_emisor', 'id_operador'), $data);
                $usuario = $em->getRepository('YDIBackendBundle:Usuario')->find($data['idusuario']);
                //$anuncio = $em->getRepository('YDIBackendBundle:Anuncio')->find($data['idanuncio']);
                if (!$usuario) {
                    $response = new JsonResponse(array(
                        'estatus' => 'error',
                        'motivo' => 'No existe registro de usuario con ID: ' . $data['idusuario']));
                } /* else if (!$anuncio) {
                  $response = new JsonResponse(array(
                  'estatus' => 'error',
                  'motivo' => 'No existe registro de anuncio con ID: ' . $data['idanuncio']));
                  } */ else {
                    $pPorUsuario = new \YDI\BackendBundle\Entity\Puntosxusuario();
                    $pPorUsuario->setAnuncio($data['idanuncio']);
                    $pPorUsuario->setUsuario($usuario);
                    $pPorUsuario->setPuntos($data['puntos']);
                    $pPorUsuario->setCalificacionestablecimiento($data['cestablecimiento']);
                    $pPorUsuario->setCalificacionapp($data['cydi']);
                    $pPorUsuario->setFecha(new \DateTime($data['fecha']));
                    $pPorUsuario->setComentario($data['comentario']);
                    $pPorUsuario->setTicket($data['ticket']);
                    $pPorUsuario->setTipoTarjeta($data['id_tipotarjeta']);
                    $pPorUsuario->setEmisor($data['id_emisor']);
                    $pPorUsuario->setOperador($data['id_operador']);
                    $em->persist($pPorUsuario);
                    $em->flush();
                    $response = new JsonResponse(array('estatus' => 'ok'));
                }
            } catch (Exception $e) {
                $response = new JsonResponse(array('estatus' => 'error', 'motivo' => $e->getMessage()));
            }
        } else {
            $response = new JsonResponse(array('estatus' => 'error', 'motivo' => 'Ingresa los datos correctamente: idusuario,idanuncio,puntos,fecha,cestablecimiento,cydi,comentario'));
        }
        return $response;
    }

    /**
     * @Route("/altarechazo", name="post_altarechazo")
     * @Method({"GET", "POST"})
     */
    public function postAltaRechazoAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if ($request->getMethod() == "POST") {
            //$content = $request->getContent();
            //$data = json_decode($content, true);
            $data = $request->request->all();
            $logger = $this->get('logger');
            $logger->info("POST Rechazo: " . implode(';', $data));
            try {
                $this->compruebaArreglo(array('idusuario', 'idanuncio', 'noaceptadoaplicado', 'fecha', 'puntos'), $data);
                $usuario = $em->getRepository('YDIBackendBundle:Usuario')->find($data['idusuario']);
                $anuncio = $em->getRepository('YDIBackendBundle:Anuncio')->find($data['idanuncio']);
                if (!$usuario) {
                    $response = new JsonResponse(array('estatus' => 'error', 'motivo' => 'No existe registro de usuario con ID: ' . $data['idusuario']));
                } else if (!$anuncio) {
                    $response = new JsonResponse(array('estatus' => 'error', 'motivo' => 'No existe registro de anuncio con ID: ' . $data['idanuncio']));
                } else {
                    $rechazo = new \YDI\BackendBundle\Entity\Rechazos();
                    $rechazo->setUsuario($usuario);
                    $rechazo->setAnuncio($anuncio);
                    $rechazo->setNoAceptadoAplicado($data['noaceptadoaplicado']);
                    $rechazo->setFecha(new \DateTime($data['fecha']));
                    $rechazo->setPuntos($data['puntos']);
                    $em->persist($rechazo);
                    $em->flush();
                    $response = new JsonResponse(array('estatus' => 'ok'));
                }
            } catch (Exception $e) {
                $response = new JsonResponse(array('estatus' => 'error', 'motivo' => $e->getMessage()));
            }
        } else {
            $response = new JsonResponse(array('estatus' => 'error', 'motivo' => 'Ingresa los datos correctamente: idusuario,idanuncio,motivo,fecha'));
        }
        return $response;
    }

    /**
     * @Route("/altatarjetas", name="post_altatarjetas")
     * @Method({"GET", "POST"})
     */
    public function postAltaTarjetasAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if ($request->getMethod() == "POST") {
            //$content = $request->getContent();
            //$data = json_decode($content, true);
            $data = $request->request->all();
            $logger = $this->get('logger');
            $logger->info("POST Tarjetas: " . implode(';', $data));
            try {
                $this->compruebaArreglo(array('idusuario', 'ultimosdigitos', 'fechavencimiento', 'tipo', 'emisor', 'operador'), $data);
                $usuario = $em->getRepository('YDIBackendBundle:Usuario')
                        ->find($data['idusuario']);
                if (!$usuario) {
                    throw new Exception('No existe registro del usuario con ID: ' . $data['idusuario']);
                } else {
                    $this->eliminarTarjetas($usuario, $em);
                }
                if (isset($data['ultimosdigitos'])) {
                    $tarjetas = $this->parsearTarjetasDeUsuario($data);
                    foreach ($tarjetas as $tarjeta) {
                        $this->altaTarjeta($tarjeta, $em, $usuario);
                    }
                }
                $response = new JsonResponse(array('estatus' => 'ok'));
            } catch (Exception $e) {
                $response = new JsonResponse(array('estatus' => 'error', 'motivo' => $e->getMessage()));
            }
        } else {
            $response = new JsonResponse(array('estatus' => 'error', 'motivo' => 'Ingresa los datos correctamente: idusuario,ultimosdigitos,fechavencimiento,tipo,emisor,operador'));
        }
        return $response;
    }

    /**
     * @Route("/altapreferenciausuario", name="post_altapreferenciausuario")
     * @Method({"GET", "POST"})
     */
    public function postAltaPreferenciasUsuarioAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if ($request->getMethod() == "POST") {
            //$content = $request->getContent();
            //$data = json_decode($content, true);
            $data = $request->request->all();
            $logger = $this->get('logger');
            $logger->info("POST Preferencias: " . implode(';', $data));
            try {
                $em->getConnection()->beginTransaction();
                $this->compruebaArreglo(array('idusuario', 'idpreferencia', 'activoflag'), $data);
                $usuario = $em->getRepository('YDIBackendBundle:Usuario')
                        ->find($data['idusuario']);
                if (!$usuario) {
                    throw new Exception('No existe registro del usuario con ID: ' . $data['idusuario']);
                } else {
                    $this->eliminarPreferencias($usuario, $em);
                }
                if (isset($data['idpreferencia'])) {
                    $preferencias = $this->parsearPreferenciasUsuario($data);
                    foreach ($preferencias as $preferencia) {
                        $this->altaPreferencia($preferencia, $em, $usuario);
                    }
                }
                $em->getConnection()->commit();
                $response = new JsonResponse(array('estatus' => 'ok'));
            } catch (Exception $e) {
                $em->getConnection()->rollback();
                $em->close();
                $response = new JsonResponse(array('estatus' => 'error', 'motivo' => $e->getMessage()));
            }
        } else {
            $response = new JsonResponse(array('estatus' => 'error', 'motivo' => 'Ingresa los datos correctamente: idusuario, idpreferencia, activoflag'));
        }
        return $response;
    }

    /**
     * @Route("/bajausuario", name="post_bajausuario")
     * @Method({"GET", "POST"})
     */
    public function postBajaUsuarioAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if ($request->getMethod() == "POST") {
            //$content = $request->getContent();
            //$data = json_decode($content, true);
            $data = $request->request->all();
            $logger = $this->get('logger');
            //$logger->info("POST BajaUsuario: " . implode(';', $data));
            try {
                $this->compruebaArreglo(array('idusuario', 'gcmid'), $data);
                $telefono = $em->getRepository('YDIBackendBundle:Telefono')->findOneBy(array(
                    'gcmid' => $data['gcmid']
                ));
                $usuario = $em->getRepository('YDIBackendBundle:Usuario')
                        ->find($data['idusuario']);
                $usuarioReemplazo = $em->getRepository('YDIBackendBundle:Usuario')
                        ->find(1);
                if ($telefono) {
                    $telefono->setUsuario($usuarioReemplazo);
                    //$em->persist($telefono);
                    $em->flush();
                    $response = new JsonResponse(array('estatus' => 'ok'));
                } else {
                    throw new Exception('No existe registro de telefono con GCMID: ' . $data['gcmid']);
                }
            } catch (Exception $e) {
                $response = new JsonResponse(array('estatus' => 'error', 'motivo' => $e->getMessage()));
            }
        } else {
            $response = new JsonResponse(array('estatus' => 'error', 'motivo' => 'Ingresa los datos correctamente: idusuario, gcmid'));
        }
        return $response;
    }

    /**
     * @Route("/alta_contadores_evento_anuncio", name="post_alta_contadores_evento_anuncio")
     * @Method({"GET", "POST"})
     */
    public function postAltaContadoresEventoAnuncioAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if ($request->getMethod() == "POST") {
            $data = $request->request->all();
            $logger = $this->get('logger');
            //$logger->info("POST Contadores Evento Anuncio : " . implode(';', $data));
            try {
                $em->getConnection()->beginTransaction();
                $this->compruebaArreglo(array('id_usuario', 'id_evento', 'id_anuncio',
                    'fecha', 'contador'), $data);
                if (isset($data['id_usuario'])) {
                    $contadores = $this->parsearContadoresEventoAnuncio($data);
                    foreach ($contadores as $contador) {
                        $this->altaContadoresEventoAnuncio($contador, $em);
                    }
                }
                $em->getConnection()->commit();
                $response = new JsonResponse(array('estatus' => 'ok'));
            } catch (Exception $e) {
                $em->getConnection()->rollback();
                $em->close();
                $response = new JsonResponse(array('estatus' => 'error', 'motivo' => $e->getMessage()));
            }
        } else {
            $response = new JsonResponse(array('estatus' => 'error', 'motivo' => 'Ingresa los datos correctamente: idusuario,idanuncio,puntos,fecha,cestablecimiento,cydi,comentario'));
        }
        return $response;
    }

    /**
     * @Route("/alta_contadores_navegacion", name="post_alta_contadores_navegacion")
     * @Method({"GET", "POST"})
     */
    public function postAltaContadoresNavegacionAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if ($request->getMethod() == "POST") {
            $data = $request->request->all();
            $logger = $this->get('logger');
            //$logger->info("POST Contadores Navegacion : " . implode(';', $data));
            try {
                $em->getConnection()->beginTransaction();
                $this->compruebaArreglo(array('id_usuario', 'id_evento', 'id_vista',
                    'fecha', 'contador'), $data);
                if (isset($data['id_usuario'])) {
                    $contadores = $this->parsearContadoresNavegacion($data);
                    foreach ($contadores as $contador) {
                        $this->altaContadoresNavegacion($contador, $em);
                    }
                }
                $em->getConnection()->commit();
                $response = new JsonResponse(array('estatus' => 'ok'));
            } catch (Exception $e) {
                $em->getConnection()->rollback();
                $em->close();
                $response = new JsonResponse(array('estatus' => 'error', 'motivo' => $e->getMessage()));
            }
        } else {
            $response = new JsonResponse(array('estatus' => 'error', 'motivo' => 'Ingresa los datos correctamente: idusuario,idanuncio,puntos,fecha,cestablecimiento,cydi,comentario'));
        }
        return $response;
    }

    /**
     * @Route("/alta_contadores_vista_anuncio", name="post_alta_contadores_vista_anuncio")
     * @Method({"GET", "POST"})
     */
    public function postAltaContadoresVistaAnuncioAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if ($request->getMethod() == "POST") {
            $data = $request->request->all();
            $logger = $this->get('logger');
            //$logger->info("POST Contador Vista Anuncio : " . implode(';', $data));
            try {
                $em->getConnection()->beginTransaction();
                $this->compruebaArreglo(array('id_usuario', 'id_vista', 'id_anuncio',
                    'fecha', 'contador'), $data);
                if (isset($data['id_usuario'])) {
                    $contadores = $this->parsearContadoresVistaAnuncio($data);
                    foreach ($contadores as $contador) {
                        $this->altaContadoresVistaAnuncio($contador, $em);
                    }
                }
                $em->getConnection()->commit();
                $response = new JsonResponse(array('estatus' => 'ok'));
            } catch (Exception $e) {
                $em->getConnection()->rollback();
                $em->close();
                $response = new JsonResponse(array('estatus' => 'error', 'motivo' => $e->getMessage()));
            }
        } else {
            $response = new JsonResponse(array('estatus' => 'error', 'motivo' => 'Ingresa los datos correctamente: idusuario,idanuncio,puntos,fecha,cestablecimiento,cydi,comentario'));
        }
        return $response;
    }

    /**
     * @Route("/alta_contadores_vista_logo", name="post_alta_contadores_vista_logo")
     * @Method({"GET", "POST"})
     */
    public function postAltaContadoresVistaLogoAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if ($request->getMethod() == "POST") {
            $data = $request->request->all();
            $logger = $this->get('logger');
            //$logger->info("POST Contador Vista Logo : " . implode(';', $data));
            try {
                $em->getConnection()->beginTransaction();
                $this->compruebaArreglo(array('id_usuario', 'id_vista', 'id_establecimiento',
                    'fecha', 'contador'), $data);
                if (isset($data['id_usuario'])) {
                    $contadores = $this->parsearContadoresVistaLogo($data);
                    foreach ($contadores as $contador) {
                        $this->altaContadoresVistaLogo($contador, $em);
                    }
                }
                $em->getConnection()->commit();
                $response = new JsonResponse(array('estatus' => 'ok'));
            } catch (Exception $e) {
                $em->getConnection()->rollback();
                $em->close();
                $response = new JsonResponse(array('estatus' => 'error', 'motivo' => $e->getMessage()));
            }
        } else {
            $response = new JsonResponse(array('estatus' => 'error', 'motivo' => 'Ingresa los datos correctamente: idusuario,idanuncio,puntos,fecha,cestablecimiento,cydi,comentario'));
        }
        return $response;
    }

    /**
     * @Route("/alta_errores", name="post_alta_errores")
     * @Method({"GET", "POST"})
     */
    public function postAltaErroresAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if ($request->getMethod() == "POST") {
            //$content = $request->getContent();
            //$data = json_decode($content, true);
            $data = $request->request->all();
            $logger = $this->get('logger');
            $logger->info("POST Error: " . implode(';', $data));
            try {
                $this->compruebaArreglo(array('stacktrace', 'brand', 'device', 'model', 'id',
                    'product', 'sdk', 'lanzamiento', 'incremental', 'id_usuario', 'fecha', 'ydiversion'), $data);
                $usuario = $em->getRepository('YDIBackendBundle:Usuario')->find($data['id_usuario']);
                if (!$usuario) {
                    $response = new JsonResponse(array('estatus' => 'error', 'motivo' => 'No existe registro de usuario con ID: ' . $data['idusuario']));
                } else {
                    $error = new \YDI\BackendBundle\Entity\Errores();
                    $error->setUsuario($usuario);
                    $error->setStacktrace($data['stacktrace']);
                    $error->setBrand($data['brand']);
                    $error->setDevice($data['device']);
                    $error->setModel($data['model']);
                    $error->setId($data['id']);
                    $error->setProduct($data['product']);
                    $error->setSdk($data['sdk']);
                    $error->setRelease($data['release']);
                    $error->setIncremental($data['incremental']);
                    $error->setFecha(new \DateTime($data['fecha']));
                    $error->setYdiversion($data['ydiversion']);
                    $em->persist($error);
                    $em->flush();
                    $response = new JsonResponse(array('estatus' => 'ok'));
                }
            } catch (Exception $e) {
                $response = new JsonResponse(array('estatus' => 'error', 'motivo' => $e->getMessage()));
                $logger = $this->get('logger');
                $logger->info("POST Errores Exception: " + $e->getMessage());
            }
        } else {
            $response = new JsonResponse(array('estatus' => 'error', 'motivo' => 'Ingresa los datos correctamente'));
        }
        return $response;
    }

    private function altaPreferencia($data, &$em, $usuario = null) {
        if (!$usuario) {
            $usuario = $em->getRepository('YDIBackendBundle:Usuario')->find($data['idusuario']);
        }
        $preferencia = $em->getRepository('YDIBackendBundle:Preferencias')->find($data['idpreferencia']);
        $subnivel = $em->getRepository('YDIBackendBundle:SubnivelPreferencia')->findOneBy(array(
            'nombre' => $preferencia->getNombre()
        ));
        if (!$subnivel) {
            $subnivel = new \YDI\BackendBundle\Entity\SubnivelPreferencia();
            $subnivel->setNombre($preferencia->getNombre());
            $subnivel->setPreferencias($preferencia);
            $em->persist($subnivel);
            $em->flush();
        }

        if (!$usuario) {
            throw new Exception('No existe registro de usuario con ID: ' . $data['idusuario']);
        } else if (!$preferencia) {
            throw new Exception('No existe registro de usuario con ID: ' . $data['idpreferencia']);
        } else {
            $preferenciasUsuario = new \YDI\BackendBundle\Entity\PreferenciasUsuario();
            $preferenciasUsuario->setUsuario($usuario);
            $preferenciasUsuario->setSubnivelPreferencia($subnivel);
            $preferenciasUsuario->setActivoFlag($data['activoflag']);
            $em->persist($preferenciasUsuario);
            $em->flush();
        }
        return true;
    }

    private function altaTarjeta($data, &$em, $usuario = null) {
        if (!$usuario) {
            $usuario = $em->getRepository('YDIBackendBundle:Usuario')->find($data['idusuario']);
        }
        $emisor = $em->getRepository('YDIBackendBundle:Emisor')->find($data['emisor']);
        $tipo = $em->getRepository('YDIBackendBundle:TiposTarjeta')->find($data['tipo']);
        $operador = $em->getRepository('YDIBackendBundle:OperadorFinanciero')->find($data['operador']);
        $teo = $em->getRepository('YDIBackendBundle:TarjetaEmisorOperador')->findOneBy(array(
            'tiposTarjeta' => $data['tipo'],
            'emisor' => $data['emisor'],
            'operadorFinanciero' => $data['operador']
        ));

        if (!$emisor) {
            throw new Exception('No existe registro de emisor con ID: ' . $data['emisor']);
        } else if (!$tipo) {
            throw new Exception('No existe registro de tipostajeta con ID: ' . $data['tipo']);
        } else if (!$operador) {
            throw new Exception('No existe registro de operador con ID: ' . $data['operador']);
        } else if (!$usuario) {
            throw new Exception('No existe registro de usuario con ID: ' . $data['idusuario']);
        } else if (!$teo) {
            throw new Exception('No existe la combinacion Tipo-Emisor-Operador: ' . $data['tipo'] . '-' . $data['emisor'] . '-' . $data['operador']);
        } else {
            $tarjeta = new \YDI\BackendBundle\Entity\Tarjeta();
            $tarjeta->setUsuario($usuario);
            $tarjeta->setEmisor($emisor);
            $tarjeta->setOperadorFinanciero($operador);
            $tarjeta->setTiposTarjeta($tipo);
            $tarjeta->setFechaVencimiento(new \DateTime($data['fechavencimiento']));
            $tarjeta->setUltimosDigitos($data['ultimosdigitos']);
            $em->persist($tarjeta);
            $em->flush();
        }
        return true;
    }

    private function altaContadoresEventoAnuncio($data, &$em, $usuario = null) {
        $usuario = $em->getRepository('YDIBackendBundle:Usuario')->find($data['id_usuario']);
        $anuncio = $em->getRepository('YDIBackendBundle:Anuncio')->find($data['id_anuncio']);
        $evento = $em->getRepository('YDIBackendBundle:Evento')->find($data['id_evento']);
        if (!$usuario) {
            throw new Exception('No existe registro de usuario con ID: ' . $data['id_usuario']);
        } else if (!$anuncio) {
            throw new Exception('No existe registro de anuncio con ID: ' . $data['id_anuncio']);
        } else if (!$evento) {
            throw new Exception('No existe registro de evento con ID: ' . $data['id_evento']);
        } else {
            $registro = $em->getRepository('YDIBackendBundle:ContadorEventoAnuncio')
                    ->findOneBy(array(
                'usuario' => $usuario->getId(),
                'evento' => $evento->getId(),
                'anuncio' => $anuncio->getId(),
                'fecha' => new \DateTime($data['fecha'])
            ));
            if (!$registro) {
                $registro = new \YDI\BackendBundle\Entity\ContadorEventoAnuncio();
            }
            $registro->setUsuario($usuario);
            $registro->setAnuncio($anuncio);
            $registro->setEvento($evento);
            $registro->setFecha(new \DateTime($data['fecha']));
            $registro->setContador($data['contador']);
            $em->persist($registro);
            $em->flush();
            return true;
        }
    }

    private function altaContadoresNavegacion($data, &$em, $usuario = null) {
        $usuario = $em->getRepository('YDIBackendBundle:Usuario')->find($data['id_usuario']);
        $evento = $em->getRepository('YDIBackendBundle:Evento')->find($data['id_evento']);
        $vista = $em->getRepository('YDIBackendBundle:Vista')->find($data['id_vista']);
        if (!$usuario) {
            throw new Exception('No existe registro de usuario con ID: ' . $data['id_usuario']);
        } else if (!$evento) {
            throw new Exception('No existe registro de evento con ID: ' . $data['id_evento']);
        } else if (!$vista) {
            throw new Exception('No existe registro de vista con ID: ' . $data['id_vista']);
        } else {
            $registro = $em->getRepository('YDIBackendBundle:ContadoresNavegacion')
                    ->findOneBy(array(
                'usuario' => $usuario->getId(),
                'evento' => $evento->getId(),
                'vista' => $vista->getId(),
                'fecha' => new \DateTime($data['fecha'])
            ));
            if (!$registro) {
                $registro = new \YDI\BackendBundle\Entity\ContadoresNavegacion();
            }
            $registro->setUsuario($usuario);
            $registro->setEvento($evento);
            $registro->setVista($vista);
            $registro->setFecha(new \DateTime($data['fecha']));
            $registro->setContador($data['contador']);
            $em->persist($registro);
            $em->flush();
            return true;
        }
    }

    private function altaContadoresVistaAnuncio($data, &$em, $usuario = null) {
        $usuario = $em->getRepository('YDIBackendBundle:Usuario')->find($data['id_usuario']);
        $anuncio = $em->getRepository('YDIBackendBundle:Anuncio')->find($data['id_anuncio']);
        $vista = $em->getRepository('YDIBackendBundle:Vista')->find($data['id_vista']);
        if (!$usuario) {
            throw new Exception('No existe registro de usuario con ID: ' . $data['id_usuario']);
        } else if (!$anuncio) {
            throw new Exception('No existe registro de anuncio con ID: ' . $data['id_anuncio']);
        } else if (!$vista) {
            throw new Exception('No existe registro de vista con ID: ' . $data['id_vista']);
        } else {
            $registro = $em->getRepository('YDIBackendBundle:ContadorVistaAnuncio')
                    ->findOneBy(array(
                'usuario' => $usuario->getId(),
                'anuncio' => $anuncio->getId(),
                'vista' => $vista->getId(),
                'fecha' => new \DateTime($data['fecha'])
            ));
            if (!$registro) {
                $registro = new \YDI\BackendBundle\Entity\ContadorVistaAnuncio();
            }
            $registro->setUsuario($usuario);
            $registro->setAnuncio($anuncio);
            $registro->setVista($vista);
            $registro->setFecha(new \DateTime($data['fecha']));
            $registro->setContador($data['contador']);
            $em->persist($registro);
            $em->flush();
            return true;
        }
    }

    private function altaContadoresVistaLogo($data, &$em, $usuario = null) {
        $usuario = $em->getRepository('YDIBackendBundle:Usuario')->find($data['id_usuario']);
        $vista = $em->getRepository('YDIBackendBundle:Vista')->find($data['id_vista']);
        $establecimiento = $em->getRepository('YDIBackendBundle:Establecimiento')
                ->find($data['id_establecimiento']);
        if (!$usuario) {
            throw new Exception('No existe registro de usuario con ID: ' . $data['id_usuario']);
        } else if (!$establecimiento) {
            throw new Exception('No existe registro de establecimiento con ID: ' . $data['id_establecimiento']);
        } else if (!$vista) {
            throw new Exception('No existe registro de vista con ID: ' . $data['id_vista']);
        } else {
            $registro = $em->getRepository('YDIBackendBundle:ContadoresVistaLogo')
                    ->findOneBy(array(
                'usuario' => $usuario->getId(),
                'establecimiento' => $establecimiento->getId(),
                'vista' => $vista->getId(),
                'fecha' => new \DateTime($data['fecha'])
            ));
            if (!$registro) {
                $registro = new \YDI\BackendBundle\Entity\ContadoresVistaLogo();
            }
            $registro->setUsuario($usuario);
            $registro->setEstablecimiento($establecimiento);
            $registro->setVista($vista);
            $registro->setFecha(new \DateTime($data['fecha']));
            $registro->setContador($data['contador']);
            $em->persist($registro);
            $em->flush();
            return true;
        }
    }

    private function compruebaArreglo($search, $source) {
        $bandera = false;
        $cadena = "";
        foreach ($search as $key) {
            if (!array_key_exists($key, $source)) {
                $bandera = true;
                $cadena = "El campo $key es requerido para esta operacion";
                break;
            }
        }
        if ($bandera) {
            throw new Exception($cadena);
        }
    }

    private function parsearTarjetasDeUsuario($data) {
        $aUltimosdigitos = $data['ultimosdigitos'];
        $aFechavencimiento = $data['fechavencimiento'];
        $aTipo = $data['tipo'];
        $aEmisor = $data['emisor'];
        $aOperador = $data['operador'];
        $datos = array();
        $largo = count($aUltimosdigitos);
        for ($cont = 0; $cont < $largo; $cont++) {
            $arregloData = array(
                'ultimosdigitos' => $aUltimosdigitos[$cont],
                'fechavencimiento' => $aFechavencimiento[$cont],
                'tipo' => $aTipo[$cont],
                'emisor' => $aEmisor[$cont],
                'operador' => $aOperador[$cont]
            );
            $datos[] = $arregloData;
        }
        return $datos;
    }

    private function parsearPreferenciasUsuario($data) {
        $aPrefencias = $data['idpreferencia'];
        $aActivoFlag = $data['activoflag'];
        $datos = array();
        $largo = count($aPrefencias);
        for ($cont = 0; $cont < $largo; $cont++) {
            $arregloData = array(
                'idpreferencia' => $aPrefencias[$cont],
                'activoflag' => $aActivoFlag[$cont]
            );
            $datos[] = $arregloData;
        }
        return $datos;
    }

    private function parsearContadoresNavegacion($data) {
        $aUsuarios = $data['id_usuario'];
        $aEventos = $data['id_evento'];
        $aVistas = $data['id_vista'];
        $aFechas = $data['fecha'];
        $aContadores = $data['contador'];
        $datos = array();
        $largo = count($aUsuarios);
        for ($cont = 0; $cont < $largo; $cont++) {
            $arregloData = array(
                'id_usuario' => $aUsuarios[$cont],
                'id_evento' => $aEventos[$cont],
                'id_vista' => $aVistas[$cont],
                'fecha' => $aFechas[$cont],
                'contador' => $aContadores[$cont]
            );
            $datos[] = $arregloData;
        }
        return $datos;
    }

    private function parsearContadoresVistaLogo($data) {
        $aUsuarios = $data['id_usuario'];
        $aEstablecimientos = $data['id_establecimiento'];
        $aVistas = $data['id_vista'];
        $aFechas = $data['fecha'];
        $aContadores = $data['contador'];
        $datos = array();
        $largo = count($aUsuarios);
        for ($cont = 0; $cont < $largo; $cont++) {
            $arregloData = array(
                'id_usuario' => $aUsuarios[$cont],
                'id_establecimiento' => $aEstablecimientos[$cont],
                'id_vista' => $aVistas[$cont],
                'fecha' => $aFechas[$cont],
                'contador' => $aContadores[$cont]
            );
            $datos[] = $arregloData;
        }
        return $datos;
    }

    private function parsearContadoresEventoAnuncio($data) {
        $aUsuarios = $data['id_usuario'];
        $aAnuncios = $data['id_anuncio'];
        $aEventos = $data['id_evento'];
        $aFechas = $data['fecha'];
        $aContadores = $data['contador'];
        $datos = array();
        $largo = count($aUsuarios);
        for ($cont = 0; $cont < $largo; $cont++) {
            $arregloData = array(
                'id_usuario' => $aUsuarios[$cont],
                'id_evento' => $aEventos[$cont],
                'id_anuncio' => $aAnuncios[$cont],
                'fecha' => $aFechas[$cont],
                'contador' => $aContadores[$cont]
            );
            $datos[] = $arregloData;
        }
        return $datos;
    }

    private function parsearContadoresVistaAnuncio($data) {
        $aUsuarios = $data['id_usuario'];
        $aAnuncios = $data['id_anuncio'];
        $aVistas = $data['id_vista'];
        $aFechas = $data['fecha'];
        $aContadores = $data['contador'];
        $datos = array();
        $largo = count($aUsuarios);
        for ($cont = 0; $cont < $largo; $cont++) {
            $arregloData = array(
                'id_usuario' => $aUsuarios[$cont],
                'id_anuncio' => $aAnuncios[$cont],
                'id_vista' => $aVistas[$cont],
                'fecha' => $aFechas[$cont],
                'contador' => $aContadores[$cont]
            );
            $datos[] = $arregloData;
        }
        return $datos;
    }

    private function eliminarTarjetas(&$usuario, &$em) {
        $tarjetas = $usuario->getTarjetas();
        foreach ($tarjetas as $tarjeta) {
            $em->remove($tarjeta);
        }
        $em->flush();
    }

    private function eliminarPreferencias(&$usuario, &$em) {
        $preferencias = $usuario->getPreferenciasUsuario();
        foreach ($preferencias as $preferencia) {
            $em->remove($preferencia);
        }
        $em->flush();
    }

    private function actualizarGCMID(&$data, &$usuario, &$em) {
        $telefono1 = null;
        $telefono1 = $em->getRepository('YDIBackendBundle:Telefono')->findOneBy(array(
            'gcmid' => $data['gcmid']
        ));
        if ($usuario->getId() != 1) {
            $telefonos = $em->getRepository('YDIBackendBundle:Telefono')->findBy(array(
                'usuario' => $usuario->getId(),
                'registroBorrado' => false
            ));
            foreach ($telefonos as $telefono) {
                $telefono->setRegistroBorrado(true);
                $em->flush();
            }
        }
        if (!$telefono1) {
            $telefono1 = new \YDI\BackendBundle\Entity\Telefono();
        }
        $telefono1->setGcmid($data['gcmid']);
        $telefono1->setUsuario($usuario);
        $telefono1->setFechaDescarga(new \DateTime());
        $telefono1->setRegistroBorrado(false);
        $telefono1->setInactivo(false);
        if (!$telefono1->getId()) {
            $em->persist($telefono1);
        }
        $em->flush();
    }


    /**
     * @Route("/contacto", name="contacto")
     * @Method({"GET", "POST"})
     */
    public function contactoAction(Request $request) {
        $contacto = new Contacto();
        $form = $this->createForm(new ContactoType(), $contacto);
        $em = $this->getDoctrine()->getManager();
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $emailContacto = 'ydi@gmail.com';
                $datos = $form->getData();
                $message = \Swift_Message::newInstance()
                        ->setSubject('Contacto desde pagina')
                        ->setFrom($datos->getEmail())
                        ->setTo($emailContacto)
                        ->setBody($this->renderView('AppBundle:Default:contactoEmail.html.twig', array('datos' => $datos)), 'text/html');
                $this->get('mailer')->send($message);
                // Redirige - Esto es importante para prevenir que el usuario
                // reenvíe el formulario si actualiza la página
                $status = 'send';
                $mensaje = "Se ha enviado el mensaje";
                $contacto = new Contacto();
                $form = $this->createForm(new ContactoType(), $contacto);
            } else {
                $status = 'notsend';
                $mensaje = "El mensaje no se ha podido enviar";
            }
        } else {
            $status = 'new';
            $mensaje = "";
        }
        if ($request->isXmlHttpRequest()) {
            $vista = $this->renderView('AppBundle:Default:formContacto.html.twig', array(
                'form' => $form->createView(),
                'status' => $status,
                'mensaje' => $mensaje,
            ));
            return new JsonResponse(array(
                'form' => $vista,
                'status' => $status,
                'mensaje' => $mensaje,
            ));
        }
        return $this->render('AppBundle:Default:contacto.html.twig', array(
                    'form' => $form->createView(),
                    'status' => $status,
                    'mensaje' => $mensaje
        ));
    }
    
    /**
     * @Route("/legales", name="legales")
     * @Template()
     * @Method({"GET"})
     */
    public function legalesAction(Request $request) {
        return array();
    }

}
