<?php

namespace YDI\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Emisor
 *
 * @ORM\Table(name="emisor")
 * @ORM\Entity
 */
class Emisor
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
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
     * @ORM\OneToOne(targetEntity="TarjetaEmisorOperador", mappedBy="emisor")
     */
    private $tarjetasEmisorOperador;
    
    /**
     * Bidirectional - One-To-One (INVERSE SIDE)
     *
     * @ORM\OneToMany(targetEntity="Tarjeta", mappedBy="emisor")
     */
    private $tarjetas;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tarjetas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Emisor
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
     * Set tarjetasEmisorOperador
     *
     * @param \YDI\BackendBundle\Entity\TarjetaEmisorOperador $tarjetasEmisorOperador
     *
     * @return Emisor
     */
    public function setTarjetasEmisorOperador(\YDI\BackendBundle\Entity\TarjetaEmisorOperador $tarjetasEmisorOperador = null)
    {
        $this->tarjetasEmisorOperador = $tarjetasEmisorOperador;

        return $this;
    }

    /**
     * Get tarjetasEmisorOperador
     *
     * @return \YDI\BackendBundle\Entity\TarjetaEmisorOperador
     */
    public function getTarjetasEmisorOperador()
    {
        return $this->tarjetasEmisorOperador;
    }

    /**
     * Add tarjeta
     *
     * @param \YDI\BackendBundle\Entity\Tarjeta $tarjeta
     *
     * @return Emisor
     */
    public function addTarjeta(\YDI\BackendBundle\Entity\Tarjeta $tarjeta)
    {
        $this->tarjetas[] = $tarjeta;

        return $this;
    }

    /**
     * Remove tarjeta
     *
     * @param \YDI\BackendBundle\Entity\Tarjeta $tarjeta
     */
    public function removeTarjeta(\YDI\BackendBundle\Entity\Tarjeta $tarjeta)
    {
        $this->tarjetas->removeElement($tarjeta);
    }

    /**
     * Get tarjetas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTarjetas()
    {
        return $this->tarjetas;
    }
}
