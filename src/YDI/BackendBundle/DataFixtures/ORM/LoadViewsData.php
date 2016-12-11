<?php

// src/YDI/BackendBundle/DataFixtures/ORM/LoadUserData.php
namespace YDI\BackendBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use YDI\BackendBundle\Entity\Vista;


class LoadViewsData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {

    	$promociones = new Vista();
        $promociones->setNombre("promociones");
        $manager->persist($promociones);

        $lugares = new Vista();
        $lugares->setNombre("lugares");
        $manager->persist($lugares);

        $anunRapidos = new Vista();
        $anunRapidos->setNombre("anuncios rapidos");
        $manager->persist($anunRapidos);

        $manager->flush();
    }

    public function getOrder()
    {
        // the order for load data.
        return 2;
    }
}