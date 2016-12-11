<?php

namespace YDI\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rechazos
 *
 * @ORM\Table(name="rechazos", indexes={@ORM\Index(name="fk_rechazos_usuario1_idx", columns={"id_usuario"})})
 * @ORM\Entity
 */
class Rechazos
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
     * @var \Anuncio
     *
     * @ORM\ManyToOne(targetEntity="Anuncio", inversedBy="rechazos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_anuncio", referencedColumnName="id")
     * })
     */
    private $anuncio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="puntos", type="integer", nullable=true)
     */
    private $puntos;

    /**
     * @var \Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="rechazos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_usuario", referencedColumnName="id")
     * })
     */
    private $usuario;

    /**
     * @var \Booolean
     *
     * @ORM\Column(name="no_aceptadoaplicado", type="boolean", nullable=true)
     */
    private $noAceptadoAplicado = true;



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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return Rechazos
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set noAceptadoAplicado
     *
     * @param boolean $noAceptadoAplicado
     *
     * @return Rechazos
     */
    public function setNoAceptadoAplicado($noAceptadoAplicado)
    {
        $this->noAceptadoAplicado = $noAceptadoAplicado;

        return $this;
    }

    /**
     * Get noAceptadoAplicado
     *
     * @return boolean
     */
    public function getNoAceptadoAplicado()
    {
        return $this->noAceptadoAplicado;
    }

    /**
     * Set anuncio
     *
     * @param \YDI\BackendBundle\Entity\Anuncio $anuncio
     *
     * @return Rechazos
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

    /**
     * Set usuario
     *
     * @param \YDI\BackendBundle\Entity\Usuario $usuario
     *
     * @return Rechazos
     */
    public function setUsuario(\YDI\BackendBundle\Entity\Usuario $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \YDI\BackendBundle\Entity\Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set puntos
     *
     * @param integer $puntos
     *
     * @return Rechazos
     */
    public function setPuntos($puntos)
    {
        $this->puntos = $puntos;

        return $this;
    }

    /**
     * Get puntos
     *
     * @return integer
     */
    public function getPuntos()
    {
        return $this->puntos;
    }
}
