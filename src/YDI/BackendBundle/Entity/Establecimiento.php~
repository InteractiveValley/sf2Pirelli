<?php

namespace YDI\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Establecimiento
 *
 * @ORM\Table(name="establecimiento")
 * @ORM\Entity
 */
class Establecimiento
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="clavegrupo", type="string", length=10, nullable=true)
     */
    private $clavegrupo;

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
     * @var integer
     *
     * @ORM\Column(name="tipo_logo", type="integer", nullable=true)
     */
    private $tipoLogo;

    /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     *
     * @ORM\OneToMany(targetEntity="Anuncio", mappedBy="establecimiento")
     */
    private $anuncios;

    /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     *
     * @ORM\OneToMany(targetEntity="ContadoresDispositivo", mappedBy="establecimiento")
     */
    private $contadoresDispositivo;

    /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     *
     * @ORM\OneToMany(targetEntity="GooglePlaces", mappedBy="establecimiento")
     */
    private $googlePlaces;

    /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     *
     * @ORM\OneToMany(targetEntity="Premios", mappedBy="establecimiento")
     */
    private $premios;

    /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     *
     * @ORM\OneToMany(targetEntity="Socios", mappedBy="establecimiento")
     */
    private $socios;
    
    /**
     * @var \Pais
     *
     * @ORM\ManyToOne(targetEntity="GrupoEstablecimiento", inversedBy="establecimientos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_grupo_establecimiento", referencedColumnName="id")
     * })
     */
    private $grupoEstablecimiento;
    
    /**
     * @var string
     *
     * @ORM\Column(name="nombre_pantalla", type="string", length=20, nullable=true)
     */
    private $nombrePantalla;
    
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
        $this->anuncios = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contadoresDispositivo = new \Doctrine\Common\Collections\ArrayCollection();
        $this->googlePlaces = new \Doctrine\Common\Collections\ArrayCollection();
        $this->premios = new \Doctrine\Common\Collections\ArrayCollection();
        $this->socios = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Establecimiento
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set clavegrupo
     *
     * @param string $clavegrupo
     *
     * @return Establecimiento
     */
    public function setClavegrupo($clavegrupo)
    {
        $this->clavegrupo = $clavegrupo;

        return $this;
    }

    /**
     * Get clavegrupo
     *
     * @return string
     */
    public function getClavegrupo()
    {
        return $this->clavegrupo;
    }

    /**
     * Set urlLd
     *
     * @param string $urlLd
     *
     * @return Establecimiento
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
     * @return Establecimiento
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
     * Set tipoLogo
     *
     * @param integer $tipoLogo
     *
     * @return Establecimiento
     */
    public function setTipoLogo($tipoLogo)
    {
        $this->tipoLogo = $tipoLogo;

        return $this;
    }

    /**
     * Get tipoLogo
     *
     * @return integer
     */
    public function getTipoLogo()
    {
        return $this->tipoLogo;
    }

    /**
     * Set nombrePantalla
     *
     * @param string $nombrePantalla
     *
     * @return Establecimiento
     */
    public function setNombrePantalla($nombrePantalla)
    {
        $this->nombrePantalla = $nombrePantalla;

        return $this;
    }

    /**
     * Get nombrePantalla
     *
     * @return string
     */
    public function getNombrePantalla()
    {
        return $this->nombrePantalla;
    }

    /**
     * Add anuncio
     *
     * @param \YDI\BackendBundle\Entity\Anuncio $anuncio
     *
     * @return Establecimiento
     */
    public function addAnuncio(\YDI\BackendBundle\Entity\Anuncio $anuncio)
    {
        $this->anuncios[] = $anuncio;

        return $this;
    }

    /**
     * Remove anuncio
     *
     * @param \YDI\BackendBundle\Entity\Anuncio $anuncio
     */
    public function removeAnuncio(\YDI\BackendBundle\Entity\Anuncio $anuncio)
    {
        $this->anuncios->removeElement($anuncio);
    }

    /**
     * Get anuncios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnuncios()
    {
        return $this->anuncios;
    }

    /**
     * Add contadoresDispositivo
     *
     * @param \YDI\BackendBundle\Entity\ContadoresDispositivo $contadoresDispositivo
     *
     * @return Establecimiento
     */
    public function addContadoresDispositivo(\YDI\BackendBundle\Entity\ContadoresDispositivo $contadoresDispositivo)
    {
        $this->contadoresDispositivo[] = $contadoresDispositivo;

        return $this;
    }

    /**
     * Remove contadoresDispositivo
     *
     * @param \YDI\BackendBundle\Entity\ContadoresDispositivo $contadoresDispositivo
     */
    public function removeContadoresDispositivo(\YDI\BackendBundle\Entity\ContadoresDispositivo $contadoresDispositivo)
    {
        $this->contadoresDispositivo->removeElement($contadoresDispositivo);
    }

    /**
     * Get contadoresDispositivo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContadoresDispositivo()
    {
        return $this->contadoresDispositivo;
    }

    /**
     * Add googlePlace
     *
     * @param \YDI\BackendBundle\Entity\GooglePlaces $googlePlace
     *
     * @return Establecimiento
     */
    public function addGooglePlace(\YDI\BackendBundle\Entity\GooglePlaces $googlePlace)
    {
        $this->googlePlaces[] = $googlePlace;

        return $this;
    }

    /**
     * Remove googlePlace
     *
     * @param \YDI\BackendBundle\Entity\GooglePlaces $googlePlace
     */
    public function removeGooglePlace(\YDI\BackendBundle\Entity\GooglePlaces $googlePlace)
    {
        $this->googlePlaces->removeElement($googlePlace);
    }

    /**
     * Get googlePlaces
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGooglePlaces()
    {
        return $this->googlePlaces;
    }

    /**
     * Add premio
     *
     * @param \YDI\BackendBundle\Entity\Premios $premio
     *
     * @return Establecimiento
     */
    public function addPremio(\YDI\BackendBundle\Entity\Premios $premio)
    {
        $this->premios[] = $premio;

        return $this;
    }

    /**
     * Remove premio
     *
     * @param \YDI\BackendBundle\Entity\Premios $premio
     */
    public function removePremio(\YDI\BackendBundle\Entity\Premios $premio)
    {
        $this->premios->removeElement($premio);
    }

    /**
     * Get premios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPremios()
    {
        return $this->premios;
    }

    /**
     * Add socio
     *
     * @param \YDI\BackendBundle\Entity\Socios $socio
     *
     * @return Establecimiento
     */
    public function addSocio(\YDI\BackendBundle\Entity\Socios $socio)
    {
        $this->socios[] = $socio;

        return $this;
    }

    /**
     * Remove socio
     *
     * @param \YDI\BackendBundle\Entity\Socios $socio
     */
    public function removeSocio(\YDI\BackendBundle\Entity\Socios $socio)
    {
        $this->socios->removeElement($socio);
    }

    /**
     * Get socios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSocios()
    {
        return $this->socios;
    }

    /**
     * Set grupoEstablecimiento
     *
     * @param \YDI\BackendBundle\Entity\GrupoEstablecimiento $grupoEstablecimiento
     *
     * @return Establecimiento
     */
    public function setGrupoEstablecimiento(\YDI\BackendBundle\Entity\GrupoEstablecimiento $grupoEstablecimiento = null)
    {
        $this->grupoEstablecimiento = $grupoEstablecimiento;

        return $this;
    }

    /**
     * Get grupoEstablecimiento
     *
     * @return \YDI\BackendBundle\Entity\GrupoEstablecimiento
     */
    public function getGrupoEstablecimiento()
    {
        return $this->grupoEstablecimiento;
    }

    /**
     * Set fechaActualizacion
     *
     * @param \DateTime $fechaActualizacion
     *
     * @return Establecimiento
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
