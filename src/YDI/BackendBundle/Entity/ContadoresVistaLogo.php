<?php

namespace YDI\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContadoresVistaLogo
 *
 * @ORM\Table(name="contadores_vista_logo", indexes={@ORM\Index(name="fk_contadores_dispositivo_vista1_idx", columns={"id_vista"}), @ORM\Index(name="fk_contadores_dispositivo_establecimiento1_idx", columns={"id_establecimiento"}), @ORM\Index(name="fk_contadores_vista_logo_usuario1_idx", columns={"id_usuario"})})
 * @ORM\Entity
 */
class ContadoresVistaLogo
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
     * @var \Establecimiento
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Establecimiento")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_establecimiento", referencedColumnName="id")
     * })
     */
    private $establecimiento;

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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return ContadoresVistaLogo
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
     * @return ContadoresVistaLogo
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
     * Set establecimiento
     *
     * @param \YDI\BackendBundle\Entity\Establecimiento $establecimiento
     *
     * @return ContadoresVistaLogo
     */
    public function setEstablecimiento(\YDI\BackendBundle\Entity\Establecimiento $establecimiento)
    {
        $this->establecimiento = $establecimiento;

        return $this;
    }

    /**
     * Get establecimiento
     *
     * @return \YDI\BackendBundle\Entity\Establecimiento
     */
    public function getEstablecimiento()
    {
        return $this->establecimiento;
    }

    /**
     * Set vista
     *
     * @param \YDI\BackendBundle\Entity\Vista $vista
     *
     * @return ContadoresVistaLogo
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

    /**
     * Set usuario
     *
     * @param \YDI\BackendBundle\Entity\Usuario $usuario
     *
     * @return ContadoresVistaLogo
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
}
