<?php

namespace YDI\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TarjetaEmisorOperador
 *
 * @ORM\Table(name="tarjeta_emisor_operador", indexes={@ORM\Index(name="fk_tarjeta_emisor_operador_tipostarjeta1_idx", columns={"id_tipostarjeta"}), @ORM\Index(name="fk_tarjeta_emisor_operador_emisor1_idx", columns={"id_emisor"}), @ORM\Index(name="fk_tarjeta_emisor_operador_operadorfinanciero1_idx", columns={"id_operadorfinanciero"})})
 * @ORM\Entity
 */
class TarjetaEmisorOperador
{
    /**
     * @var string
     *
     * @ORM\Column(name="url_ld", type="string", length=100, nullable=true)
     */
    private $urlLd;

    /**
     * @var string
     *
     * @ORM\Column(name="url_hd", type="string", length=100, nullable=true)
     */
    private $urlHd;

    /**
     * @var \Emisor
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Emisor", inversedBy="tarjetasEmisorOperador")
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
     * @ORM\OneToOne(targetEntity="OperadorFinanciero", inversedBy="tarjetasEmisorOperador")
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
     * @ORM\OneToOne(targetEntity="TiposTarjeta", inversedBy="tarjetasEmisorOperador")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_tipostarjeta", referencedColumnName="id")
     * })
     */
    private $tiposTarjeta;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Usuario", mappedBy="tiposTarjeta")
     */
    private $usuario;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Anuncio", mappedBy="tiposTarjeta")
     */
    private $anuncio;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_actualizacion", type="datetime", nullable=true)
     */
    private $fechaActualizacion;
    
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->usuario = new \Doctrine\Common\Collections\ArrayCollection();
        $this->anuncio = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set urlLd
     *
     * @param string $urlLd
     *
     * @return TarjetaEmisorOperador
     */
    public function setUrlLd($urlLd)
    {
        $this->urlLd = $urlLd;

        return $this;
    }

    /**
     * Get urlLd
     *
     * @return string
     */
    public function getUrlLd()
    {
        return $this->urlLd;
    }

    /**
     * Set urlHd
     *
     * @param string $urlHd
     *
     * @return TarjetaEmisorOperador
     */
    public function setUrlHd($urlHd)
    {
        $this->urlHd = $urlHd;

        return $this;
    }

    /**
     * Get urlHd
     *
     * @return string
     */
    public function getUrlHd()
    {
        return $this->urlHd;
    }

    /**
     * Set emisor
     *
     * @param \YDI\BackendBundle\Entity\Emisor $emisor
     *
     * @return TarjetaEmisorOperador
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
     * @return TarjetaEmisorOperador
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
     * @return TarjetaEmisorOperador
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

    /**
     * Add usuario
     *
     * @param \YDI\BackendBundle\Entity\Usuario $usuario
     *
     * @return TarjetaEmisorOperador
     */
    public function addUsuario(\YDI\BackendBundle\Entity\Usuario $usuario)
    {
        $this->usuario[] = $usuario;

        return $this;
    }

    /**
     * Remove usuario
     *
     * @param \YDI\BackendBundle\Entity\Usuario $usuario
     */
    public function removeUsuario(\YDI\BackendBundle\Entity\Usuario $usuario)
    {
        $this->usuario->removeElement($usuario);
    }

    /**
     * Get usuario
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Add anuncio
     *
     * @param \YDI\BackendBundle\Entity\Anuncio $anuncio
     *
     * @return TarjetaEmisorOperador
     */
    public function addAnuncio(\YDI\BackendBundle\Entity\Anuncio $anuncio)
    {
        $this->anuncio[] = $anuncio;

        return $this;
    }

    /**
     * Remove anuncio
     *
     * @param \YDI\BackendBundle\Entity\Anuncio $anuncio
     */
    public function removeAnuncio(\YDI\BackendBundle\Entity\Anuncio $anuncio)
    {
        $this->anuncio->removeElement($anuncio);
    }

    /**
     * Get anuncio
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnuncio()
    {
        return $this->anuncio;
    }

    /**
     * Set fechaActualizacion
     *
     * @param \DateTime $fechaActualizacion
     *
     * @return TarjetaEmisorOperador
     */
    public function setFechaActualizacion($fechaActualizacion)
    {
        $this->fechaActualizacion = $fechaActualizacion;

        return $this;
    }

    /**
     * Get fechaActualizacion
     *
     * @return \DateTime
     */
    public function getFechaActualizacion()
    {
        return $this->fechaActualizacion;
    }
}
