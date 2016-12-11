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
     * @Method({"GET","POST"})
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
        }else{
            $establecimientos = $this->getDoctrine()->getManager()
                    ->getRepository('YDIBackendBundle:Establecimiento')->findAll();
        }
        if (file_exists($fileName)) {
            $cargas = $this->getResumenImportar($fileName);
        }
        return array(
            'cargas' => $cargas,
            'entities' => $establecimientos
        );
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
     * @Route("/backend/eliminar/establecimiento/{id}", name="backend_delete_establecimientos")
     * @Method({"DELETE"})
     */
    public function deleteEstablecimientoAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        if ($request->getMethod() == 'DELETE') {
            $registro = $em->find('YDIBackendBundle:Establecimiento', $id);
            if(!$registro){
                throw $this->createNotFoundException('El registro no existe.');
            }
            $em->remove($registro);
            $em->flush();
            return new JsonResponse(array("msg"=>"Registro eliminado"));
        }else{
            throw $this->createNotFoundException('No acceso por este medio.');
        }
    }

    /**
     * @Route("/backend/mensaje/", name="backend_mensaje")
     * @Template()
     * @Method({"GET","POST"})
     */
    public function mensajeAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $publicacion = $em->getRepository('YDIBackendBundle:Publicacion')->find(1);
        
        if(!$publicacion){
            $publicacion = new \YDI\BackendBundle\Entity\Publicacion();
            $publicacion->setMensaje("Bienvenidos a la plataforma de anuncios Pirelli, escriba su mensaje");
            $em->persist($publicacion);
            $em->flush();
        }
        
        $form = $this->createForm(new \YDI\BackendBundle\Form\PublicacionType(), $publicacion, array(
            'action' => $this->generateUrl('backend_mensaje'),
            'method' => 'POST'
        ));
        $form->add('submit', 'submit', array('label' => 'Actualizar', 'attr'=>array('class'=>'btn btn-primary')));
        
        $form->handleRequest($request);
        if ($request->getMethod() == 'POST') {
            $em->flush();
        }
        
        return array(
            'entity'        => $publicacion,
            'form'          => $form->createView()
        );
    }

    /**
     * @Route("/backend/carga/imagenes/", name="backend_upload_images")
     * @Template()
     * @Method({"GET","POST"})
     */
    public function uploadImagesAction(Request $request) {
        $phpconteo = 0;
        $arreglo = array();
        $em = $this->getDoctrine()->getManager();
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
                        $img = new \YDI\BackendBundle\Entity\Imagen();
                        $img->setImagen($imagen->getClientOriginalName());
                        $img->setPosicion(0);
                        $em->persist($img);
                    }
                }
            }
            $em->flush();
            return new JsonResponse($arreglo);
        }else{
            $imagenes = $em->getRepository('YDIBackendBundle:Imagen')->findAll();
        }
        
        return array(
            "entities" => $imagenes
        );
    }
    
    /**
     * @Route("/backend/eliminar/imagenes/{id}", name="backend_delete_images")
     * @Method({"DELETE"})
     */
    public function deleteImagesAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        if ($request->getMethod() == 'DELETE') {
            $registro = $em->find('YDIBackendBundle:Imagen', $id);
            if(!$registro){
                throw $this->createNotFoundException('El registro no existe.');
            }
            $em->remove($registro);
            $em->flush();
            return new JsonResponse(array("msg"=>"Registro eliminado"));
        }else{
            throw $this->createNotFoundException('No acceso por este medio.');
        }
    }
    
    /**
     * @Route("/backend/estados/", name="backend_estados")
     * @Template()
     * @Method({"GET","POST"})
     */
    public function estadosAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if ($request->getMethod() == 'POST') {
            $data = $request->request->all();
            $estado = $em->find('YDIBackendBundle:Estado', $data['id']);
            if(!$estado){
                throw $this->createNotFoundException('No existe el registro del estado ' . $data['nombre']);
            }
            $data['mostrar'] = ($data['mostrar'])?true:false;
            $estado->setMostrar($data['mostrar']);
            $em->flush();
            return new JsonResponse(array(
                'id'=> $estado->getId(),
                'estado'=> $estado->getNombre(),
                'mostrar'=> $estado->getMostrar()
            ));
        }else{
            $estados = $em->getRepository('YDIBackendBundle:Estado')->findBy(
                    array(), array('nombre'=>'ASC')
            );
        }
        
        return array(
            "entities" => $estados
        );
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

        if (!$error) {
            $em->getConnection()->commit();
        }
        return $infoCarga;
    }

    private function loadTo($tabla, &$registros, &$em, &$infoCarga) {
        if ($tabla == "pirelli") {
            $this->loadToRegistros($registros, $em, $infoCarga);
            return '';
        }else{
            return "404";
        } 
    }

    /*
     * De carga inicial.
     */
    private function loadToRegistros($registros = null, &$em, &$infoCarga) {
        if ($registros && count($registros) > 0) {
            $this->valid_fields($registros[0], array('estado', 'ciudad', 'nombre','tipologia','direccion','cp','telefonos'));
            
            // elimina los registros antes cargados
            $em = $this->getDoctrine()->getManager();
            $establecimientos = $em->getRepository('YDIBackendBundle:Establecimiento')->findAll();
            foreach($establecimientos as $establecimiento){
                $em->remove($establecimiento);
            }
            $em->flush();
            $cont = 0;
            
            foreach ($registros as $registro) {
                $establecimiento = new \YDI\BackendBundle\Entity\Establecimiento();
                $establecimiento->setEstado($registro['estado']);
                $establecimiento->setCiudad($registro['ciudad']);
                $establecimiento->setNombre($registro['nombre']);
                $establecimiento->setTipologia($registro['tipologia']);
                $establecimiento->setDireccion($registro['direccion']);
                $establecimiento->setCp($registro['cp']);
                $establecimiento->setTelefonos($registro['telefonos']);
                $em->persist($establecimiento);
                $cont++;
            }
            $em->flush();
            // busca los registros que aun no existen
            $registros = $em->getRepository('YDIBackendBundle:Establecimiento')->getDistinctEstados();
            foreach($registros as $registro){
                $estado = $em->getRepository('YDIBackendBundle:Estado')->findOneBy(array(
                    'nombre' => $registro['estado']
                ));
                if(!$estado){
                    $estado = new \YDI\BackendBundle\Entity\Estado();
                    $estado->setNombre($registro['estado']);
                    $estado->setMostrar(true);
                    $em->persist($estado);
                }
            }
            $em->flush();
            $infoCarga[] = array('tabla' => 'pirelli', 'registros' => $cont);
            
        }
    }
	
    /** 
     * Función que elimina los acantos y letras ñ
     */
    private function quitar_acentos($cadena) {
        $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿ';
        $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyyby';
        $cadena = utf8_decode($cadena);
        $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
        return utf8_encode($cadena);
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

}
