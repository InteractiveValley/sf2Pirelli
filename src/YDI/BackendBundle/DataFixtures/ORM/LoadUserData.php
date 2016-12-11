<?php

// src/YDI/BackendBundle/DataFixtures/ORM/LoadUserData.php
namespace YDI\BackendBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use YDI\BackendBundle\Entity\Usuario;
use YDI\BackendBundle\Entity\Codigop;
use YDI\BackendBundle\Entity\Estado;
use YDI\BackendBundle\Entity\Pais;


class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {

    	$codigoPostal = $manager->getRepository('YDIBackendBundle:Codigop')->findOneBy(array('numero'=>999999));
    	if($codigoPostal==null){
                $pais = new Pais();
                $pais->setNombre("Mexico");
                $manager->persist($pais);
                
                $estado = new Estado();
                $estado->setNombre("Todos");
                $estado->setPais($pais);
                $manager->persist($estado);
    		
                $codigoPostal = new Codigop();
    		$codigoPostal->setNumero(999999);
    		$codigoPostal->setEstado($estado);
    		$manager->persist($codigoPostal);
    	}

        $dummy = new Usuario();
        $dummy->setNombre('Dummy');
        $dummy->setApellidos('dummy');
        $dummy->setEmail('dymmy@dummy.com');
        $dummy->setFechaSettings(new \DateTime());
        //$dummy->setUri("");
        $dummy->setCodigoPostal($codigoPostal);

        $manager->persist($dummy);
        $manager->flush();
    }

    public function getOrder()
    {
        // the order for load data.
        return 1;
    }
}