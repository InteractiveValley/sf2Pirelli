<?php

namespace YDI\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Codigop
 *
 * @ORM\Table(name="codigop", indexes={@ORM\Index(name="fk_codigop_estado1_idx", columns={"id_estado"})})
 * @ORM\Entity
 */
class Codigop
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \Estado
     * 
     * @ORM\ManyToOne(targetEntity="Estado", inversedBy="codigosPostales")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_estado", referencedColumnName="id")
     * })
     */
    private $estado;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Anuncio", inversedBy="codigosPostales")
     * @ORM\JoinTable(name="codigop_anuncio",
     *   joinColumns={
     *     @ORM\JoinColumn(name="codigop_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="anuncio_id", referencedColumnName="id")
     *   }
     * )
     */
    private $anuncio;

    /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     *
     * @ORM\OneToMany(targetEntity="Usuario", mappedBy="codigoPostal")
     */
    private $usuarios;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->anuncio = new \Doctrine\Common\Collections\ArrayCollection();
        $this->usuarios = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set estado
     *
     * @param \YDI\BackendBundle\Entity\Estado $estado
     *
     * @return Codigop
     */
    public function setEstado(\YDI\BackendBundle\Entity\Estado $estado = null)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return \YDI\BackendBundle\Entity\Estado
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Add anuncio
     *
     * @param \YDI\BackendBundle\Entity\Anuncio $anuncio
     *
     * @return Codigop
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
     * Add usuario
     *
     * @param \YDI\BackendBundle\Entity\Usuario $usuario
     *
     * @return Codigop
     */
    public function addUsuario(\YDI\BackendBundle\Entity\Usuario $usuario)
    {
        $this->usuarios[] = $usuario;

        return $this;
    }

    /**
     * Remove usuario
     *
     * @param \YDI\BackendBundle\Entity\Usuario $usuario
     */
    public function removeUsuario(\YDI\BackendBundle\Entity\Usuario $usuario)
    {
        $this->usuarios->removeElement($usuario);
    }

    /**
     * Get usuarios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsuarios()
    {
        return $this->usuarios;
    }
}
