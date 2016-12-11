<?php

namespace YDI\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vista
 *
 * @ORM\Table(name="vista")
 * @ORM\Entity
 */
class Vista
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
     * @ORM\OneToMany(targetEntity="ContadoresDispositivo", mappedBy="vista")
     */
    private $contadoresDispositivo;

    /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     *
     * @ORM\OneToMany(targetEntity="Navegacion", mappedBy="vista")
     */
    private $navegaciones;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contadoresDispositivo = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Vista
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
     * Add contadoresDispositivo
     *
     * @param \YDI\BackendBundle\Entity\ContadoresDispositivo $contadoresDispositivo
     *
     * @return Vista
     */
    public function addContadoresDispositivo(\YDI\BackendBundle\Entity\ContadoresDispositivo $contadoresDispositivo)
    {
        $this->contadoresDispositivo[] = $contadoresDispositivo;

        return $this;
    }

    /**
     * Remove contadoresDispositivo
     *
     * @param \YDI\BackendBundle\Entity\ContadoresDispositivo $contadoresDispositivo
     */
    public function removeContadoresDispositivo(\YDI\BackendBundle\Entity\ContadoresDispositivo $contadoresDispositivo)
    {
        $this->contadoresDispositivo->removeElement($contadoresDispositivo);
    }

    /**
     * Get contadoresDispositivo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContadoresDispositivo()
    {
        return $this->contadoresDispositivo;
    }

    /**
     * Add navegacione
     *
     * @param \YDI\BackendBundle\Entity\Navegacion $navegacione
     *
     * @return Vista
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
