<?php

namespace YDI\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Evento
 *
 * @ORM\Table(name="evento")
 * @ORM\Entity
 */
class Evento
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
     * @ORM\OneToMany(targetEntity="Navegacion", mappedBy="evento")
     */
    private $navegaciones;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->navegaciones = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Evento
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
     * Add navegacione
     *
     * @param \YDI\BackendBundle\Entity\Navegacion $navegacione
     *
     * @return Evento
     */
    public function addNavegacione(\YDI\BackendBundle\Entity\Navegacion $navegacione)
    {
        $this->navegaciones[] = $navegacione;

        return $this;
    }

    /**
     * Remove navegacione
     *
     * @param \YDI\BackendBundle\Entity\Navegacion $navegacione
     */
    public function removeNavegacione(\YDI\BackendBundle\Entity\Navegacion $navegacione)
    {
        $this->navegaciones->removeElement($navegacione);
    }

    /**
     * Get navegaciones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNavegaciones()
    {
        return $this->navegaciones;
    }
}
