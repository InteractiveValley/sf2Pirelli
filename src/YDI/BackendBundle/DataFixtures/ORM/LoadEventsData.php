<?php

// src/YDI/BackendBundle/DataFixtures/ORM/LoadUserData.php
namespace YDI\BackendBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use YDI\BackendBundle\Entity\Evento;


class LoadEventsData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {

    	$click = new Evento();
        $click->setNombre("click");
        $manager->persist($click);

        $manager->flush();
    }

    public function getOrder()
    {
        // the order for load data.
        return 3;
    }
}