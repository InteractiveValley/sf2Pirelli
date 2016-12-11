<?php

namespace YDI\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Navegacion
 *
 * @ORM\Table(name="navegacion", indexes={@ORM\Index(name="fk_navegacion_usuario1_idx", columns={"id_usuario"}), @ORM\Index(name="fk_navegacion_evento1_idx", columns={"id_evento"}), @ORM\Index(name="fk_navegacion_vista1_idx", columns={"id_vista"})})
 * @ORM\Entity
 */
class Navegacion
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
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @var \Evento
     *
     * @ORM\ManyToOne(targetEntity="Evento", inversedBy="navegaciones")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_evento", referencedColumnName="id")
     * })
     */
    private $evento;

    /**
     * @var \Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="navegaciones")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_usuario", referencedColumnName="id")
     * })
     */
    private $usuario;

    /**
     * @var \Vista
     *
     * @ORM\ManyToOne(targetEntity="Vista", inversedBy="navegaciones")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_vista", referencedColumnName="id")
     * })
     */
    private $vista;



    /**
     * Set id
     *
     * @param integer $id
     *
     * @return Navegacion
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return Navegacion
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
     * Set evento
     *
     * @param \YDI\BackendBundle\Entity\Evento $evento
     *
     * @return Navegacion
     */
    public function setEvento(\YDI\BackendBundle\Entity\Evento $evento)
    {
        $this->evento = $evento;

        return $this;
    }

    /**
     * Get evento
     *
     * @return \YDI\BackendBundle\Entity\Evento
     */
    public function getEvento()
    {
        return $this->evento;
    }

    /**
     * Set usuario
     *
     * @param \YDI\BackendBundle\Entity\Usuario $usuario
     *
     * @return Navegacion
     */
    public function setUsuario(\YDI\BackendBundle\Entity\Usuario $usuario)
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
     * Set vista
     *
     * @param \YDI\BackendBundle\Entity\Vista $vista
     *
     * @return Navegacion
     */
    public function setVista(\YDI\BackendBundle\Entity\Vista $vista)
    {
        $this->vista = $vista;

        return $this;
    }

    /**
     * Get vista
     *
     * @return \YDI\BackendBundle\Entity\Vista
     */
    public function getVista()
    {
        return $this->vista;
    }
}
