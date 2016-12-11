<?php

namespace YDI\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Puntosxusuario
 *
 * @ORM\Table(name="puntosxusuario", indexes={@ORM\Index(name="fk_puntos_usuario1_idx", columns={"id_usuario"})})
 * @ORM\Entity
 */
class Puntosxusuario
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
     * @var integer
     *
     * @ORM\Column(name="id_anuncio", type="integer", nullable=true)
     */
    private $anuncio = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="puntos", type="integer", nullable=true)
     */
    private $puntos;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @var integer
     *
     * @ORM\Column(name="calificacionestablecimiento", type="integer", nullable=true)
     */
    private $calificacionestablecimiento;

    /**
     * @var integer
     *
     * @ORM\Column(name="calificacionapp", type="integer", nullable=true)
     */
    private $calificacionapp;
    
    /**
     * @var \Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="puntosXUsuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_usuario", referencedColumnName="id")
     * })
     */
    private $usuario;

    /**
     * @var string
     *
     * @ORM\Column(name="comentario", type="text", nullable=true)
     */
    private $comentario;
    
    /**
     * @var string
     *
     * @ORM\Column(name="ticket", type="string", length=20, nullable=true)
     */
    private $ticket;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id_tipotarjeta", type="integer", nullable=true)
     */
    private $tipoTarjeta = 0;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id_emisor", type="integer", nullable=true)
     */
    private $emisor = 0;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id_operador", type="integer", nullable=true)
     */
    private $operador = 0;

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
     * Set anuncio
     *
     * @param integer $anuncio
     *
     * @return Puntosxusuario
     */
    public function setAnuncio($anuncio)
    {
        $this->anuncio = $anuncio;

        return $this;
    }

    /**
     * Get anuncio
     *
     * @return integer
     */
    public function getAnuncio()
    {
        return $this->anuncio;
    }

    /**
     * Set puntos
     *
     * @param integer $puntos
     *
     * @return Puntosxusuario
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

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return Puntosxusuario
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
     * Set calificacionestablecimiento
     *
     * @param integer $calificacionestablecimiento
     *
     * @return Puntosxusuario
     */
    public function setCalificacionestablecimiento($calificacionestablecimiento)
    {
        $this->calificacionestablecimiento = $calificacionestablecimiento;

        return $this;
    }

    /**
     * Get calificacionestablecimiento
     *
     * @return integer
     */
    public function getCalificacionestablecimiento()
    {
        return $this->calificacionestablecimiento;
    }

    /**
     * Set calificacionapp
     *
     * @param integer $calificacionapp
     *
     * @return Puntosxusuario
     */
    public function setCalificacionapp($calificacionapp)
    {
        $this->calificacionapp = $calificacionapp;

        return $this;
    }

    /**
     * Get calificacionapp
     *
     * @return integer
     */
    public function getCalificacionapp()
    {
        return $this->calificacionapp;
    }

    /**
     * Set comentario
     *
     * @param string $comentario
     *
     * @return Puntosxusuario
     */
    public function setComentario($comentario)
    {
        $this->comentario = $comentario;

        return $this;
    }

    /**
     * Get comentario
     *
     * @return string
     */
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * Set ticket
     *
     * @param string $ticket
     *
     * @return Puntosxusuario
     */
    public function setTicket($ticket)
    {
        $this->ticket = $ticket;

        return $this;
    }

    /**
     * Get ticket
     *
     * @return string
     */
    public function getTicket()
    {
        return $this->ticket;
    }

    /**
     * Set tipoTarjeta
     *
     * @param integer $tipoTarjeta
     *
     * @return Puntosxusuario
     */
    public function setTipoTarjeta($tipoTarjeta)
    {
        $this->tipoTarjeta = $tipoTarjeta;

        return $this;
    }

    /**
     * Get tipoTarjeta
     *
     * @return integer
     */
    public function getTipoTarjeta()
    {
        return $this->tipoTarjeta;
    }

    /**
     * Set emisor
     *
     * @param integer $emisor
     *
     * @return Puntosxusuario
     */
    public function setEmisor($emisor)
    {
        $this->emisor = $emisor;

        return $this;
    }

    /**
     * Get emisor
     *
     * @return integer
     */
    public function getEmisor()
    {
        return $this->emisor;
    }

    /**
     * Set operador
     *
     * @param integer $operador
     *
     * @return Puntosxusuario
     */
    public function setOperador($operador)
    {
        $this->operador = $operador;

        return $this;
    }

    /**
     * Get operador
     *
     * @return integer
     */
    public function getOperador()
    {
        return $this->operador;
    }

    /**
     * Set usuario
     *
     * @param \YDI\BackendBundle\Entity\Usuario $usuario
     *
     * @return Puntosxusuario
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
}
