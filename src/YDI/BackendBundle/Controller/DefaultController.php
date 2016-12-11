<?php

namespace YDI\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use PHPExcel_Cell;

class DefaultController extends Controller {

    /**
     * @Route("/backend/", name="backend_home")
     * @Template()
     */
    public function indexAction() {
        return array('name' => 'richpolis');
    }

    private function getFileNameCargaInitial() {
        $uploads = $this->getParameter('uploads.initial');
        if (!file_exists($uploads)) {
            mkdir($uploads, 0777);
        }
        $nombreArchivo = $this->get_first_file_path($uploads);
        if ($nombreArchivo == '') {
            $nombreArchivo = "carga_initial.xls";
        }
        $fileName = $uploads . '/' . $nombreArchivo;
        return $fileName;
    }

    private function getFileNameCargaUpdates() {
        $uploads = $this->getParameter('uploads.updates');
        if (!file_exists($uploads)) {
            mkdir($uploads, 0777);
        }
        $nombreArchivo = $this->get_first_file_path($uploads);
        if ($nombreArchivo == '') {
            $nombreArchivo = "carga_updates.xls";
        }
        $fileName = $uploads . '/' . $nombreArchivo;
        return $fileName;
    }

    /**
     * @Route("/backend/carga/inicial/", name="backend_upload_initial")
     * @Template()
     */
    public function uploadInitialAction(Request $request) {
        $cargas = array();

        $fileName = $this->getFileNameCargaInitial();

        if ($request->getMethod() == 'POST') {
            $infoCarga = array();
            foreach ($request->files as $archivo) {
                if ($archivo[0] instanceof UploadedFile) {
                    $uploads = $this->getParameter('uploads.initial');
                    if (!file_exists($uploads)) {
                        mkdir($uploads, 0777);
                    }
                    $this->remove_files_path($uploads);
                    $nombreArchivo = $archivo[0]->getClientOriginalName();
                    $fileName = $uploads . '/' . $nombreArchivo;
                    if (file_exists($fileName)) {
                        unlink($fileName);
                    }
                    $archivo[0]->move(
                            $uploads, $nombreArchivo
                    );
                }
            }
        }
        if (file_exists($fileName)) {
            $cargas = $this->getResumenImportar($fileName);
        }
        return array('cargas' => $cargas);
    }

    /**
     * @Route("/backend/load/initial/{index}", name="backend_load_initial")
     * @Method({"POST"})
     */
    public function loadInitialAction(Request $request, $index = null) {
        $fileName = $this->getFileNameCargaInitial();
        $infoCarga = $this->importar($fileName, $index);
        $response = new JsonResponse($infoCarga);
        return $response;
    }

    /**
     * @Route("/backend/carga/actualizaciones/", name="backend_upload_updates")
     * @Template()
     */
    public function uploadUpdatesAction(Request $request) {
        $cargas = array();

        $fileName = $this->getFileNameCargaUpdates();

        if ($request->getMethod() == 'POST') {
            $infoCarga = array();
            foreach ($request->files as $archivo) {
                if ($archivo[0] instanceof UploadedFile) {
                    $uploads = $this->getParameter('uploads.updates');
                    if (!file_exists($uploads)) {
                        mkdir($uploads, 0777);
                    }
                    $this->remove_files_path($uploads);
                    $nombreArchivo = $archivo[0]->getClientOriginalName();
                    $fileName = $uploads . '/' . $nombreArchivo;
                    if (file_exists($fileName)) {
                        unlink($fileName);
                    }
                    $archivo[0]->move(
                            $uploads, $nombreArchivo
                    );
                }
            }
        }
        if (file_exists($fileName)) {
            $cargas = $this->getResumenImportar($fileName);
        }
        return array('cargas' => $cargas);
    }

    /**
     * @Route("/backend/load/updates/{index}", name="backend_load_updates")
     * @Method({"POST"})
     */
    public function loadUpdatesAction(Request $request, $index = null) {
        $fileName = $this->getFileNameCargaUpdates();
        $infoCarga = $this->importar($fileName, $index);
        $response = new JsonResponse($infoCarga);
        return $response;
    }

    /**
     * @Route("/backend/carga/imagenes/", name="backend_upload_images")
     * @Template()
     */
    public function uploadImagesAction(Request $request) {
        $phpconteo = 0;
        $arreglo = array();
        if ($request->getMethod() == 'POST') {
            foreach ($request->files as $imagenes) {
                foreach ($imagenes as $imagen) {
                    if ($imagen instanceof UploadedFile) {
                        $uploads = $this->getParameter('uploads.images');
                        if (!file_exists($uploads)) {
                            mkdir($uploads, 0777);
                        }
                        $fileName = $uploads . '/' . $imagen->getClientOriginalName();
                        if (file_exists($fileName)) {
                            unlink($fileName);
                        }
                        $imagen->move(
                                $uploads, $imagen->getClientOriginalName()
                        );
                        $archivoActual = "/uploads/images/" . $imagen->getClientOriginalName();
                        $arreglo[] = $archivoActual;
                    }
                }
            }
            return new JsonResponse($arreglo);
        }
    }

    private function getResumenImportar($filename) {
        //cargamos el archivo a procesar.
        $objPHPExcel = $this->get('phpexcel')->createPHPExcelObject($filename);
        //se obtienen las hojas, el nombre de las hojas y se pone activa la primera hoja
        $total_sheets = $objPHPExcel->getSheetCount();
        $allSheetName = $objPHPExcel->getSheetNames();

        for ($i = 0; $i < $total_sheets; $i++) {
            $allSheetName[$i] = strtolower($allSheetName[$i]);
        }
        return $allSheetName;
    }

    private function importar($filename, $index = null) {
        //cargamos el archivo a procesar.
        $objPHPExcel = $this->get('phpexcel')->createPHPExcelObject($filename);
        //se obtienen las hojas, el nombre de las hojas y se pone activa la primera hoja
        $total_sheets = $objPHPExcel->getSheetCount();
        $allSheetName = $objPHPExcel->getSheetNames();
        for ($i = 0; $i < $total_sheets; $i++) {
            $allSheetName[$i] = strtolower($allSheetName[$i]);
        }
        $em = $this->getDoctrine()->getManager();
        $em->getConnection()->beginTransaction();
        $infoCarga = [];
        $error = false;
        $currentTable = '';
        $codigosNotificaciones = array();
        for ($cont = 0; $cont < $total_sheets; $cont++) {
            if ($index == null) {
                $currentTable = $allSheetName[$cont];
                $objWorksheet = $objPHPExcel->setActiveSheetIndex($cont);
            } else {
                $currentTable = $allSheetName[$index - 1];
                $objWorksheet = $objPHPExcel->setActiveSheetIndex($index - 1);
            }
            //Se obtiene el número máximo de filas
            $highestRow = $objWorksheet->getHighestRow();
            //Se obtiene el número máximo de columnas
            $highestColumn = $objWorksheet->getHighestColumn();
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            //$headingsArray contiene las cabeceras de la hoja excel. Llos titulos de columnas
            $headingsArray = $objWorksheet->rangeToArray('A1:' . $highestColumn . '1', null, true, true, true);
            $headingsArray = $headingsArray[1];
            foreach ($headingsArray as $columnKey => $columnHeading) {
                $headingsArray[$columnKey] = strtolower(trim($columnHeading));
            }

            //Se recorre toda la hoja excel desde la fila 2 y se almacenan los datos
            $r = -1;
            $namedDataArray = array();
            for ($row = 2; $row <= $highestRow; ++$row) {
                $dataRow = $objWorksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, true, true);
                if ((isset($dataRow[$row]['A'])) && ($dataRow[$row]['A'] > '')) {
                    ++$r;
                    foreach ($headingsArray as $columnKey => $columnHeading) {
                        $namedDataArray[$r][$columnHeading] = $dataRow[$row][$columnKey];
                    } //endforeach
                } //endif
            }

            try {
                $codigo = $this->loadTo($currentTable, $namedDataArray, $em, $infoCarga);
                $em->flush();
                $codigosNotificaciones[$codigo] = true;
            } catch (\Exception $e) {
                $em->getConnection()->rollback();
                $em->close();
                $infoCarga[] = array('tabla' => $currentTable, 'error' => $e->getMessage());
                $error = true;
                break;
            }

            if ($index != null) {
                break;
            }
        }

        try {
            if (count($codigosNotificaciones) > 0) {
                $em->getRepository('YDIBackendBundle:Telefono')
                        ->updateNotificaciones($codigosNotificaciones);
            }
        } catch (\Exception $e) {
            $em->getConnection()->rollback();
            $em->close();
            $infoCarga[] = array('tabla' => 'telefono', 'error' => $e->getMessage());
            $error = true;
        }

