<?php

namespace YDI\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GrupoEstablecimiento
 *
 * @ORM\Table(name="grupo_establecimiento")
 * @ORM\Entity
 */
class GrupoEstablecimiento
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
     * @ORM\Column(name="clavegrupo", type="string", length=10, nullable=true)
     */
    private $clavegrupo;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */
    private $nombre;
    
    /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     *
     * @ORM\OneToMany(targetEntity="Establecimiento", mappedBy="grupoEstablecimiento")
     */
    private $establecimientos;
    
    /**
     * @var \Booolean
     *
     * @ORM\Column(name="inactivo", type="boolean", nullable=true)
     */
    private $inactivo = false;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->establecimientos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set clavegrupo
     *
     * @param string $clavegrupo
     *
     * @return GrupoEstablecimiento
     */
    public function setClavegrupo($clavegrupo)
    {
        $this->clavegrupo = $clavegrupo;

        return $this;
    }

    /**
     * Get clavegrupo
     *
     * @return string
     */
    public function getClavegrupo()
    {
        return $this->clavegrupo;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return GrupoEstablecimiento
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
     * Add establecimiento
     *
     * @param \YDI\BackendBundle\Entity\Establecimiento $establecimiento
     *
     * @return GrupoEstablecimiento
     */
    public function addEstablecimiento(\YDI\BackendBundle\Entity\Establecimiento $establecimiento)
    {
        $this->establecimientos[] = $establecimiento;

        return $this;
    }

    /**
     * Remove establecimiento
     *
     * @param \YDI\BackendBundle\Entity\Establecimiento $establecimiento
     */
    public function removeEstablecimiento(\YDI\BackendBundle\Entity\Establecimiento $establecimiento)
    {
        $this->establecimientos->removeElement($establecimiento);
    }

    /**
     * Get establecimientos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEstablecimientos()
    {
        return $this->establecimientos;
    }

    /**
     * Set inactivo
     *
     * @param boolean $inactivo
     *
     * @return GrupoEstablecimiento
     */
    public function setInactivo($inactivo)
    {
        $this->inactivo = $inactivo;

        return $this;
    }

    /**
     * Get inactivo
     *
     * @return boolean
     */
    public function getInactivo()
    {
        return $this->inactivo;
    }
}
