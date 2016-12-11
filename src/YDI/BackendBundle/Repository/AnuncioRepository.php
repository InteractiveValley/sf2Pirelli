<?php

namespace YDI\BackendBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * AnuncioRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AnuncioRepository extends EntityRepository
{
    public function findCodigoPostalPorAnuncio($anuncio, $codigoPostal) {
        $em = $this->getEntityManager();
        $consulta = $em->createQuery('SELECT cp '
                . 'FROM YDIBackendBundle:Anuncio a '
                . 'JOIN INNER a.codigosPostales cp'
                . 'WHERE a.id == :anuncio AND cp.numero == :cp ');
        $consulta->setParameters(array(
            'anuncio' => $anuncio->getId(),
            'cp'=> $codigoPostal->getNumero()
        ));
        return $consulta->getOneOrNullResult();
    }
    
    public function findPalabraClavePorAnuncio($anuncio, $palabraClave) {
        $em = $this->getEntityManager();
        $consulta = $em->createQuery('SELECT p '
                . 'FROM YDIBackendBundle:Anuncio a '
                . 'JOIN INNER a.palabrasClave p'
                . 'WHERE a.id == :anuncio AND p.palabraClave == :palabraClave ');
        $consulta->setParameters(array(
            'anuncio' => $anuncio->getId(),
            'palabraClave'=> $palabraClave->getPalabraClave()
        ));
        return $consulta->getOneOrNullResult();
    }

    public function queryFindAnuncios($buscar = "",$usuario=null,$operador="=")
    {
        $em = $this->getEntityManager();
        if(strlen($buscar)==0){
            if($usuario == null){
                $consulta = $em->createQuery('SELECT h '
                    . 'FROM AnunciosBundle:Anuncio h '
                    . 'ORDER BY h.titulo ASC');
            }else{
                $consulta = $em->createQuery("SELECT h "
                    . "FROM AnunciosBundle:Anuncio h "
                    . "WHERE h.usuario$operador:usuario "    
                    . "ORDER BY h.titulo ASC");
                $consulta->setParameters(array(
                    'usuario' => $usuario->getId()
                ));
            }
        }
        return $consulta;
    }
    
    public function findAnuncios($buscar = "", $usuario=null, $operador="="){
        return $this->queryFindAnuncios($buscar,$usuario,$operador)->getResult();
    }
}