        if (!$error) {
            $em->getConnection()->commit();
        }
        return $infoCarga;
    }

    private function loadTo($tabla, &$registros, &$em, &$infoCarga) {
        if ($tabla == "pais") {
            $this->loadToPaises($registros, $em, $infoCarga);
            return '';
        } else if ($tabla == "estado" || $tabla == "estados") {
            $this->loadToEstados($registros, $em, $infoCarga);
            return '';
        } else if ($tabla == "codigop") {
            $this->loadToCodigosPostales($registros, $em, $infoCarga);
            return '';
        } else if ($tabla == "usuario" || $tabla == "usuarios") {
            $this->loadToUsuarios($registros, $em, $infoCarga);
            return '';
        } else if ($tabla == "operadorfinanciero") {
            $this->loadToOperadorFinanciero($registros, $em, $infoCarga);
            return '';
        } else if ($tabla == "emisor") {
            $this->loadToEmisores($registros, $em, $infoCarga);
            return '';
        } else if ($tabla == "tipostarjeta") {
            $this->loadToTiposTarjeta($registros, $em, $infoCarga);
            return '';
        } else if ($tabla == "tarjeta_emisoroperador") {
            $this->loadToTarjetaEmisorOperador($registros, $em, $infoCarga);
            return '12';
        } else if ($tabla == "preferencias") {
            $this->loadToPreferencias($registros, $em, $infoCarga);
            return '';
        } else if ($tabla == "evento") {
            $this->loadToEventos($registros, $em, $infoCarga);
            return '';
        } else if ($tabla == "vista") {
            $this->loadToVistas($registros, $em, $infoCarga);
            return '';
        } else if ($tabla == "grupo_establecimiento") {
            $this->loadToGrupoEstablecimiento($registros, $em, $infoCarga);
            return '';
        } else if ($tabla == "establecimiento") {
            $this->loadToEstablecimientos($registros, $em, $infoCarga);
            return '';
        } else if ($tabla == "googleplaces" || $tabla == "googleplace" || $tabla == "google_places") {
            $this->loadToGooglePlaces($registros, $em, $infoCarga);
            return '';
        } else if ($tabla == "socios") {
            $this->loadToSocios($registros, $em, $infoCarga);
            return '';
        } else if ($tabla == "premios") {
            $this->loadToPremios($registros, $em, $infoCarga);
            return '';
        } else if ($tabla == "tarjeta" || $tabla == "tarjetas") {
            $this->loadToTarjetas($registros, $em, $infoCarga);
            return '';
        } else if ($tabla == "anuncio" || $tabla == "anuncios") {
            $this->loadToAnuncios($registros, $em, $infoCarga);
            return '11';
        } else if ($tabla == "anuncios_tarjeta" || $tabla == "anuncio_tarjeta") {
            $this->loadToTarjetaEmisorOperadorAnuncio($registros, $em, $infoCarga);
            return '11';
        } else if ($tabla == "preferencias_anuncio") {
            $this->loadToPreferenciasAnuncio($registros, $em, $infoCarga);
            return '11';
        } else if ($tabla == "condiciones") {
            $this->loadToCondiciones($registros, $em, $infoCarga);
            return '11';
        } else if ($tabla == "codigop_anuncio") {
            $this->loadToCodigoPostalAnuncio($registros, $em, $infoCarga);
            return '11';
        } else if ($tabla == "palabras_clave") {
            $this->loadToPalabrasClave($registros, $em, $infoCarga);
            return '11';
        } else if ($tabla == "preferencias_usuario") {
            $this->loadToPreferenciasUsuario($registros, $em, $infoCarga);
            return '';
        } else if ($tabla == "subnivel_preferencia" || $tabla == "subnivel_preferencias") {
            $this->loadToSubnivelPreferencias($registros, $em, $infoCarga);
            return '';
        } else if ($tabla == "noticias" || $tabla == "noticia") {
            $this->loadToNoticias($registros, $em, $infoCarga);
            return '13';
        }
    }

    /*
     * De carga inicial.
     */

    private function loadToPaises($registros = null, &$em, &$infoCarga) {
        $pais = null;
        $cont = 0;
        if ($registros && count($registros) > 0) {
            $this->valid_fields($registros[0], array('nombre', 'action', 'nuevo'));
            foreach ($registros as $registro) {
                $pais = $em->getRepository('YDIBackendBundle:Pais')->findOneBy(array(
                    'nombre' => $registro['nombre']
                ));
                $cont++;
                if ($registro['action'] == 'C') {
                    if (!$pais) {
                        $nombre = $registro['nombre'];
                        throw new \Exception("$cont.- El pais con nombre: $nombre, no existe. No es posible hacer cambios en el registro.");
                        break;
                    }
                    if (strlen(trim($registro['nuevo']))) {
                        $registro['nombre'] = $registro['nuevo'];
                    }
                } else if ($registro['action'] == 'A') {
                    if ($pais) {
                        $nombre = $registro['nombre'];
                        throw new \Exception("$cont.- El pais con nombre: $nombre, ya existe. No es posible realizar la alta nuevamente.");
                        break;
                    }
                    $pais = new \YDI\BackendBundle\Entity\Pais();
                } else {
                    throw new \Exception("$cont.- Solo es posible Agregar o Cambiar Paises ");
                }
                $pais->setNombre($registro['nombre']);
                $em->persist($pais);
                $em->flush();
            }
            $infoCarga[] = array('tabla' => 'pais', 'registros' => $cont);
        }
    }

    /*
     * De carga inicial.
     */

    private function loadToEstados($registros = null, &$em, &$infoCarga) {
        $pais = null;
        $estado = null;
        $cont = 0;
        if ($registros && count($registros) > 0) {
            $this->valid_fields($registros[0], array('nombre', 'action', 'pais', 'nuevo'));
            foreach ($registros as $registro) {
                $this->getPais($registro['pais'], $pais, $em);
                $this->getEstadoDePais($registro['nombre'], $pais, $estado, $em);
                if (isset($registro['action']) && $registro['action'] == 'C') {
                    if (strlen(trim($registro['nuevo']))) {
                        $estado->setNombre($registro['nuevo']);
                    }
                    $em->flush();
                }
                $cont++;
            }
            $infoCarga[] = array('tabla' => 'estado', 'registros' => $cont);
        }
    }

    /*
     * De carga inicial.
     */

    private function loadToCodigosPostales($registros = null, &$em, &$infoCarga) {
        $estado = null;
        $codigo = null;
        $cont = 0;
        if ($registros && count($registros) > 0) {
            $this->valid_fields($registros[0], array('codigop', 'estado', 'nuevo'));
            foreach ($registros as $registro) {
                $this->getEstado($registro['estado'], $estado, $em);
                $this->getCodigoPostalDeEstado($registro['codigop'], $estado, $codigo, $em);
                $cont++;
            }
            $infoCarga[] = array('tabla' => 'codigop', 'registros' => $cont);
        }
    }

    /*
     * De carga inicial.
     */

    private function loadToUsuarios($registros = null, &$em, &$infoCarga) {
        $codigoPostal = null;
        $usuario = null;
        $cont = 0;
        $nuevo = false;
        if ($registros && count($registros) > 0) {
            $this->valid_fields($registros[0], array('id', 'email', 'action', 'nombre',
                'url', 'telefono', 'fecha_settings', 'codigop'));
            foreach ($registros as $registro) {
                $this->getCodigoPostal($registro['codigop'], $codigoPostal, $em);
                //$this->getUsuarioForEmail($registro, $usuario, $em);
                $usuario = $em->getRepository('YDIBackendBundle:Usuario')->findOneBy(array(
                    'email' => $registro['email']
                ));
                $cont++;
                if ($registro['action'] == 'C') {
                    $nuevo = false;
                    if (!$usuario) {
                        $email = $registro['email'];
                        throw new \Exception("$cont.- El usuario con email: $email no existe, no es posible hacer cambios en el registro.");
                        break;
                    }
                } else if ($registro['action'] == 'A') {
                    $nuevo = true;
                    if ($usuario) {
                        $email = $registro['email'];
                        throw new \Exception("$cont.- El usuario con email: $email ya existe. No es posible realizar la alta nuevamente.");
                        break;
                    }
                    $usuario = new \YDI\BackendBundle\Entity\Usuario();
                    $usuario->setEmail($registro['email']);
                    if (isset($registro['id'])) {
                        $usuario->setId($registro['id']);
                    }
                } else {
                    throw new \Exception("$cont.- Solo es posible Agregar o Cambiar Usuarios ");
                }
                $usuario->setNombre($registro['nombre']);
                $usuario->setUrl($registro['url']);
                $usuario->setTelefono($registro['telefono']);
                $usuario->setFechaSettings(new \DateTime($registro['fecha_settings']));
                $usuario->setCodigoPostal($codigoPostal);
                if ($nuevo)
                    $em->persist($usuario);
                else
                    $em->flush();
            }
            $infoCarga[] = array('tabla' => 'usuario', 'registros' => $cont);
        }
    }

    /*
     * De carga inicial.
     */

    private function loadToOperadorFinanciero($registros = null, &$em, &$infoCarga) {
        $operadorFinanciero = null;
        $cont = 0;
        if ($registros && count($registros) > 0) {
            $this->valid_fields($registros[0], array('nombre', 'action', 'nuevo'));
            foreach ($registros as $registro) {
                $operadorFinanciero = $em->getRepository('YDIBackendBundle:OperadorFinanciero')->findOneBy(array(
                    'nombre' => $registro['nombre']
                ));
                $cont++;
                if ($registro['action'] == 'C') {
                    if (!$operadorFinanciero) {
                        $nombre = $registro['nombre'];
                        throw new \Exception("$cont.- El Operador Financiero con nombre: $nombre, no existe. No es posible hacer cambios en el registro.");
                        break;
                    }
                    if (strlen(trim($registro['nuevo']))) {
                        $registro['nombre'] = $registro['nuevo'];
                    }
                } else if ($registro['action'] == 'A') {
                    if ($operadorFinanciero) {
                        $nombre = $registro['nombre'];
                        throw new \Exception("$cont.- El Operador Financiero con nombre: $nombre, ya existe. No es posible realizar la alta nuevamente.");
                        break;
                    }
                    $operadorFinanciero = new \YDI\BackendBundle\Entity\OperadorFinanciero();
                } else {
                    throw new \Exception("$cont.- Solo es posible Agregar o Cambiar Operadores Financieros ");
                }
                $operadorFinanciero->setNombre($registro['nombre']);
                $em->persist($operadorFinanciero);
                $em->flush();
            }
            $infoCarga[] = array('tabla' => 'operadorfinanciero', 'registros' => $cont);
        }
    }

    /*
     * De carga inicial.
     */

    private function loadToEmisores($registros = null, &$em, &$infoCarga) {
        $emisor = null;
        $cont = 0;
        if ($registros && count($registros) > 0) {
            $this->valid_fields($registros[0], array('nombre', 'action', 'nuevo'));
            foreach ($registros as $registro) {
                $emisor = $em->getRepository('YDIBackendBundle:Emisor')->findOneBy(array(
                    'nombre' => $registro['nombre']
                ));
                $cont++;
                if ($registro['action'] == 'C') {
                    if (!$emisor) {
                        $nombre = $registro['nombre'];
                        throw new \Exception("$cont.- El Emisor con nombre: $nombre, no existe. No es posible hacer cambios en el registro.");
                        break;
                    }
                    if (strlen(trim($registro['nuevo']))) {
                        $registro['nombre'] = $registro['nuevo'];
                    }
                } else if ($registro['action'] == 'A') {
                    if ($emisor) {
                        $nombre = $registro['nombre'];
                        throw new \Exception("$cont.- El Emisor con nombre: $nombre, ya existe. No es posible realizar la alta nuevamente.");
                        break;
                    }
                    $emisor = new \YDI\BackendBundle\Entity\Emisor();
                } else {
                    throw new \Exception("$cont.- Solo es posible Agregar o Cambiar Emisores ");
                }
                $emisor->setNombre($registro['nombre']);
                $em->persist($emisor);
                $em->flush();
            }
            $infoCarga[] = array('tabla' => 'emisor', 'registros' => $cont);
        }
    }

    /*
     * De carga inicial.
     */

    private function loadToTiposTarjeta($registros = null, &$em, &$infoCarga) {
        $tipoTarjeta = null;
        $cont = 0;
        if ($registros && count($registros) > 0) {
            $this->valid_fields($registros[0], array('nombre', 'action', 'nuevo'));
            foreach ($registros as $registro) {
                $tipoTarjeta = $em->getRepository('YDIBackendBundle:TiposTarjeta')->findOneBy(array(
                    'nombre' => $registro['nombre']
                ));
                $cont++;
                if ($registro['action'] == 'C') {
                    if (!$tipoTarjeta) {
                        $nombre = $registro['nombre'];
                        throw new \Exception("$cont.- El Tipo Tarjeta con nombre: $nombre, no existe. No es posible hacer cambios en el registro.");
                        break;
                    }
                    if (strlen(trim($registro['nuevo']))) {
                        $registro['nombre'] = $registro['nuevo'];
                    }
                } else if ($registro['action'] == 'A') {
                    if ($tipoTarjeta) {
                        $nombre = $registro['nombre'];
                        throw new \Exception("$cont.- El Tipo Tarjeta con nombre: $nombre, ya existe. No es posible realizar la alta nuevamente.");
                        break;
                    }
                    $tipoTarjeta = new \YDI\BackendBundle\Entity\TiposTarjeta();
                } else {
                    throw new \Exception("$cont.- Solo es posible Agregar o Cambiar Tipos Tarjeta ");
                }
                $tipoTarjeta->setNombre($registro['nombre']);
                $em->persist($tipoTarjeta);
                $em->flush();
            }
            $infoCarga[] = array('tabla' => 'tipostarjeta', 'registros' => $cont);
        }
    }

    /*
     * De carga inicial.
     */

    private function loadToTarjetaEmisorOperador($registros = null, &$em, &$infoCarga) {
        $operadorFinanciero = null;
        $emisor = null;
        $tipoTarjeta = null;
        $cont = 0;
        $nuevo = false;
        if ($registros && count($registros) > 0) {
            $this->valid_fields($registros[0], array('operadorfinanciero', 'emisor', 'tipostarjeta',
                'action', 'url_hd', 'url_ld'));
            foreach ($registros as $registro) {
                $this->getOperadorFinanciero($registro['operadorfinanciero'], $operadorFinanciero, $em);
                $this->getEmisor($registro['emisor'], $emisor, $em);
                $this->getTipoTarjeta($registro['tipostarjeta'], $tipoTarjeta, $em);
                $teo = $em->getRepository('YDIBackendBundle:TarjetaEmisorOperador')
                        ->findOneBy(array(
                    'tiposTarjeta' => $tipoTarjeta->getId(),
                    'emisor' => $emisor->getId(),
                    'operadorFinanciero' => $operadorFinanciero->getId()
                ));
                $cont++;
                if ($registro['action'] == 'C') {
                    $nuevo = false;
                    if (!$teo) {
                        $tt = $tipoTarjeta->getNombre();
                        $e = $emisor->getNombre();
                        $o = $operadorFinanciero->getNombre();
                        throw new \Exception("$cont.- La combinacion $tt-$e-$o no existe, no es posible actualizar el registro.");
                        break;
                    }
                } else if ($registro['action'] == 'A') { // por default $registro['action'] == 'A'
                    $nuevo = true;
                    if (!$teo) {
                        $teo = new \YDI\BackendBundle\Entity\TarjetaEmisorOperador();
                        $teo->setOperadorFinanciero($operadorFinanciero);
                        $teo->setEmisor($emisor);
                        $teo->setTiposTarjeta($tipoTarjeta);
                    } else {
                        $tt = $tipoTarjeta->getNombre();
                        $e = $emisor->getNombre();
                        $o = $operadorFinanciero->getNombre();
                        throw new \Exception("$cont.- La combinacion $tt-$e-$o ya existe, no es posible darlo de alta nuevamente.");
                        break;
                    }
                } else {
                    throw new \Exception("$cont.- Solo es posible Agregar o Cambiar TipoTarjeta-Emisor-OperadorFinanciero");
                }
                $teo->setFechaActualizacion(new \DateTime());
                $teo->setUrlHd($registro['url_hd']);
                $teo->setUrlLd($registro['url_ld']);
                if ($nuevo)
                    $em->persist($teo);
                else
                    $em->flush();
            }
            $infoCarga[] = array('tabla' => 'tarjeta_emisoroperador', 'registros' => $cont);
        }
    }

    /*
     * De carga inicial.
     */

    private function loadToPreferencias($registros = null, &$em, &$infoCarga) {
        $preferencia = null;
        $cont = 0;
        if ($registros && count($registros) > 0) {
            $this->valid_fields($registros[0], array('nombre', 'action', 'nuevo'));
            foreach ($registros as $registro) {
                $preferencia = $em->getRepository('YDIBackendBundle:Preferencias')->findOneBy(array(
                    'nombre' => $registro['nombre']
                ));
                $cont++;
                if ($registro['action'] == 'C') {
                    if (!$preferencia) {
                        $nombre = $registro['nombre'];
                        throw new \Exception("$cont.- La preferencia con nombre: $nombre, no existe. No es posible hacer cambios en el registro.");
                        break;
                    }
                    if (strlen(trim($registro['nuevo']))) {
                        $registro['nombre'] = $registro['nuevo'];
                    }
                } else if ($registro['action'] == 'A') {
                    if ($preferencia) {
                        $nombre = $registro['nombre'];
                        throw new \Exception("$cont.- La preferencia con nombre: $nombre, ya existe. No es posible realizar la alta nuevamente.");
                        break;
                    }
                    $preferencia = new \YDI\BackendBundle\Entity\Preferencias();
                } else {
                    throw new \Exception("$cont.- Solo es posible Agregar o Cambiar Preferencias ");
                }
                $preferencia->setNombre($registro['nombre']);
                $em->persist($preferencia);
                $em->flush();
            }
            $infoCarga[] = array('tabla' => 'preferencias', 'registros' => $cont);
        }
    }

    /*
     * De cargar inicial.
     */

    private function loadToSubnivelPreferencias($registros = null, &$em, &$infoCarga) {
        $preferencia = null;
        $subnivel = null;
        $cont = 0;
        if ($registros && count($registros) > 0) {
            foreach ($registros as $registro) {
				$this->getPreferencia($registro['preferencias'],$preferencia,$em);
                $this->getSubnivelPreferencia($registro['preferencias'], $preferencia, $subnivel, $em);
                $cont++;
            }
            $infoCarga[] = array('tabla' => 'subnivel_preferencia', 'registros' => $cont);
        }
    }

    /*
     * De carga inicial.
     */

    private function loadToEventos($registros = null, &$em, &$infoCarga) {
        $evento = null;
        $cont = 0;
        if ($registros && count($registros) > 0) {
            $this->valid_fields($registros[0], array('nombre', 'action', 'nuevo'));
            foreach ($registros as $registro) {
                $evento = $em->getRepository('YDIBackendBundle:Evento')->findOneBy(array(
                    'nombre' => $registro['nombre']
                ));
                $cont++;
                if ($registro['action'] == 'C') {
                    if (!$evento) {
                        $nombre = $registro['nombre'];
                        throw new \Exception("$cont.- El evento con nombre: $nombre, no existe. No es posible hacer cambios en el registro.");
                        break;
                    }
                    if (strlen(trim($registro['nuevo']))) {
                        $registro['nombre'] = $registro['nuevo'];
                    }
                } else if ($registro['action'] == 'A') {
                    if ($evento) {
                        $nombre = $registro['nombre'];
                        throw new \Exception("$cont.- El evento con nombre: $nombre, ya existe. No es posible realizar la alta nuevamente.");
                        break;
                    }
                    $evento = new \YDI\BackendBundle\Entity\Evento();
                } else {
                    throw new \Exception("$cont.- Solo es posible Agregar o Cambiar Eventos ");
                }
                $evento->setNombre($registro['nombre']);
                $em->persist($evento);
                $em->flush();
            }
            $infoCarga[] = array('tabla' => 'evento', 'registros' => $cont);
        }
    }

    /*
     * De carga inicial.
     */

    private function loadToVistas($registros = null, &$em, &$infoCarga) {
        $vista = null;
        $cont = 0;
        if ($registros && count($registros) > 0) {
            $this->valid_fields($registros[0], array('nombre', 'action', 'nuevo'));
            foreach ($registros as $registro) {
                $vista = $em->getRepository('YDIBackendBundle:Vista')->findOneBy(array(
                    'nombre' => $registro['nombre']
                ));
                $cont++;
                if ($registro['action'] == 'C') {
                    if (!$vista) {
                        $nombre = $registro['nombre'];
                        throw new \Exception("$cont.- La vista con nombre: $nombre, no existe. No es posible hacer cambios en el registro.");
                        break;
                    }
                    if (strlen(trim($registro['nuevo']))) {
                        $registro['nombre'] = $registro['nuevo'];
                    }
                } else if ($registro['action'] == 'A') {
                    if ($vista) {
                        $nombre = $registro['nombre'];
                        throw new \Exception("$cont.- La vista con nombre: $nombre, ya existe. No es posible realizar la alta nuevamente.");
                        break;
                    }
                    $vista = new \YDI\BackendBundle\Entity\Vista();
                } else {
                    throw new \Exception("$cont.- Solo es posible Agregar o Cambiar Vistas ");
                }
                $vista->setNombre($registro['nombre']);
                $em->persist($vista);
                $em->flush();
            }
            $infoCarga[] = array('tabla' => 'vista', 'registros' => $cont);
        }
    }

    /*
     * De carga updates.
     */

    private function loadToGrupoEstablecimiento($registros = null, &$em, &$infoCarga) {
        $grupo = null;
        $cont = 0;
        if ($registros && count($registros) > 0) {
            $this->valid_fields($registros[0], array('clavegrupo', 'nombre',
                'action', 'nuevo'));
            foreach ($registros as $registro) {
                $grupo = $em->getRepository('YDIBackendBundle:GrupoEstablecimiento')
                        ->findOneBy(array('clavegrupo' => $registro['clavegrupo']));
                $cont++;
                if ($registro['action'] == 'C') {
                    if ($grupo) {
                        if (strlen(trim($registro['nuevo'])) > 0) {
                            $registro['clavegrupo'] = $registro['nuevo'];
                        }
                    } else {
                        $clavegrupo = $registro['clavegrupo'];
                        throw new \Exception("$cont.- El grupo con clave $clavegrupo no existe, no es posible hacer la actualizacion solicitada.");
                    }
                } else if ($registro['action'] == 'B') {
                    if ($grupo) {
                        $grupo->setInactivo(true);
                        $em->flush();
                        continue;
                    } else {
                        $clavegrupo = $registro['clavegrupo'];
                        throw new \Exception("$cont.- El grupo con clave $clavegrupo no existe, no es posible eliminar el grupo.");
                    }
                } else { // default $registro['action']=='A'
                    if (!$grupo) {
                        $grupo = new \YDI\BackendBundle\Entity\GrupoEstablecimiento();
                    } else {
                        if ($grupo->getInactivo()) {
                            $grupo->setInactivo(false);
                            $em->flush();
                            continue;
                        } else {
                            $clavegrupo = $registro['clavegrupo'];
                            throw new \Exception("$cont.- El grupo con clave $clavegrupo ya existe, no es posible darlo de alta nuevamente.");
                        }
                    }
                }
                $grupo->setClavegrupo($registro['clavegrupo']);
                $grupo->setNombre($registro['nombre']);
                if (!$grupo->getId())
                    $em->persist($grupo);
                else
                    $em->flush();
            }
            $infoCarga[] = array('tabla' => 'grupo_establecimiento', 'registros' => $cont);
        }
    }

    private function loadToNoticias($registros = null, &$em, &$infoCarga) {
        $grupo = null;
        $cont = 0;
        $nuevo = false;
        if ($registros && count($registros) > 0) {
            $this->valid_fields($registros[0], array('id', 'descripcion', 'url_hd',
                'action', 'url_ld', 'fecha_hora_inicio', 'fecha_hora_terminacion'));
            foreach ($registros as $registro) {
                $noticia = $em->getRepository('YDIBackendBundle:Noticia')
                        ->find($registro['id']);
                $cont++;
                if ($registro['action'] == 'C') {
                    $nuevo = false;
                    if (!$noticia) {
                        $id = $registro['id'];
                        throw new \Exception("$cont.- La noticia con ID $id no existe, no es posible hacer la actualizacion solicitada.");
                    }
                } else if ($registro['action'] == 'B') {
                    $nuevo = false;
                    if ($noticia) {
                        $em->remove($noticia);
                        $em->flush();
                        continue;
                    } else {
                        $id = $registro['id'];
                        throw new \Exception("$cont.- La noticia con ID $id no existe, no es posible eliminar el grupo.");
                    }
                } else { // default $registro['action']=='A'
                    $nuevo = true;
                    if (!$noticia) {
                        $noticia = new \YDI\BackendBundle\Entity\Noticia();
                    } else {
                        $id = $registro['id'];
                        throw new \Exception("$cont.- La noticia con ID $id ya existe, no es posible darlo de alta nuevamente.");
                    }
                }
                $noticia->setId($registro['id']);
                $noticia->setDescripcion($registro['descripcion']);
                $noticia->setUrlHd($registro['url_hd']);
                $noticia->setUrlLd($registro['url_ld']);
                $noticia->setFechaHoraInicio(new \DateTime($registro['fecha_hora_inicio']));
                $noticia->setFechaHoraTerminacion(new \DateTime($registro['fecha_hora_terminacion']));
                $noticia->setFechaActualizacion(new \DateTime());
                if ($nuevo)
                    $em->persist($noticia);
                else
                    $em->flush();
            }
            $infoCarga[] = array('tabla' => 'noticias', 'registros' => $cont);
        }
    }

    /*
     * De carga updates.
     */

    private function loadToEstablecimientos($registros = null, &$em, &$infoCarga) {
        $cont = 0;
        $establecimiento = null;
        $grupo = null;
        if ($registros && count($registros) > 0) {
            $this->valid_fields($registros[0], array('clavegrupo', 'nombre_establecimiento',
                'action', 'nuevo', 'url_hd', 'url_ld', 'tipo_logo', 'nombre_pantalla'));
            foreach ($registros as $registro) {
                $establecimiento = $em->getRepository('YDIBackendBundle:Establecimiento')
                        ->findOneBy(array('nombre' => $registro['nombre_establecimiento']));
                $cont++;
                if ($registro['action'] == 'C') {
                    if ($establecimiento) {
                        if (strlen(trim($registro['nuevo'])) > 0) {
                            $registro['nombre_establecimiento'] = $registro['nuevo'];
                        }
                    } else {
                        $nombreEstablecimiento = $registro['nombre_establecimiento'];
                        throw new \Exception("$cont.- El establecimiento con nombre: $nombreEstablecimiento, no existe. Se necesita crear el registro para despues actualizarlo.");
                    }
                } else if ($registro['action'] == 'A') { // default $registro['action']=='A'
                    if (!$establecimiento) {
                        $establecimiento = new \YDI\BackendBundle\Entity\Establecimiento();
                    } else {
                        $nombreEstablecimiento = $registro['nombre_establecimiento'];
                        throw new \Exception("$cont.- El establecimiento con nombre: $nombreEstablecimiento, ya existe. No se puede crear un nuevo registro.");
                    }
                } else {
                    throw new \Exception("$cont.- Solo es posible Agregar o Cambiar Establecimientos ");
                }
                $establecimiento->setFechaActualizacion(new \DateTime());
                $establecimiento->setNombre($registro['nombre_establecimiento']);
                $establecimiento->setClavegrupo($registro['clavegrupo']);
                $this->getGrupoEstablecimiento($registro['clavegrupo'], $grupo, $em);
                $establecimiento->setTipoLogo($registro['tipo_logo']);
                $establecimiento->setUrlHd($registro['url_hd']);
                $establecimiento->setUrlLd($registro['url_ld']);
                $establecimiento->setNombrePantalla($registro['nombre_pantalla']);
                $establecimiento->setGrupoEstablecimiento($grupo);
                if (!$establecimiento->getId())
                    $em->persist($establecimiento);
                else
                    $em->flush();
            }
            $infoCarga[] = array('tabla' => 'establecimiento', 'registros' => $cont);
        }
    }

    /*
     * De carga updates.
     */

    private function loadToGooglePlaces($registros = null, &$em, &$infoCarga) {
        $establecimiento = null;
        $cont = 0;
        $nuevo = false;
        if ($registros && count($registros) > 0) {
            $this->valid_fields($registros[0], array('nombre_establecimiento',
                'action', 'nuevo_id_places', 'id_places', 'latitud', 'longitud'));
            foreach ($registros as $registro) {
                $this->getEstablecimientoForName($registro['nombre_establecimiento'], $establecimiento, $em);
                $googlePlace = $em->getRepository('YDIBackendBundle:GooglePlaces')->findOneBy(array(
                    'places' => $registro['id_places'],
                    'establecimiento' => $establecimiento->getId()));
                $cont++;
                if ($registro['action'] == 'C') {
                    $nuevo = false;
                    if ($googlePlace) {
                        if (strlen(trim($registro['nuevo_id_places'])) > 0) {
                            $registro['id_places'] = $registro['nuevo_id_places'];
                        }
                    } else {
                        $nombreEstablecimiento = $registro['nombre_establecimiento'];
                        $idPlaces = $registro['id_places'];
                        throw new \Exception("$cont.- El IdPlaces:$idPlaces con nombre: $nombreEstablecimiento, no existe. No se puede actualizar el registro.");
                    }
                    break;
                } else if ($registro['action'] == 'A') { // default $registro['action']=='A'
                    $nuevo = true;
                    if (!$googlePlace) {
                        $googlePlace = new \YDI\BackendBundle\Entity\GooglePlaces();
                    } else {
                        $nombreEstablecimiento = $registro['nombre_establecimiento'];
                        $idPlaces = $registro['id_places'];
                        throw new \Exception("$cont.- El IdPlaces:$idPlaces con nombre: $nombreEstablecimiento, ya existe. No se puede crear el registro nuevamente.");
                    }
                } else {
                    throw new \Exception("$cont.- Solo es posible Agregar o Cambiar Google Places de Establecimientos ");
                }
                $googlePlace->setPlaces($registro['id_places']);
                $googlePlace->setEstablecimiento($establecimiento);
                $googlePlace->setLatitud($registro['latitud']);
                $googlePlace->setLongitud($registro['longitud']);
                $establecimiento->setFechaActualizacion(new \DateTime());
                if ($nuevo) $em->persist($googlePlace);
                $em->flush();
            }
            $infoCarga[] = array('tabla' => 'google_places', 'registros' => $cont);
        }
    }

    /*
     * De carga updates.
     */

    private function loadToSocios($registros = null, &$em, &$infoCarga) {
        $establecimiento = null;
        $cont = 0;
        if ($registros && count($registros) > 0) {
            $this->valid_fields($registros[0], array('nombre_establecimiento',
                'action', 'fechaalta'));
            foreach ($registros as $registro) {
                $this->getEstablecimientoForName($registro['nombre_establecimiento'], $establecimiento, $em);
                $socio = $em->getRepository('YDIBackendBundle:Socios')
                        ->findOneBy(array('establecimiento' => $establecimiento->getId()));
                $cont++;
                if ($registro['action'] == 'C') {
                    if ($socio) {
                        $socio->setFechaAlta(new \DateTime($registro['fechaalta']));
                        $socio->setFechaActualizacion(new \DateTime());
                        $establecimiento->setFechaActualizacion(new \DateTime());
                        $em->flush();
                    } else {
                        $nombreEstablecimiento = $registro['nombre_establecimiento'];
                        throw new \Exception("$cont.- El socio con nombre de establecimiento: $nombreEstablecimiento, no existe. No se puede actualizar el registro.");
                    }
                } else if ($registro['action'] == 'B') {
                    if ($socio) {
                        $em->remove($socio);
                        $establecimiento->setFechaActualizacion(new \DateTime());
                        $em->flush();
                    } else {
                        $nombreEstablecimiento = $registro['nombre_establecimiento'];
                        throw new \Exception("$cont.- El socio con nombre de establecimiento: $nombreEstablecimiento, no existe. No se puede eliminar el registro.");
                    }
                } else { // default $registro['action']=='A'
                    if (!$socio) {
                        $socio = new \YDI\BackendBundle\Entity\Socios();
                        $socio->setFechaAlta(new \DateTime($registro['fechaalta']));
                        $socio->setEstablecimiento($establecimiento);
                        //$socio->setFechaActualizacion(new \DateTime()); descartada
                        $em->persist($socio);
                        $establecimiento->setFechaActualizacion(new \DateTime());
                        $em->flush();
                    } else {
                        $nombreEstablecimiento = $registro['nombre_establecimiento'];
                        throw new \Exception("$cont.- El socio con nombre de establecimiento: $nombreEstablecimiento, ya existe. No se puede crear un nuevo registro nuevamente.");
                    }
                }
            }
            $infoCarga[] = array('tabla' => 'socios', 'registros' => $cont);
        }
    }

    /*
     * De carga updates.
     */

    private function loadToPremios($registros = null, &$em, &$infoCarga) {
        $establecimiento = null;
        $cont = 0;
        if ($registros && count($registros) > 0) {
            $this->valid_fields($registros[0], array('nombre_establecimiento',
                'action', 'descripcion', 'valorpuntos', 'nuevo_descripcion'));
            foreach ($registros as $registro) {
                $this->getEstablecimientoForName($registro['nombre_establecimiento'], $establecimiento, $em);
                $premio = $em->getRepository('YDIBackendBundle:Premios')->findOneBy(array(
                    'establecimiento' => $establecimiento->getId(),
                    'descripcion' => $registro['descripcion']
                ));
                $cont++;
                if ($registro['action'] == 'C') {
                    if ($premio) {
                        if (strlen(trim($registro['nuevo_descripcion'])) > 0) {
                            $registro['descripcion'] = $registro['nuevo_descripcion'];
                        }
                    } else {
                        $nombreEstablecimiento = $registro['nombre_establecimiento'];
                        $descripcion = $registro['descripcion'];
                        throw new \Exception("$cont.- El premio: $descripcion con nombre de establecimiento: $nombreEstablecimiento, no existe. No se puede actualizar el registro.");
                    }
                } else if ($registro['action'] == 'B') {
                    if ($premio) {
                        $em->remove($premio);
                        $establecimiento->setFechaActualizacion(new \DateTime());
                        $em->flush();
                        continue;
                    } else {
                        $nombreEstablecimiento = $registro['nombre_establecimiento'];
                        $descripcion = $registro['descripcion'];
                        throw new \Exception("$cont.- El premio: $descripcion con nombre de establecimiento: $nombreEstablecimiento, no existe. No se puede eliminar el registro.");
                    }
                } else { // default $registro['action']=='A'
                    if (!$premio) {
                        $premio = new \YDI\BackendBundle\Entity\Premios();
                    } else {
                        $nombreEstablecimiento = $registro['nombre_establecimiento'];
                        $descripcion = $registro['descripcion'];
                        throw new \Exception("$cont.- El premio: $descripcion con nombre de establecimiento: $nombreEstablecimiento, ya existe. No se puede crear un nuevo registro nuevamente.");
                    }
                }
                $premio->setDescripcion($registro['descripcion']);
                $premio->setValorpuntos($registro['valorpuntos']);
                $premio->setNumeroPremio($registro['numeropremio']);
                $premio->setEstablecimiento($establecimiento);
                $establecimiento->setFechaActualizacion(new \DateTime());
                if (!$premio->getId()) $em->persist($premio);
                $em->flush();
            }
            $infoCarga[] = array('tabla' => 'premios', 'registros' => $cont);
        }
    }

    /*
     * De carga updates.
     */

    private function loadToTarjetas($registros = null, &$em, &$infoCarga) {
        $usuario = null;
        $tiposTarjeta = null;
        $operadorFinanciero = null;
        $emisor = null;
        $tarjeta = null;
        $cont = 0;
        $nuevo = false;
        if ($registros && count($registros) > 0) {
            $this->valid_fields($registros[0], array('action', 'id_usuario', 'tipostarjeta',
                'operadorfinanciero', 'emisor', 'ultimos_digitos', 'fecha_vencimiento_anio', 'fecha_vencimiento_mes',
                'nuevo_ultimos_digitos', 'nuevo_fecha_vencimiento_anio', 'nuevo_fecha_vencimiento_mes',
                'nuevo_tipostarjeta', 'nuevo_emisor', 'nuevo_operadorfinanciero'));
            foreach ($registros as $registro) {
                $this->getUsuario($registro['id_usuario'], $usuario, $em);
                $this->getTipoTarjeta($registro['tipostarjeta'], $tiposTarjeta, $em);
                $this->getOperadorFinanciero($registro['operadorfinanciero'], $operadorFinanciero, $em);
                $this->getEmisor($registro['emisor'], $emisor, $em);
                $teo = $em->getRepository('YDIBackendBundle:TarjetaEmisorOperador')->findOneBy(array(
                    'tiposTarjeta' => $tiposTarjeta->getId(),
                    'emisor' => $emisor->getId(),
                    'operadorFinanciero' => $operadorFinanciero->getId()
                ));
                if (!$teo) {
                    throw new \Exception('La combinacion de TiposTarjeta-Emisor-OperadorFinanciero : ' .
                    $tiposTarjeta->getNombre() .
                    '-' . $emisor->getNombre() .
                    '-' . $operadorFinanciero->getNombre() .
                    ', no existe en la tabla tarjeta_emisor_operador'
                    );
                }
                $cont++;
                $fecha = $registro['fecha_vencimiento_anio'] . "-" . (abs($registro['fecha_vencimiento_mes']) > 9 ? $registro['fecha_vencimiento_mes'] : "0" . $registro['fecha_vencimiento_mes']) . "-01";
                $tarjeta = $em->getRepository('YDIBackendBundle:Tarjeta')->findOneBy(array(
                    'tiposTarjeta' => $tiposTarjeta->getId(),
                    'emisor' => $emisor->getId(),
                    'operadorFinanciero' => $operadorFinanciero->getId(),
                    'usuario' => $usuario->getId(),
                    'ultimosDigitos' => $registro['ultimos_digitos'],
                    'fechaVencimiento' => new \DateTime($fecha)
                ));
                if ($registro['action'] == 'C') {
                    $nuevo = false;
                    if ($tarjeta) {
                        if (strlen(trim($registro['nuevo_ultimos_digitos']))) {
                            $registro['ultimos_digitos'] = $registro['nuevo_ultimos_digitos'];
                        }
                        if (strlen(trim($registro['nuevo_fecha_vencimiento_anio']))) {
                            $registro['fecha_vencimiento_anio'] = $registro['nuevo_fecha_vencimiento_anio'];
                        }
                        if (strlen(trim($registro['nuevo_fecha_vencimiento_mes']))) {
                            $registro['fecha_vencimiento_mes'] = $registro['nuevo_fecha_vencimiento_mes'];
                        }
                        $cambio_tt_of_e = false;
                        if (strlen(trim($registro['nuevo_tipostarjeta']))) {
                            $registro['tipostarjeta'] = $registro['nuevo_tipostarjeta'];
                            $cambio_tt_of_e = true;
                        }
                        if (strlen(trim($registro['nuevo_emisor']))) {
                            $registro['emisor'] = $registro['nuevo_emisor'];
                            $cambio_tt_of_e = true;
                        }
                        if (strlen(trim($registro['nuevo_operadorfinanciero']))) {
                            $registro['operadorfinanciero'] = $registro['nuevo_operadorfinanciero'];
                            $cambio_tt_of_e = true;
                        }

                        $fecha = $registro['fecha_vencimiento_anio'] . "-" . (abs($registro['fecha_vencimiento_mes']) > 9 ? $registro['fecha_vencimiento_mes'] : "0" . $registro['fecha_vencimiento_mes']) . "-01";

                        if ($cambio_tt_of_e) {
                            $this->getTipoTarjeta($registro['tipostarjeta'], $tiposTarjeta, $em);
                            $this->getOperadorFinanciero($registro['operadorfinanciero'], $operadorFinanciero, $em);
                            $this->getEmisor($registro['emisor'], $emisor, $em);
                            $teo = $em->getRepository('YDIBackendBundle:TarjetaEmisorOperador')->findOneBy(array(
                                'tiposTarjeta' => $tiposTarjeta->getId(),
                                'emisor' => $emisor->getId(),
                                'operadorFinanciero' => $operadorFinanciero->getId()
                            ));
                            if (!$teo) {
                                throw new \Exception('La Nueva Combinacion de TiposTarjeta-Emisor-OperadorFinanciero : ' .
                                $tiposTarjeta->getNombre() .
                                '-' . $emisor->getNombre() .
                                '-' . $operadorFinanciero->getNombre() .
                                ', no existe en la tabla tarjeta_emisor_operador'
                                );
                            }
                        }
                    } else {
                        $digitos = $registro['ultimos_digitos'];
                        throw new \Exception("$cont.- La tarjeta con ultimos digitos: $digitos, no existe. No se puede actualizar el registro.");
                    }
                } else if ($registro['action'] == 'B') {
                    $nuevo = false;
                    if ($tarjeta) {
                        $em->remove($tarjeta);
                        continue;
                    } else {
                        $digitos = $registro['ultimos_digitos'];
                        throw new \Exception("$cont.- La tarjeta con ultimos digitos: $digitos, no existe. No se puede eliminar el registro.");
                    }
                } else { // default $registro['action']=='A'
                    $nuevo = true;
                    if (!$tarjeta) {
                        $tarjeta = new \YDI\BackendBundle\Entity\Tarjeta();
                    } else {
                        $digitos = $registro['ultimos_digitos'];
                        throw new \Exception("$cont.- La tarjeta con ultimos digitos: $digitos, ya existe. No se puede crear un nuevo registro nuevamente.");
                    }
                }
                $tarjeta->setUsuario($usuario);
                $tarjeta->setEmisor($emisor);
                $tarjeta->setTiposTarjeta($tiposTarjeta);
                $tarjeta->setOperadorFinanciero($operadorFinanciero);
                $tarjeta->setUltimosDigitos($registro['ultimos_digitos']);
                $tarjeta->setFechaVencimiento(new \DateTime($fecha));
                if ($nuevo)
                    $em->persist($tarjeta);
                else
                    $em->flush();
            }
            $infoCarga[] = array('tabla' => 'tarjeta', 'registros' => $cont);
        }
    }

    /*
     * De carga updates.
     */

    private function loadToAnuncios($registros = null, &$em, &$infoCarga) {
        $establecimiento = null;
        $anuncio = null;
        $cont = 0;
        $nuevo = false;
        if ($registros && count($registros) > 0) {
            $this->valid_fields($registros[0], array('action', 'id', 'nombre_establecimiento',
                'fecha_hora_inicio', 'fecha_hora_terminacion', 'url_hd', 'url_ld', 'anuncio_rapido_flag',
                'tamanio_flag', 'valor_puntos', 'leyendacupon', 'clavecupon', 'usoscupon', 'imagen_pantalla_1'));
            foreach ($registros as $registro) {
                $this->getEstablecimientoForName($registro['nombre_establecimiento'], $establecimiento, $em);
                $anuncio = $em->getRepository('YDIBackendBundle:Anuncio')->find($registro['id']);
                $cont++;
                if ($registro['action'] == 'B') {
                    $nuevo = false;
                    if ($anuncio) {
                        $anuncio->setRegistroborrado(1);
                        $em->remove($anuncio);
                        continue;
                    } else {
                        $id = $registro['id'];
                        throw new \Exception("$cont.- El registro de anuncio con ID: $id, no existe. No se puede eliminar el registro.");
                    }
                } else if ($registro['action'] == 'C') {
                    $nuevo = false;
                    if ($anuncio) {
                        $anuncio->setRegistroborrado(0);
                    } else {
                        $id = $registro['id'];
                        throw new \Exception("$cont.- El registro de anuncio con ID: $id, no existe. No se puede actualizar el registro.");
                    }
                } else { // default $registro['action']=='A'
                    $nuevo = true;
                    if (!$anuncio) {
                        $anuncio = new \YDI\BackendBundle\Entity\Anuncio();
                        $anuncio->setId($registro['id']);
                        $anuncio->setRegistroborrado(0);
                    } else {
                        $id = $registro['id'];
                        throw new \Exception("$cont.- El registro de anuncio con ID: $id, ya existe. No se puede crear un nuevo registro nuevamente.");
                    }
                }
                $anuncio->setFechaHoraInicio(new \DateTime($registro['fecha_hora_inicio']));
                $anuncio->setFechaHoraTerminacion(new \DateTime($registro['fecha_hora_terminacion']));
                $anuncio->setUrlLd($registro['url_ld']);
                $anuncio->setUrlHd($registro['url_hd']);
                $anuncio->setAnuncioRapidoFlag($registro['anuncio_rapido_flag']);
                $anuncio->setTamanioFlag($registro['tamanio_flag']);
                $anuncio->setValorPuntos($registro['valor_puntos']);
                $anuncio->setValorPuntosRechazo($registro['valor_puntos_rechazo']);
                $anuncio->setLeyendacupon($registro['leyendacupon']);
                $anuncio->setNumerocupon($registro['clavecupon']);
                $anuncio->setNumerousoscupon($registro['usoscupon']);
                $anuncio->setFechaCarga(new \DateTime());
                $anuncio->setEstablecimiento($establecimiento);
                $anuncio->setImagenPantalla($registro['imagen_pantalla_1']);
                if ($nuevo)
                    $em->persist($anuncio);
                else
                    $em->flush();
            }
            $infoCarga[] = array('tabla' => 'anuncio', 'registros' => $cont);
        }
    }

    /*
     * De carga updates.
     */

    private function loadToTarjetaEmisorOperadorAnuncio($registros = null, &$em, &$infoCarga) {
        $anuncio = null;
        $tiposTarjeta = null;
        $operadorFinanciero = null;
        $emisor = null;
        $arregloAnuncios = [];
        $cont = 0;
        if ($registros && count($registros) > 0) {
            $this->valid_fields($registros[0], array('action', 'id_anuncio',
                'tipostarjeta', 'operadorfinanciero', 'emisor'));
            foreach ($registros as $registro) {
                $this->getAnuncio($registro['id_anuncio'], $anuncio, $em);
                $this->getTipoTarjeta($registro['tipostarjeta'], $tiposTarjeta, $em);
                $this->getOperadorFinanciero($registro['operadorfinanciero'], $operadorFinanciero, $em);
                $this->getEmisor($registro['emisor'], $emisor, $em);
                $tarjetaEmisorOperador = $em->getRepository('YDIBackendBundle:TarjetaEmisorOperador')->findOneBy(array(
                    'tiposTarjeta' => $tiposTarjeta->getId(),
                    'emisor' => $emisor->getId(),
                    'operadorFinanciero' => $operadorFinanciero->getId()
                ));
                $cont++;
                if (!$tarjetaEmisorOperador) {
                    throw new \Exception("$cont.- La combinacion de TiposTarjeta-Emisor-OperadorFinanciero : " .
                    $tiposTarjeta->getNombre() .
                    "-" . $emisor->getNombre() .
                    "-" . $operadorFinanciero->getNombre() .
                    ", no existe en la tabla tarjeta_emisor_operador"
                    );
                }
                if (!$anuncio) {
                    $idAnuncio = $registro['id_anuncio'];
                    throw new \Exception("$cont.- No existe el anuncio con ID: $idAnuncio. No es posible relacionar.");
                }
                if ($registro['action'] == 'A') {
                    if (!$anuncio->getTiposTarjeta()->contains($tarjetaEmisorOperador)) {
                        $anuncio->addTiposTarjetum($tarjetaEmisorOperador);
                        $em->persist($anuncio);
                        $em->flush();
                    }
                } else if ($registro['action'] == 'B') {
                    if ($anuncio->getTiposTarjeta()->contains($tarjetaEmisorOperador)) {
                        $anuncio->removeTiposTarjetum($tarjetaEmisorOperador);
                        $em->persist($anuncio);
                        $em->flush();
                    }
                } else {
                    throw new \Exception("$cont.- Solo es posible Agregar o Baja la referencia de anuncio y TipoTarjeta-Emisor-OperadorFinanciero");
                }
            }
            $infoCarga[] = array('tabla' => 'anuncio_tarjeta', 'registros' => $cont);
        }
    }

    /*
     * De carga updates.
     */

    private function loadToPreferenciasAnuncio($registros = null, &$em, &$infoCarga) {
        $anuncio = null;
        $preferencia = null;
        $subnivel = null;
        $cont = 0;
        if ($registros && count($registros) > 0) {
            $this->valid_fields($registros[0], array('action', 'id_anuncio',
                'preferencia'));
            foreach ($registros as $registro) {
                $this->getAnuncio($registro['id_anuncio'], $anuncio, $em);
                $this->getPreferencia($registro['preferencia'], $preferencia, $em);
                $this->getSubnivelPreferencia($registro['preferencia'], $preferencia, $subnivel, $em);
                $cont++;
                if (!$anuncio) {
                    $idAnuncio = $registro['id_anuncio'];
                    throw new \Exception("$cont.- No existe el anuncio con ID: $idAnuncio. No es posible relacionar.");
                }
                if ($registro['action'] == 'A') {
                    if (!$subnivel->getAnuncio()->contains($anuncio)) {
                        $subnivel->addAnuncio($anuncio);
                        $em->persist($subnivel);
                        $em->flush();
                    }
                } else if ($registro['action'] == 'B') {
                    if ($subnivel->getAnuncio()->contains($anuncio)) {
                        $subnivel->removeAnuncio($anuncio);
                        $em->persist($subnivel);
                        $em->flush();
                    }
                } else {
                    throw new \Exception("$cont.- Solo es posible Agregar o Baja la referencia de anuncio y preferencia");
                }
            }
            $infoCarga[] = array('tabla' => 'preferencias_anuncio', 'registros' => $cont);
        }
    }

    /*
     * De carga updates.
     */

    private function loadToCondiciones($registros = null, &$em, &$infoCarga) {
        $anuncio = null;
        $condicion = null;
        $cont = 0;
        if ($registros && count($registros) > 0) {
            $this->valid_fields($registros[0], array('action', 'id_anuncio',
                'descripcion', 'nuevo_descripcion'));
            foreach ($registros as $registro) {
                $this->getAnuncio($registro['id_anuncio'], $anuncio, $em);
                $cont++;
                if (!$anuncio) {
                    $idAnuncio = $registro['id_anuncio'];
                    throw new \Exception("$cont.- No existe el anuncio con ID: $idAnuncio. No es posible relacionar.");
                }
                $condicion = $em->getRepository('YDIBackendBundle:Condiciones')->findOneBy(array(
                    'anuncio' => $anuncio->getId(),
                    'descripcion' => $registro['descripcion']
                ));
                if ($registro['action'] == 'B') {
                    if ($condicion) {
                        $em->remove($condicion);
                        continue;
                    } else {
                        $id = $registro['id_anuncio'];
                        $descripcion = $registro['descripcion'];
                        throw new \Exception("$cont.- El registro con IdAnuncio: $id y descripcion: $descripcion, no existe. No es posible eliminar el registro.");
                    }
                } else if ($registro['action'] == 'C') {
                    if ($condicion) {
                        if (strlen(trim($registro['nuevo_descripcion']))) {
                            $registro['descripcion'] = $registro['nuevo_descripcion'];
                        }
                        $condicion->setDescripcion($registro['descripcion']);
                        $condicion->setAnuncio($anuncio);
                        $em->flush();
                    } else {
                        $id = $registro['id_anuncio'];
                        $descripcion = $registro['descripcion'];
                        throw new \Exception("$cont.- El registro con IdAnuncio: $id y descripcion: $descripcion, no existe. No se puede actualizar el registro.");
                    }
                } else { // default $registro['action']=='A'
                    if (!$condicion) {
                        $condicion = new \YDI\BackendBundle\Entity\Condiciones();
                        $condicion->setDescripcion($registro['descripcion']);
                        $condicion->setAnuncio($anuncio);
                        $em->persist($condicion);
                    } else {
                        $id = $registro['id_anuncio'];
                        $descripcion = $registro['descripcion'];
                        throw new \Exception("$cont.- El registro con IdAnuncio: $id y descripcion: $descripcion, ya existe. No se puede crear un nuevo registro nuevamente.");
                    }
                }
            }
            $infoCarga[] = array('tabla' => 'condiciones', 'registros' => $cont);
        }
    }

    /*
     * De carga updates.
     */

    private function loadToCodigoPostalAnuncio($registros = null, &$em, &$infoCarga) {
        $anuncio = null;
        $codigoPostal = null;
        $cont = 0;
        if ($registros && count($registros) > 0) {
            $this->valid_fields($registros[0], array('action', 'id_anuncio',
                'codigopostal', 'estado'));
            foreach ($registros as $registro) {
                $this->getAnuncio($registro['id_anuncio'], $anuncio, $em);
                $cont++;
                if (!$anuncio) {
                    $idAnuncio = $registro['id_anuncio'];
                    throw new \Exception("$cont.- No existe el anuncio con ID: $idAnuncio. No es posible relacionar.");
                }
                if (strlen(trim($registro['codigopostal'])) > 0) {
                    //solo para validar que existe datos, no se ejecuta nada
                } else if (strlen(trim($registro['estado']))) {
                    $estado = $em->getRepository('YDIBackendBundle:Estado')->findOneBy(array(
                        'nombre' => $registro['estado']
                    ));
                    if (!$estado) {
                        throw new \Exception("$cont.- No existe el estado: " . $registro['estado']);
                    }
                    /* Se invalida este codigo porque ya solo lo van a cargar de otra forma
                     * if($registro['action']=='B'){
                      foreach($estado->getCodigosPostales() as $codigop){
                      if ($codigop->getAnuncio()->contains($anuncio)) {
                      $codigop->removeAnuncio($anuncio);
                      $em->persist($codigop);
                      }
                      }
                      $em->flush();
                      }else if($registro['action']=='A'){
                      foreach($estado->getCodigosPostales() as $codigop){
                      if (!$codigop->getAnuncio()->contains($anuncio)) {
                      $codigop->addAnuncio($anuncio);
                      $em->persist($codigop);
                      }
                      }
                      $em->flush();
                      }else{
                      throw new \Exception("$cont.- Solo es posible Agregar o Baja la referencia de anuncio y codigopostal o estado");
                      } */
                    $registro['codigopostal'] = $estado->getId();
                } else {
                    throw new \Exception("$cont.- No existe ningun dato para relacionar con el registro de anuncio.");
                }

                $this->getCodigoPostal($registro['codigopostal'], $codigoPostal, $em);
                if (!$codigoPostal) {
                    $cp = $registro['codigopostal'];
                    throw new \Exception("$cont.- El codigo postal: $cp, no existe. No es posible relacionar.");
                }
                if ($registro['action'] == 'B') {
                    if ($codigoPostal->getAnuncio()->contains($anuncio)) {
                        $codigoPostal->removeAnuncio($anuncio);
                        $em->persist($codigoPostal);
                        $em->flush();
                    }
                } else if ($registro['action'] == 'A') {
                    if (!$codigoPostal->getAnuncio()->contains($anuncio)) {
                        $codigoPostal->addAnuncio($anuncio);
                        $em->persist($codigoPostal);
                        $em->flush();
                    }
                } else {
                    throw new \Exception("$cont.- Solo es posible Agregar o Baja la referencia de anuncio y codigopostal o estado");
                }
            }
            $infoCarga[] = array('tabla' => 'codigop_anuncio', 'registros' => $cont);
        }
    }

    /*
     * De carga updates.
     */

    private function loadToPalabrasClave($registros = null, &$em, &$infoCarga) {
        $anuncio = null;
        $palabraClave = null;
        $cont = 0;
        $encontrado = false;
        if ($registros && count($registros) > 0) {
            $this->valid_fields($registros[0], array('action', 'id_anuncio',
                'palabra_cve', 'nuevo_palabra_cve'));
            foreach ($registros as $registro) {
                $this->getAnuncio($registro['id_anuncio'], $anuncio, $em);
                $cont++;
                if (!$anuncio) {
                    $idAnuncio = $registro['id_anuncio'];
                    throw new \Exception("$cont.- No existe el anuncio con ID: $idAnuncio. No es posible relacionar.");
                }
                $palabraClave = null;
                foreach ($anuncio->getPalabrasClave() as $palabra) {
                    if ($palabra->getPalabraClave() == $registro['palabra_cve']) {
                        $palabraClave = $palabra;
                        break;
                    }
                }
                if ($registro['action'] == 'B') {
                    if ($palabraClave) {
                        $em->remove($palabraClave);
                        continue;
                    } else {
                        $id = $registro['id_anuncio'];
                        $pc = $registro['palabra_cve'];
                        throw new \Exception("$cont.- El registro con IdAnuncio: $id y Palabra Clave: $pc, no existe. No es posible eliminar el registro.");
                    }
                } else if ($registro['action'] == 'C') {
                    if ($palabraClave) {
                        if (strlen(trim($registro['nuevo_palabra_cve']))) {
                            $registro['palabra_cve'] = $registro['nuevo_palabra_cve'];
                        }
                        $palabraClave->setPalabraClave($registro['palabra_cve']);
                        $palabraClave->setAnuncio($anuncio);
                        $em->flush();
                    } else {
                        $id = $registro['id_anuncio'];
                        $pc = $registro['palabra_cve'];
                        throw new \Exception("$cont.- El registro con IdAnuncio: $id y Palabra Clave: $pc, no existe. No se puede actualizar el registro.");
                    }
                } else { // default $registro['action']=='A'
                    if (!$palabraClave) {
                        $palabraClave = new \YDI\BackendBundle\Entity\PalabrasClave();
                        $palabraClave->setPalabraClave($registro['palabra_cve']);
                        $palabraClave->setAnuncio($anuncio);
                        $em->persist($palabraClave);
                    } else {
                        $id = $registro['id_anuncio'];
                        $descripcion = $registro['descripcion'];
                        throw new \Exception("$cont.- El registro con IdAnuncio: $id y descripcion: $descripcion, ya existe. No se puede crear un nuevo registro nuevamente.");
                    }
                }
            }
            $infoCarga[] = array('tabla' => 'palabras_clave', 'registros' => $cont);
        }
    }

    /*
     * De carga updates.
     */

    private function loadToPreferenciasUsuario($registros = null, &$em, &$infoCarga) {
        $usuario = null;
        $preferencia = null;
        $subnivel = null;
        $prefUsuario = null;
        $cont = 0;
        if ($registros && count($registros) > 0) {
            $this->valid_fields($registros[0], array('action', 'id_usuario',
                'preferencia', 'activo_flag'));
            foreach ($registros as $registro) {
                $this->getUsuario($registro['id_usuario'], $usuario, $em);
                $this->getPreferencia($registro['preferencia'], $preferencia, $em);
                $this->getSubnivelPreferencia($registro['preferencia'], $preferencia, $subnivel, $em);
                $cont++;
                if (!$usuario) {
                    $id = $registro['id_usuario'];
                    throw new \Exception("$cont.- No existe el usuario con ID: $id. No es posible relacionar.");
                }
                $prefUsuario = $em->getRepository('YDIBackendBundle:PreferenciasUsuario')->findOneBy(array(
                    'usuario' => $usuario->getId(),
                    'subnivelPreferencia' => $subnivel->getId()
                ));
                if ($registro['action'] == 'B') {
                    if ($prefUsuario) {
                        $em->remove($prefUsuario);
                        continue;
                    } else {
                        $id = $registro['id_usuario'];
                        $pref = $registro['preferencia'];
                        throw new \Exception("$cont.- El registro con IdUsuario: $id y Prefencia: $pref, no existe. No es posible eliminar el registro.");
                    }
                } else if ($registro['action'] == 'C') {
                    if (!$prefUsuario) {
                        $id = $registro['id_usuario'];
                        $pref = $registro['preferencia'];
                        throw new \Exception("$cont.- El registro con IdUsuario: $id y Prefencia: $pref, no existe. No es posible actualizar el registro.");
                    } else {
                        $prefUsuario->setUsuario($usuario);
                        $prefUsuario->setSubnivelPreferencia($subnivel);
                        $prefUsuario->setActivoFlag($registro['activo_flag']);
                        $em->flush();
                    }
                } else { // default $registro['action']=='A'
                    if (!$prefUsuario) {
                        $prefUsuario = new \YDI\BackendBundle\Entity\PreferenciasUsuario();
                        $prefUsuario->setUsuario($usuario);
                        $prefUsuario->setSubnivelPreferencia($subnivel);
                        $prefUsuario->setActivoFlag($registro['activo_flag']);
                        $em->persist($prefUsuario);
                    } else {
                        $id = $registro['id_usuario'];
                        $pref = $registro['preferencia'];
                        throw new \Exception("$cont.- El registro con IdUsuario: $id y Prefencia: $pref, ya existe. No se puede crear un nuevo registro nuevamente.");
                    }
                }
            }
            $infoCarga[] = array('tabla' => 'preferencias_usuario', 'registros' => $cont);
        }
    }

    /*
     * Funciones GET de Registros.
     */

    private function getCodigoPostalDeEstado($numero, &$estado, &$codigoPostal, &$em) {
        if (is_null($codigoPostal) || $numero != $codigoPostal->getId()) {
            $codigoPostal = $em->getRepository('YDIBackendBundle:Codigop')
                    ->find($numero);
        }
        if (!$codigoPostal) {
            $codigoPostal = new \YDI\BackendBundle\Entity\Codigop();
            $codigoPostal->setId($numero);
            $codigoPostal->setEstado($estado);
            $em->persist($codigoPostal);
        }
    }

    private function getCodigoPostal($numero, &$codigoPostal, &$em) {
        if (is_null($codigoPostal) || $numero != $codigoPostal->getId()) {
            $codigoPostal = $em->getRepository('YDIBackendBundle:Codigop')
                    ->find($numero);
        }
        if (!$codigoPostal) {
            throw new \Exception('No existe el Codigo Postal con Numero: ' . $numero);
        }
    }

    private function getEstadoDePais($nombre, &$pais, &$estado, &$em) {
        if (is_null($estado) || $nombre != $estado->getNombre()) {
            $estado = $em->getRepository('YDIBackendBundle:Estado')
                    ->findOneBy(array('nombre' => $nombre, 'pais' => $pais));
        }
        if (!$estado) {
            $estado = new \YDI\BackendBundle\Entity\Estado();
            $estado->setNombre($nombre);
            $estado->setPais($pais);
            $em->persist($estado);
            $em->flush();
        }
    }

    private function getEstado($nombre, &$estado, &$em) {
        if (is_null($estado) || $nombre != $estado->getNombre()) {
            $estado = $em->getRepository('YDIBackendBundle:Estado')
                    ->findOneBy(array('nombre' => $nombre));
        }
        if (!$estado) {
            $estado = new \YDI\BackendBundle\Entity\Estado();
            $estado->setNombre($nombre);
            $em->persist($estado);
            $em->flush();
        }
    }

    private function getPais($nombre, &$pais, &$em) {
        if (is_null($pais) || $nombre != $pais->getNombre()) {
            $pais = $em->getRepository('YDIBackendBundle:Pais')
                    ->findOneBy(array('nombre' => $nombre));
        }
        if (!$pais) {
            $pais = new \YDI\BackendBundle\Entity\Pais();
            $pais->setNombre($nombre);
            $em->persist($pais);
            $em->flush();
        }
    }

    private function getSubnivelPreferencia($nombre, &$pref, &$preferencia, &$em) {
        if (is_null($preferencia) || $nombre != $preferencia->getNombre()) {
            $preferencia = $em->getRepository('YDIBackendBundle:SubnivelPreferencia')
                    ->findOneBy(array('nombre' => $nombre));
        }
        if (!$preferencia) {
            $preferencia = new \YDI\BackendBundle\Entity\SubnivelPreferencia();
			$preferencia->setId($pref->getId());
            $preferencia->setNombre($nombre);
            $preferencia->setPreferencias($pref);
            $em->persist($preferencia);
            $em->flush();
        }
    }
	
	/* Función que elimina los acantos y letras ñ*/
    private function quitar_acentos($cadena) {
        $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿ';
        $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyyby';
        $cadena = utf8_decode($cadena);
        $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
        return utf8_encode($cadena);
    }

    private function getPreferencia($nombre, &$preferencia, &$em) {
        $logger = $this->get('logger');
        //var_dump($nombre); die;
        $logger->info("Nombre de preferecia: " . $nombre);
        $nombre = $this->quitar_acentos($nombre);
        $logger->info("Quitando acentos: " . $nombre);

        if (is_null($preferencia) || $nombre != $preferencia->getNombre()) {
            $preferencia = $em->getRepository('YDIBackendBundle:Preferencias')
                              ->findOneBy(array('nombre' => $nombre));
        }
        if($preferencia){
            $logger->info("Preferencia load: " . $preferencia->getNombre());
        }
        if (!$preferencia) {
            switch ($nombre) {
                case "Viajes":
                case "Tecnologia":
                case "Lujo":
                case "Entretenimiento":
                case "Vehiculos":
                case "Restaurantes":
                case "Deportes":
                case "Cultura":
                case "Moda":
                    $preferencia = new \YDI\BackendBundle\Entity\Preferencias();
                    $preferencia->setNombre($nombre);
                    $em->persist($preferencia);
                    $em->flush();
                    break;
                default:
                    throw new \Exception("La preferencia con nombre: $nombre, no esta en la lista de preferencias permitidas. No es posible realizar la alta de la preferencia.");
            }
        }
    }

    private function getEmisor($nombre, &$emisor, &$em) {
        if (is_null($emisor) || $nombre != $emisor->getNombre()) {
            $emisor = $em->getRepository('YDIBackendBundle:Emisor')
                    ->findOneBy(array('nombre' => $nombre));
        }
        if (!$emisor) {
            $emisor = new \YDI\BackendBundle\Entity\Emisor();
            $emisor->setNombre($nombre);
            $em->persist($emisor);
            $em->flush();
        }
    }

    private function getOperadorFinanciero($nombre, &$OF, &$em) {
        if (is_null($OF) || $nombre != $OF->getNombre()) {
            $OF = $em->getRepository('YDIBackendBundle:OperadorFinanciero')
                    ->findOneBy(array('nombre' => $nombre));
        }
        if (!$OF) {
            $OF = new \YDI\BackendBundle\Entity\OperadorFinanciero();
            $OF->setNombre($nombre);
            $em->persist($OF);
            $em->flush();
        }
    }

    private function getVista($nombre, &$vista, &$em) {
        if (is_null($vista) || $nombre != $vista->getNombre()) {
            $vista = $em->getRepository('YDIBackendBundle:Vista')
                    ->findOneBy(array('nombre' => $nombre));
        }
        if (!$vista) {
            $vista = new \YDI\BackendBundle\Entity\Vista();
            $vista->setNombre($nombre);
            $em->persist($vista);
            $em->flush();
        }
    }

    private function getEvento($nombre, &$evento, &$em) {
        if (is_null($evento) || $nombre != $evento->getNombre()) {
            $evento = $em->getRepository('YDIBackendBundle:Evento')
                    ->findOneBy(array('nombre' => $nombre));
        }
        if (!$evento) {
            $evento = new \YDI\BackendBundle\Entity\Evento();
            $evento->setNombre($nombre);
            $em->persist($evento);
            $em->flush();
        }
    }

    private function getTipoTarjeta($nombre, &$tiposTarjeta, &$em) {
        if (is_null($tiposTarjeta) || $nombre != $tiposTarjeta->getNombre()) {
            $tiposTarjeta = $em->getRepository('YDIBackendBundle:TiposTarjeta')
                    ->findOneBy(array('nombre' => $nombre));
        }
        if (!$tiposTarjeta) {
            $tiposTarjeta = new \YDI\BackendBundle\Entity\TiposTarjeta();
            $tiposTarjeta->setNombre($nombre);
            $em->persist($tiposTarjeta);
            $em->flush();
        }
    }

    private function getUsuario($id, &$usuario, &$em) {
        if (is_null($usuario) || $id != $usuario->getId()) {
            $usuario = $em->getRepository('YDIBackendBundle:Usuario')
                    ->find($id);
        }
        if (!$usuario) {
            throw new \Exception('No existe el Usuario con el ID: ' . $id);
        }
    }

    private function getUsuarioForEmail($data, &$usuario, &$em) {
        if (is_null($usuario) || $data['email'] != $usuario->getEmail()) {
            $usuario = $em->getRepository('YDIBackendBundle:Usuario')
                    ->findOneBy(array('email' => $data['email']));
        }
        if (!$usuario) {
            $usuario = new \YDI\BackendBundle\Entity\Usuario();
            $usuario->setNombre($data['nombre']);
            $usuario->setEmail($data['email']);
            $usuario->setUrl($data['url']);
            $usuario->setFechaSettings(new \DateTime($data['fecha_settings']));
            $em->persist($usuario);
            $em->flush();
        }
    }

    private function getAnuncio($id, &$anuncio, &$em) {
        if (is_null($anuncio) || $id != $anuncio->getId()) {
            $anuncio = $em->getRepository('YDIBackendBundle:Anuncio')->find($id);
        }
        if (!$anuncio) {
            throw new \Exception('No existe el Anuncio con ID: ' . $id);
        }
    }

    private function getEstablecimiento($id, &$establecimiento, &$em) {
        if (is_null($establecimiento) || $id != $establecimiento->getId()) {
            $establecimiento = $em->getRepository('YDIBackendBundle:Establecimiento')
                    ->find($id);
        }
        if (!$establecimiento) {
            throw new \Exception('No existe el Establecimiento con ID: ' . $id);
        }
    }

    private function getEstablecimientoForName($nombre, &$establecimiento, &$em) {
        if (is_null($establecimiento) || $nombre != $establecimiento->getNombre()) {
            $establecimiento = $em->getRepository('YDIBackendBundle:Establecimiento')
                    ->findOneBy(array('nombre' => $nombre));
        }
        if (!$establecimiento) {
            throw new \Exception('No existe el Establecimiento con Nombre: ' . $nombre);
        }
    }

    private function getGrupoEstablecimiento($clavegrupo, &$grupo, &$em) {
        if (is_null($grupo) || $clavegrupo != $grupo->getClavegrupo()) {
            $grupo = $em->getRepository('YDIBackendBundle:GrupoEstablecimiento')
                    ->findOneBy(array('clavegrupo' => $clavegrupo));
        }
        if (!$grupo) {
            throw new \Exception('No existe el Grupo de establecimiento con Clave: ' . $clavegrupo);
        }
    }

    private function getPalabraClave($palabra, &$palabraClave, &$em) {
        if (is_null($palabraClave) || $palabra != $palabraClave->getPalabraClave()) {
            $palabraClave = $em->getRepository('YDIBackendBundle:PalabrasClave')
                    ->findOneBy(array('palabraClave' => $palabra));
        }
        if (!$palabraClave) {
            $palabraClave = new \YDI\BackendBundle\Entity\PalabrasClave();
            $palabraClave->setPalabraClave($palabra);
            $em->persist($palabraClave);
            $em->flush();
        }
    }

    private function remove_files_path($ruta) {
        if ($dh = opendir($ruta)) {
            while (($file = readdir($dh)) !== false) {
                if (!is_dir($ruta . "/" . $file) && $file != "." && $file != ".." && $file != ".DS_Store") {
                    //solo si el archivo es un directorio, distinto que "." y ".."
                    //echo "<br>Directorio: $ruta$file";
                    //listar_directorios_ruta($ruta . $file . "/");
                    unlink($ruta . "/" . $file);
                }
            }
            closedir($dh);
        }
    }

    private function get_first_file_path($ruta) {
        $fileName = '';
        if ($dh = opendir($ruta)) {
            while (($file = readdir($dh)) !== false) {
                if (!is_dir($ruta . "/" . $file) && $file != "." && $file != ".." && $file != ".DS_Store") {
                    //solo si el archivo es un directorio, distinto que "." y ".."
                    //echo "<br>Directorio: $ruta$file";
                    //listar_directorios_ruta($ruta . $file . "/");
                    $fileName = $file;
                    break;
                }
            }
            closedir($dh);
        }
        return $fileName;
    }

    private function valid_fields($registro, $fields) {
        foreach ($fields as $field) {
            if (!array_key_exists($field, $registro)) {
                throw new \Exception("El campo: $field, no existe. Es necesario para realizar la operacion.");
                break;
            }
        }
    }

    /**
     * @Route("/backend/send/notification/resetear/bd/", name="backend_notificacion_resetear_bd")
     * @Method({"POST"})
     */
    public function sendNotificacionResetearBDAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if ($request->getMethod() == "POST") {
            $datos = $request->request->all();
            if(isset($datos['email'])){
                $email = $datos['email'];
                $usuario = $em->getRepository('YDIBackendBundle:Usuario')->findOneBy(array(
                   'email'=>$email 
                ));
                if(!$usuario){
                    $response = new JsonResponse(array('estatus' => 'error', 'motivo' => 'El usuario con email: ' . $email . ', no existe en la base de datos.'));
                }else{
                    $client = $this->get('endroid.gcm.client');
                    $telefonos = $em->getRepository('YDIBackendBundle:Telefono')->findBy(array(
                        'usuario'=>$usuario->getId()
                    ));
                    if(count($telefonos)>0){
                        $registrationIds = array();
                        foreach($telefonos as $telefono){
                            $registrationIds[] = $telefono->getGcmid();
                        }
                        $data = array('codigo' => '21');

                        $resp = $client->send($data, $registrationIds,array('dry_run' => false, 'collapse_key' => 'reseteo_db'));
                        $response = new JsonResponse(array('estatus' => 'exito', 'motivo' => 'Fue enviada la notificacion 21.'));
                    }else{
                        $response = new JsonResponse(array('estatus' => 'error', 'motivo' => 'El usuario con email: ' . $email . ', no existe en la base de datos.'));
                    }
                }
            }else{
                $response = new JsonResponse(array('estatus' => 'error', 'motivo' => 'No tiene el dato de email asociado al usuario.'));
            }
            
        } else {
            $response = new JsonResponse(array('estatus' => 'error', 'motivo' => 'No tiene autorizada esta ruta'));
        }
        return $response;
    }

    /**
     * @Route("/backend/send/notification/general/", name="backend_notificacion_general")
     * @Method({"POST"})
     */
    public function sendNotificacionesGeneralesAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if ($request->getMethod() == "POST") {
            try {
                $em->getRepository('YDIBackendBundle:Telefono')->enviarNotificaciones();
                $response = new JsonResponse(array('estatus' => 'ok'));
            } catch (Exception $e) {
                $response = new JsonResponse(array('estatus' => 'error', 'motivo' => $e->getMessage()));
            }
        } else {
            $response = new JsonResponse(array('estatus' => 'error', 'motivo' => 'No tiene autorizada esta ruta'));
        }
        return $response;
    }

}
