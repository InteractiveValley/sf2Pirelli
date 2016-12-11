<?php

namespace YDI\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContadorVistaAnuncio
 *
 * @ORM\Table(name="contador_vista_anuncio", indexes={@ORM\Index(name="fk_contador_vista_anuncio_vista1_idx", columns={"id_vista"}), @ORM\Index(name="fk_contador_vista_anuncio_anuncio1_idx", columns={"id_anuncio"}), @ORM\Index(name="fk_contador_vista_anuncio_usuario1_idx", columns={"id_usuario"})})
 * @ORM\Entity
 */
class ContadorVistaAnuncio
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
     * @return ContadorVistaAnuncio
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
     * @return ContadorVistaAnuncio
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
     * @return ContadorVistaAnuncio
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
     * @return ContadorVistaAnuncio
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
     * Set vista
     *
     * @param \YDI\BackendBundle\Entity\Vista $vista
     *
     * @return ContadorVistaAnuncio
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
