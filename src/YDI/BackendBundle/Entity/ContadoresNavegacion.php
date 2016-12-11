<?php

namespace YDI\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContadoresNavegacion
 *
 * @ORM\Table(name="contadores_navegacion", indexes={@ORM\Index(name="fk_cnavegacion_usuario1_idx", columns={"id_usuario"}), @ORM\Index(name="fk_cnavegacion_evento1_idx", columns={"id_evento"}), @ORM\Index(name="fk_cnavegacion_vista1_idx", columns={"id_vista"})})
 * @ORM\Entity
 */
class ContadoresNavegacion
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date", nullable=false)
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="contador", type="text", length=16777215, nullable=true)
     */
    private $contador;

    /**
     * @var \Evento
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Evento")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_evento", referencedColumnName="id")
     * })
     */
    private $evento;

    /**
     * @var \Usuario
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_usuario", referencedColumnName="id")
     * })
     */
    private $usuario;

    /**
     * @var \Vista
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Vista")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_vista", referencedColumnName="id")
     * })
     */
    private $vista;

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return ContadoresNavegacion
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
     * Set contador
     *
     * @param string $contador
     *
     * @return ContadoresNavegacion
     */
    public function setContador($contador)
    {
        $this->contador = $contador;

        return $this;
    }

    /**
     * Get contador
     *
     * @return string
     */
    public function getContador()
    {
        return $this->contador;
    }

    /**
     * Set evento
     *
     * @param \YDI\BackendBundle\Entity\Evento $evento
     *
     * @return ContadoresNavegacion
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
     * @return ContadoresNavegacion
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
     * @return ContadoresNavegacion
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
