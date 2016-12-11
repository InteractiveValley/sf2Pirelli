<?php

namespace YDI\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SubnivelPreferencia
 *
 * @ORM\Table(name="subnivel_preferencia", indexes={@ORM\Index(name="fk_subnivel_preferencia_Preferencias1_idx", columns={"id_preferencias"})})
 * @ORM\Entity
 */
class SubnivelPreferencia
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
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=45, nullable=true)
     */
    private $nombre;

    /**
     * @var \Preferencias
     *
     * @ORM\ManyToOne(targetEntity="Preferencias", inversedBy="subnivelesPreferencia")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_preferencias", referencedColumnName="id")
     * })
     */
    private $preferencias;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Anuncio", inversedBy="subnivelPreferencia")
     * @ORM\JoinTable(name="preferencias_anuncio",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_subnivel_preferencia", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_anuncio", referencedColumnName="id")
     *   }
     * )
     */
    private $anuncio;
    
    /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     *
     * @ORM\OneToMany(targetEntity="PreferenciasUsuario", mappedBy="subnivelPreferencia")
     */
    private $preferenciasUsuario;
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->anuncio = new \Doctrine\Common\Collections\ArrayCollection();
        $this->preferenciasUsuario = new \Doctrine\Common\Collections\ArrayCollection();
    }
	
	/**
     * Set id
     *
     * @param integer $id
     *
     * @return SubnivelPreferencia
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return SubnivelPreferencia
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
     * Set preferencias
     *
     * @param \YDI\BackendBundle\Entity\Preferencias $preferencias
     *
     * @return SubnivelPreferencia
     */
    public function setPreferencias(\YDI\BackendBundle\Entity\Preferencias $preferencias = null)
    {
        $this->preferencias = $preferencias;

        return $this;
    }

    /**
     * Get preferencias
     *
     * @return \YDI\BackendBundle\Entity\Preferencias
     */
    public function getPreferencias()
    {
        return $this->preferencias;
    }

    /**
     * Add anuncio
     *
     * @param \YDI\BackendBundle\Entity\Anuncio $anuncio
     *
     * @return SubnivelPreferencia
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
     * Add preferenciasUsuario
     *
     * @param \YDI\BackendBundle\Entity\PreferenciasUsuario $preferenciasUsuario
     *
     * @return SubnivelPreferencia
     */
    public function addPreferenciasUsuario(\YDI\BackendBundle\Entity\PreferenciasUsuario $preferenciasUsuario)
    {
        $this->preferenciasUsuario[] = $preferenciasUsuario;

        return $this;
    }

    /**
     * Remove preferenciasUsuario
     *
     * @param \YDI\BackendBundle\Entity\PreferenciasUsuario $preferenciasUsuario
     */
    public function removePreferenciasUsuario(\YDI\BackendBundle\Entity\PreferenciasUsuario $preferenciasUsuario)
    {
        $this->preferenciasUsuario->removeElement($preferenciasUsuario);
    }

    /**
     * Get preferenciasUsuario
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPreferenciasUsuario()
    {
        return $this->preferenciasUsuario;
    }
}
