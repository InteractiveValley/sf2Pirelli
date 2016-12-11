<?php

namespace YDI\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Errores
 *
 * @ORM\Table(name="errores")
 * @ORM\Entity
 */
class Errores
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
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime", nullable=false)
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="stacktrace", type="text", length=16777215, nullable=true)
     */
    private $stacktrace;

    /**
     * @var string
     *
     * @ORM\Column(name="brand", type="string", length=20, nullable=true)
     */
    private $brand;

    /**
     * @var string
     *
     * @ORM\Column(name="device", type="string", length=20, nullable=true)
     */
    private $device;

    /**
     * @var string
     *
     * @ORM\Column(name="model", type="string", length=50, nullable=true)
     */
    private $model;

    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=20, nullable=true)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="product", type="string", length=20, nullable=true)
     */
    private $product;

    /**
     * @var string
     *
     * @ORM\Column(name="sdk", type="string", length=20, nullable=true)
     */
    private $sdk;

    /**
     * @var string
     *
     * @ORM\Column(name="lanzamiento", type="string", length=20, nullable=true)
     */
    private $release;

    /**
     * @var string
     *
     * @ORM\Column(name="incremental", type="string", length=50, nullable=true)
     */
    private $incremental;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="ydiversion", type="string", length=25, nullable=true)
     */
    private $ydiversion;
    

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return Errores
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
     * Set stacktrace
     *
     * @param string $stacktrace
     *
     * @return Errores
     */
    public function setStacktrace($stacktrace)
    {
        $this->stacktrace = $stacktrace;

        return $this;
    }

    /**
     * Get stacktrace
     *
     * @return string
     */
    public function getStacktrace()
    {
        return $this->stacktrace;
    }

    /**
     * Set brand
     *
     * @param string $brand
     *
     * @return Errores
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand
     *
     * @return string
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set device
     *
     * @param string $device
     *
     * @return Errores
     */
    public function setDevice($device)
    {
        $this->device = $device;

        return $this;
    }

    /**
     * Get device
     *
     * @return string
     */
    public function getDevice()
    {
        return $this->device;
    }

    /**
     * Set model
     *
     * @param string $model
     *
     * @return Errores
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set id
     *
     * @param string $id
     *
     * @return Errores
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set product
     *
     * @param string $product
     *
     * @return Errores
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return string
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set sdk
     *
     * @param string $sdk
     *
     * @return Errores
     */
    public function setSdk($sdk)
    {
        $this->sdk = $sdk;

        return $this;
    }

    /**
     * Get sdk
     *
     * @return string
     */
    public function getSdk()
    {
        return $this->sdk;
    }

    /**
     * Set release
     *
     * @param string $release
     *
     * @return Errores
     */
    public function setRelease($release)
    {
        $this->release = $release;

        return $this;
    }

    /**
     * Get release
     *
     * @return string
     */
    public function getRelease()
    {
        return $this->release;
    }

    /**
     * Set incremental
     *
     * @param string $incremental
     *
     * @return Errores
     */
    public function setIncremental($incremental)
    {
        $this->incremental = $incremental;

        return $this;
    }

    /**
     * Get incremental
     *
     * @return string
     */
    public function getIncremental()
    {
        return $this->incremental;
    }

    /**
     * Set usuario
     *
     * @param \YDI\BackendBundle\Entity\Usuario $usuario
     *
     * @return Errores
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
     * Set ydiversion
     *
     * @param string $ydiversion
     *
     * @return Errores
     */
    public function setYdiversion($ydiversion)
    {
        $this->ydiversion = $ydiversion;

        return $this;
    }

    /**
     * Get ydiversion
     *
     * @return string
     */
    public function getYdiversion()
    {
        return $this->ydiversion;
    }
}
