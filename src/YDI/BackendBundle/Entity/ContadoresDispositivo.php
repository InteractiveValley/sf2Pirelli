<?php

namespace YDI\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContadoresDispositivo
 *
 * @ORM\Table(name="contadores_dispositivo", indexes={@ORM\Index(name="fk_contadores_dispositivo_vista1_idx", columns={"id_vista"}), @ORM\Index(name="fk_contadores_dispositivo_establecimiento1_idx", columns={"id_establecimiento"}), @ORM\Index(name="fk_contadores_dispositivo_telefono1_idx", columns={"id_telefono"})})
 * @ORM\Entity
 */
class ContadoresDispositivo
{
    /**
     * @var \Establecimiento
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\ManyToOne(targetEntity="Establecimiento", inversedBy="contadoresDispositivo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_establecimiento", referencedColumnName="id")
     * })
     */
    private $establecimiento;

    /**
     * @var \Telefono
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\ManyToOne(targetEntity="Telefono", inversedBy="contadoresDispositivo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_telefono", referencedColumnName="id")
     * })
     */
    private $telefono;

    /**
     * @var \Vista
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\ManyToOne(targetEntity="Vista", inversedBy="contadoresDispositivo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_vista", referencedColumnName="id")
     * })
     */
    private $vista;
    
    /**
     * @var string
     *
     * @ORM\Column(name="contador", type="text", length=16777215, nullable=true)
     */
    private $contador;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_dia", type="date", nullable=true)
     */
    private $fechaDia;

    /**
     * Set contador
     *
     * @param string $contador
     *
     * @return ContadoresDispositivo
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
     * Set fechaDia
     *
     * @param \DateTime $fechaDia
     *
     * @return ContadoresDispositivo
     */
    public function setFechaDia($fechaDia)
    {
        $this->fechaDia = $fechaDia;

        return $this;
    }

    /**
     * Get fechaDia
     *
     * @return \DateTime
     */
    public function getFechaDia()
    {
        return $this->fechaDia;
    }

    /**
     * Set establecimiento
     *
     * @param \YDI\BackendBundle\Entity\Establecimiento $establecimiento
     *
     * @return ContadoresDispositivo
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
     * Set telefono
     *
     * @param \YDI\BackendBundle\Entity\Telefono $telefono
     *
     * @return ContadoresDispositivo
     */
    public function setTelefono(\YDI\BackendBundle\Entity\Telefono $telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return \YDI\BackendBundle\Entity\Telefono
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set vista
     *
     * @param \YDI\BackendBundle\Entity\Vista $vista
     *
     * @return ContadoresDispositivo
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
