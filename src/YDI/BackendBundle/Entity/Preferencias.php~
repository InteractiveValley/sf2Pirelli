<?php

namespace YDI\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Preferencias
 *
 * @ORM\Table(name="preferencias")
 * @ORM\Entity
 */
class Preferencias
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=45, nullable=true)
     */
    private $nombre;

    /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     *
     * @ORM\OneToMany(targetEntity="SubnivelPreferencia", mappedBy="preferencias")
     */
    private $subnivelesPreferencia;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subnivelesPreferencia = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Preferencias
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Add subnivelesPreferencium
     *
     * @param \YDI\BackendBundle\Entity\SubnivelPreferencia $subnivelesPreferencium
     *
     * @return Preferencias
     */
    public function addSubnivelesPreferencium(\YDI\BackendBundle\Entity\SubnivelPreferencia $subnivelesPreferencium)
    {
        $this->subnivelesPreferencia[] = $subnivelesPreferencium;

        return $this;
    }

    /**
     * Remove subnivelesPreferencium
     *
     * @param \YDI\BackendBundle\Entity\SubnivelPreferencia $subnivelesPreferencium
     */
    public function removeSubnivelesPreferencium(\YDI\BackendBundle\Entity\SubnivelPreferencia $subnivelesPreferencium)
    {
        $this->subnivelesPreferencia->removeElement($subnivelesPreferencium);
    }

    /**
     * Get subnivelesPreferencia
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubnivelesPreferencia()
    {
        return $this->subnivelesPreferencia;
    }
}
