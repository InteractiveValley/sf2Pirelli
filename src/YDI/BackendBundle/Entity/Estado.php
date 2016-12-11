<?php

namespace YDI\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Estado
 *
 * @ORM\Table(name="estado", uniqueConstraints={@ORM\UniqueConstraint(name="indice_unique", columns={"id", "id_pais"})}, indexes={@ORM\Index(name="fk_estados_pais1_idx", columns={"id_pais"})})
 * @ORM\Entity
 */
class Estado
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
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=45, nullable=true)
     */
    private $nombre;

    /**
     * @var \Pais
     *
     * @ORM\ManyToOne(targetEntity="Pais", inversedBy="estados")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_pais", referencedColumnName="id")
     * })
     */
    private $pais;

    /**
     * Bidirectional - One-To-One (INVERSE SIDE)
     *
     * @ORM\OneToMany(targetEntity="Codigop", mappedBy="estado")
     */
    private $codigosPostales;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->codigosPostales = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Estado
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
     * Set pais
     *
     * @param \YDI\BackendBundle\Entity\Pais $pais
     *
     * @return Estado
     */
    public function setPais(\YDI\BackendBundle\Entity\Pais $pais = null)
    {
        $this->pais = $pais;

        return $this;
    }

    /**
     * Get pais
     *
     * @return \YDI\BackendBundle\Entity\Pais
     */
    public function getPais()
    {
        return $this->pais;
    }

    /**
     * Add codigosPostale
     *
     * @param \YDI\BackendBundle\Entity\Codigop $codigosPostale
     *
     * @return Estado
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
}
