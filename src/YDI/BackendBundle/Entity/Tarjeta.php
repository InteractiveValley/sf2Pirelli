<?php

namespace YDI\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tarjeta
 *
 * @ORM\Table(name="tarjeta")
 * @ORM\Entity(repositoryClass="YDI\BackendBundle\Repository\TarjetaRepository")
 */
class Tarjeta
{
    /**
     * @var \Usuario
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="tarjetas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_usuario", referencedColumnName="id")
     * })
     */
    private $usuario;

    /**
     * @var \Emisor
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\ManyToOne(targetEntity="Emisor", inversedBy="tarjetas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_emisor", referencedColumnName="id")
     * })
     */
    private $emisor;

    /**
     * @var \OperadorFinanciero
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\ManyToOne(targetEntity="OperadorFinanciero", inversedBy="tarjetas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_operadorfinanciero", referencedColumnName="id")
     * })
     */
    private $operadorFinanciero;

    /**
     * @var \TiposTarjeta
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\ManyToOne(targetEntity="TiposTarjeta", inversedBy="tarjetas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_tipostarjeta", referencedColumnName="id")
     * })
     */
    private $tiposTarjeta;

    /**
     * @var integer
     *
     * @ORM\Column(name="ultimos_digitos", type="smallint")
     */
    private $ultimosDigitos;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_vencimiento", type="date")
     */
    private $fechaVencimiento;



    /**
     * Set ultimosDigitos
     *
     * @param integer $ultimosDigitos
     *
     * @return Tarjeta
     */
    public function setUltimosDigitos($ultimosDigitos)
    {
        $this->ultimosDigitos = $ultimosDigitos;

        return $this;
    }

    /**
     * Get ultimosDigitos
     *
     * @return integer
     */
    public function getUltimosDigitos()
    {
        return $this->ultimosDigitos;
    }

    /**
     * Set fechaVencimiento
     *
     * @param \DateTime $fechaVencimiento
     *
     * @return Tarjeta
     */
    public function setFechaVencimiento($fechaVencimiento)
    {
        $this->fechaVencimiento = $fechaVencimiento;

        return $this;
    }

    /**
     * Get fechaVencimiento
     *
     * @return \DateTime
     */
    public function getFechaVencimiento()
    {
        return $this->fechaVencimiento;
    }

    /**
     * Set usuario
     *
     * @param \YDI\BackendBundle\Entity\Usuario $usuario
     *
     * @return Tarjeta
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
     * Set emisor
     *
     * @param \YDI\BackendBundle\Entity\Emisor $emisor
     *
     * @return Tarjeta
     */
    public function setEmisor(\YDI\BackendBundle\Entity\Emisor $emisor)
    {
        $this->emisor = $emisor;

        return $this;
    }

    /**
     * Get emisor
     *
     * @return \YDI\BackendBundle\Entity\Emisor
     */
    public function getEmisor()
    {
        return $this->emisor;
    }

    /**
     * Set operadorFinanciero
     *
     * @param \YDI\BackendBundle\Entity\OperadorFinanciero $operadorFinanciero
     *
     * @return Tarjeta
     */
    public function setOperadorFinanciero(\YDI\BackendBundle\Entity\OperadorFinanciero $operadorFinanciero)
    {
        $this->operadorFinanciero = $operadorFinanciero;

        return $this;
    }

    /**
     * Get operadorFinanciero
     *
     * @return \YDI\BackendBundle\Entity\OperadorFinanciero
     */
    public function getOperadorFinanciero()
    {
        return $this->operadorFinanciero;
    }

    /**
     * Set tiposTarjeta
     *
     * @param \YDI\BackendBundle\Entity\TiposTarjeta $tiposTarjeta
     *
     * @return Tarjeta
     */
    public function setTiposTarjeta(\YDI\BackendBundle\Entity\TiposTarjeta $tiposTarjeta)
    {
        $this->tiposTarjeta = $tiposTarjeta;

        return $this;
    }

    /**
     * Get tiposTarjeta
     *
     * @return \YDI\BackendBundle\Entity\TiposTarjeta
     */
    public function getTiposTarjeta()
    {
        return $this->tiposTarjeta;
    }
}
