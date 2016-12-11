<?php

namespace YDI\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContadorEventoAnuncio
 *
 * @ORM\Table(name="contador_evento_anuncio", indexes={@ORM\Index(name="fk_contador_evento_anuncio_evento1_idx", columns={"id_evento"}), @ORM\Index(name="fk_contador_evento_anuncio_anuncio1_idx", columns={"id_anuncio"}), @ORM\Index(name="fk_contador_evento_anuncio_usuario1_idx", columns={"id_usuario"})})
 * @ORM\Entity
 */
class ContadorEventoAnuncio
{
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
     * @var \Anuncio
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Anuncio")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_anuncio", referencedColumnName="id")
     * })
     */
    private $anuncio;

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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return ContadorEventoAnuncio
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
     * @return ContadorEventoAnuncio
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
     * Set usuario
     *
     * @param \YDI\BackendBundle\Entity\Usuario $usuario
     *
     * @return ContadorEventoAnuncio
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
     * Set anuncio
     *
     * @param \YDI\BackendBundle\Entity\Anuncio $anuncio
     *
     * @return ContadorEventoAnuncio
     */
    public function setAnuncio(\YDI\BackendBundle\Entity\Anuncio $anuncio)
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
     * Set evento
     *
     * @param \YDI\BackendBundle\Entity\Evento $evento
     *
     * @return ContadorEventoAnuncio
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
}
