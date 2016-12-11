<?php

namespace YDI\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Condiciones
 *
 * @ORM\Table(name="condiciones", indexes={@ORM\Index(name="fk_condiciones_anuncio1_idx", columns={"id_anuncio"})})
 * @ORM\Entity
 */
class Condiciones
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
     * @ORM\Column(name="descripcion", type="string", length=120, nullable=true)
     */
    private $descripcion;

    /**
     * @var \Anuncio
     *
     * @ORM\ManyToOne(targetEntity="Anuncio", inversedBy="condiciones")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_anuncio", referencedColumnName="id")
     * })
     */
    private $anuncio;

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
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Condiciones
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set anuncio
     *
     * @param \YDI\BackendBundle\Entity\Anuncio $anuncio
     *
     * @return Condiciones
     */
    public function setAnuncio(\YDI\BackendBundle\Entity\Anuncio $anuncio = null)
    {
        $this->anuncio = $anuncio;

        return $this;
    }

    /**
     * Get anuncio
     *
     * @return \YDI\BackendBundle\Entity\Anuncio
     */
    public function getAnuncio()
    {
        return $this->anuncio;
    }
}
