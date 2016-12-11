<?php

namespace YDI\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Anuncio
 *
 * @ORM\Table(name="anuncio", uniqueConstraints={@ORM\UniqueConstraint(name="unico", columns={"id"})}, indexes={@ORM\Index(name="fk_anuncio_establecimiento1_idx", columns={"id_establecimiento"})})
 * @ORM\Entity(repositoryClass="YDI\BackendBundle\Repository\AnuncioRepository")
 */
class Anuncio
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_carga", type="datetime", nullable=true)
     */
    private $fechaCarga;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_hora_inicio", type="datetime", nullable=true)
     */
    private $fechaHoraInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_hora_terminacion", type="datetime", nullable=true)
     */
    private $fechaHoraTerminacion;

    /**
     * @var string
     *
     * @ORM\Column(name="url_hd", type="string", length=45, nullable=true)
     */
    private $urlHd;

    /**
     * @var string
     *
     * @ORM\Column(name="url_ld", type="string", length=45, nullable=true)
     */
    private $urlLd;

    /**
     * @var integer
     *
     * @ORM\Column(name="anuncio_rapido_flag", type="integer", nullable=true)
     */
    private $anuncioRapidoFlag = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="tamanio_flag", type="integer", nullable=true)
     */
    private $tamanioFlag;

    /**
     * @var integer
     *
     * @ORM\Column(name="valor_puntos", type="integer", nullable=true)
     */
    private $valorPuntos;

    /**
     * @var integer
     *
     * @ORM\Column(name="valor_puntos_rechazo", type="integer", nullable=true)
     */
    private $valorPuntosRechazo;

    /**
     * @var string
     *
     * @ORM\Column(name="numerocupon", type="string", length=6, nullable=true)
     */
    private $numerocupon;

    /**
     * @var integer
     *
     * @ORM\Column(name="leyendacupon", type="string",length=120, nullable=true)
     */
    private $leyendacupon;

    /**
     * @var integer
     *
     * @ORM\Column(name="numerousoscupon", type="integer", nullable=true)
     */
    private $numerousoscupon;

    /**
     * @var integer
     *
     * @ORM\Column(name="registroborrado", type="integer", nullable=true)
     */
    private $registroborrado;
    
    /**
     * @var \Establecimiento
     *
     * @ORM\ManyToOne(targetEntity="Establecimiento", inversedBy="anuncios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_establecimiento", referencedColumnName="id")
     * })
     */
    private $establecimiento;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Codigop", mappedBy="anuncio")
     */
    private $codigosPostales;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="SubnivelPreferencia", mappedBy="anuncio")
     */
    private $subnivelPreferencia;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="TarjetaEmisorOperador", inversedBy="anuncio")
     * @ORM\JoinTable(name="tarjeta_emisoroperador_anuncio",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_anuncio", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_tipostarjeta", referencedColumnName="id_tipostarjeta"),
     *     @ORM\JoinColumn(name="id_emisor", referencedColumnName="id_emisor"),
     *     @ORM\JoinColumn(name="id_operadorfinanciero", referencedColumnName="id_operadorfinanciero")
     *   }
     * )
     */
    private $tiposTarjeta;

    /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     *
     * @ORM\OneToMany(targetEntity="Condiciones", mappedBy="anuncio")
     */
    private $condiciones;

    /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     *
     * @ORM\OneToMany(targetEntity="PalabrasClave", mappedBy="anuncio")
     */
    private $palabrasClave;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Rechazos", mappedBy="anuncio")
     */
    private $rechazos;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="imagen_pantalla_1", type="integer", nullable=true)
     */
    private $imagenPantalla;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->codigosPostales = new \Doctrine\Common\Collections\ArrayCollection();
        $this->subnivelPreferencia = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tiposTarjeta = new \Doctrine\Common\Collections\ArrayCollection();
        $this->condiciones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->palabrasClave = new \Doctrine\Common\Collections\ArrayCollection();
        $this->rechazos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return Anuncio
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
     * Set fechaCarga
     *
     * @param \DateTime $fechaCarga
     *
     * @return Anuncio
     */
    public function setFechaCarga($fechaCarga)
    {
        $this->fechaCarga = $fechaCarga;

        return $this;
    }

    /**
     * Get fechaCarga
     *
     * @return \DateTime
     */
    public function getFechaCarga()
    {
        return $this->fechaCarga;
    }

    /**
     * Set fechaHoraInicio
     *
     * @param \DateTime $fechaHoraInicio
     *
     * @return Anuncio
     */
    public function setFechaHoraInicio($fechaHoraInicio)
    {
        $this->fechaHoraInicio = $fechaHoraInicio;

        return $this;
    }

    /**
     * Get fechaHoraInicio
     *
     * @return \DateTime
     */
    public function getFechaHoraInicio()
    {
        return $this->fechaHoraInicio;
    }

    /**
     * Set fechaHoraTerminacion
     *
     * @param \DateTime $fechaHoraTerminacion
     *
     * @return Anuncio
     */
    public function setFechaHoraTerminacion($fechaHoraTerminacion)
    {
        $this->fechaHoraTerminacion = $fechaHoraTerminacion;

        return $this;
    }

    /**
     * Get fechaHoraTerminacion
     *
     * @return \DateTime
     */
    public function getFechaHoraTerminacion()
    {
        return $this->fechaHoraTerminacion;
    }

    /**
     * Set urlHd
     *
     * @param string $urlHd
     *
     * @return Anuncio
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
     * Set urlLd
     *
     * @param string $urlLd
     *
     * @return Anuncio
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
     * Set anuncioRapidoFlag
     *
     * @param integer $anuncioRapidoFlag
     *
     * @return Anuncio
     */
    public function setAnuncioRapidoFlag($anuncioRapidoFlag)
    {
        $this->anuncioRapidoFlag = $anuncioRapidoFlag;

        return $this;
    }

    /**
     * Get anuncioRapidoFlag
     *
     * @return integer
     */
    public function getAnuncioRapidoFlag()
    {
        return $this->anuncioRapidoFlag;
    }

    /**
     * Set tamanioFlag
     *
     * @param integer $tamanioFlag
     *
     * @return Anuncio
     */
    public function setTamanioFlag($tamanioFlag)
    {
        $this->tamanioFlag = $tamanioFlag;

        return $this;
    }

    /**
     * Get tamanioFlag
     *
     * @return integer
     */
    public function getTamanioFlag()
    {
        return $this->tamanioFlag;
    }

    /**
     * Set valorPuntos
     *
     * @param integer $valorPuntos
     *
     * @return Anuncio
     */
    public function setValorPuntos($valorPuntos)
    {
        $this->valorPuntos = $valorPuntos;

        return $this;
    }

    /**
     * Get valorPuntos
     *
     * @return integer
     */
    public function getValorPuntos()
    {
        return $this->valorPuntos;
    }

    /**
     * Set valorPuntosRechazo
     *
     * @param integer $valorPuntosRechazo
     *
     * @return Anuncio
     */
    public function setValorPuntosRechazo($valorPuntosRechazo)
    {
        $this->valorPuntosRechazo = $valorPuntosRechazo;

        return $this;
    }

    /**
     * Get valorPuntosRechazo
     *
     * @return integer
     */
    public function getValorPuntosRechazo()
    {
        return $this->valorPuntosRechazo;
    }

    /**
     * Set numerocupon
     *
     * @param string $numerocupon
     *
     * @return Anuncio
     */
    public function setNumerocupon($numerocupon)
    {
        $this->numerocupon = $numerocupon;

        return $this;
    }

    /**
     * Get numerocupon
     *
     * @return string
     */
    public function getNumerocupon()
    {
        return $this->numerocupon;
    }

    /**
     * Set leyendacupon
     *
     * @param string $leyendacupon
     *
     * @return Anuncio
     */
    public function setLeyendacupon($leyendacupon)
    {
        $this->leyendacupon = $leyendacupon;

        return $this;
    }

    /**
     * Get leyendacupon
     *
     * @return string
     */
    public function getLeyendacupon()
    {
        return $this->leyendacupon;
    }

    /**
     * Set numerousoscupon
     *
     * @param integer $numerousoscupon
     *
     * @return Anuncio
     */
    public function setNumerousoscupon($numerousoscupon)
    {
        $this->numerousoscupon = $numerousoscupon;

        return $this;
    }

    /**
     * Get numerousoscupon
     *
     * @return integer
     */
    public function getNumerousoscupon()
    {
        return $this->numerousoscupon;
    }

    /**
     * Set registroborrado
     *
     * @param integer $registroborrado
     *
     * @return Anuncio
     */
    public function setRegistroborrado($registroborrado)
    {
        $this->registroborrado = $registroborrado;

        return $this;
    }

    /**
     * Get registroborrado
     *
     * @return integer
     */
    public function getRegistroborrado()
    {
        return $this->registroborrado;
    }

    /**
     * Set imagenPantalla
     *
     * @param integer $imagenPantalla
     *
     * @return Anuncio
     */
    public function setImagenPantalla($imagenPantalla)
    {
        $this->imagenPantalla = $imagenPantalla;

        return $this;
    }

    /**
     * Get imagenPantalla
     *
     * @return integer
     */
    public function getImagenPantalla()
    {
        return $this->imagenPantalla;
    }

    /**
     * Set establecimiento
     *
     * @param \YDI\BackendBundle\Entity\Establecimiento $establecimiento
     *
     * @return Anuncio
     */
    public function setEstablecimiento(\YDI\BackendBundle\Entity\Establecimiento $establecimiento = null)
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
     * Add codigosPostale
     *
     * @param \YDI\BackendBundle\Entity\Codigop $codigosPostale
     *
     * @return Anuncio
     */
    public function addCodigosPostale(\YDI\BackendBundle\Entity\Codigop $codigosPostale)
    {
        $this->codigosPostales[] = $codigosPostale;

        return $this;
    }

    /**
     * Remove codigosPostale
     *
     * @param \YDI\BackendBundle\Entity\Codigop $codigosPostale
     */
    public function removeCodigosPostale(\YDI\BackendBundle\Entity\Codigop $codigosPostale)
    {
        $this->codigosPostales->removeElement($codigosPostale);
    }

    /**
     * Get codigosPostales
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCodigosPostales()
    {
        return $this->codigosPostales;
    }

    /**
     * Add subnivelPreferencium
     *
     * @param \YDI\BackendBundle\Entity\SubnivelPreferencia $subnivelPreferencium
     *
     * @return Anuncio
     */
    public function addSubnivelPreferencium(\YDI\BackendBundle\Entity\SubnivelPreferencia $subnivelPreferencium)
    {
        $this->subnivelPreferencia[] = $subnivelPreferencium;

        return $this;
    }

    /**
     * Remove subnivelPreferencium
     *
     * @param \YDI\BackendBundle\Entity\SubnivelPreferencia $subnivelPreferencium
     */
    public function removeSubnivelPreferencium(\YDI\BackendBundle\Entity\SubnivelPreferencia $subnivelPreferencium)
    {
        $this->subnivelPreferencia->removeElement($subnivelPreferencium);
    }

    /**
     * Get subnivelPreferencia
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubnivelPreferencia()
    {
        return $this->subnivelPreferencia;
    }

    /**
     * Add tiposTarjetum
     *
     * @param \YDI\BackendBundle\Entity\TarjetaEmisorOperador $tiposTarjetum
     *
     * @return Anuncio
     */
    public function addTiposTarjetum(\YDI\BackendBundle\Entity\TarjetaEmisorOperador $tiposTarjetum)
    {
        $this->tiposTarjeta[] = $tiposTarjetum;

        return $this;
    }

    /**
     * Remove tiposTarjetum
     *
     * @param \YDI\BackendBundle\Entity\TarjetaEmisorOperador $tiposTarjetum
     */
    public function removeTiposTarjetum(\YDI\BackendBundle\Entity\TarjetaEmisorOperador $tiposTarjetum)
    {
        $this->tiposTarjeta->removeElement($tiposTarjetum);
    }

    /**
     * Get tiposTarjeta
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTiposTarjeta()
    {
        return $this->tiposTarjeta;
    }

    /**
     * Add condicione
     *
     * @param \YDI\BackendBundle\Entity\Condiciones $condicione
     *
     * @return Anuncio
     */
    public function addCondicione(\YDI\BackendBundle\Entity\Condiciones $condicione)
    {
        $this->condiciones[] = $condicione;

        return $this;
    }

    /**
     * Remove condicione
     *
     * @param \YDI\BackendBundle\Entity\Condiciones $condicione
     */
    public function removeCondicione(\YDI\BackendBundle\Entity\Condiciones $condicione)
    {
        $this->condiciones->removeElement($condicione);
    }

    /**
     * Get condiciones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCondiciones()
    {
        return $this->condiciones;
    }

    /**
     * Add palabrasClave
     *
     * @param \YDI\BackendBundle\Entity\PalabrasClave $palabrasClave
     *
     * @return Anuncio
     */
    public function addPalabrasClave(\YDI\BackendBundle\Entity\PalabrasClave $palabrasClave)
    {
        $this->palabrasClave[] = $palabrasClave;

        return $this;
    }

    /**
     * Remove palabrasClave
     *
     * @param \YDI\BackendBundle\Entity\PalabrasClave $palabrasClave
     */
    public function removePalabrasClave(\YDI\BackendBundle\Entity\PalabrasClave $palabrasClave)
    {
        $this->palabrasClave->removeElement($palabrasClave);
    }

    /**
     * Get palabrasClave
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPalabrasClave()
    {
        return $this->palabrasClave;
    }

    /**
     * Add rechazo
     *
     * @param \YDI\BackendBundle\Entity\Rechazos $rechazo
     *
     * @return Anuncio
     */
    public function addRechazo(\YDI\BackendBundle\Entity\Rechazos $rechazo)
    {
        $this->rechazos[] = $rechazo;

        return $this;
    }

    /**
     * Remove rechazo
     *
     * @param \YDI\BackendBundle\Entity\Rechazos $rechazo
     */
    public function removeRechazo(\YDI\BackendBundle\Entity\Rechazos $rechazo)
    {
        $this->rechazos->removeElement($rechazo);
    }

    /**
     * Get rechazos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRechazos()
    {
        return $this->rechazos;
    }
}
