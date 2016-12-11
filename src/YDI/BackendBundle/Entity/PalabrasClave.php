<?php

namespace YDI\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PalabrasClave
 *
 * @ORM\Table(name="palabras_clave", indexes={@ORM\Index(name="fk_palabras_clave_anuncio1_idx", columns={"id_anuncio"})})
 * @ORM\Entity
 */
class PalabrasClave
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
     * @ORM\Column(name="palabra_cve", type="string", length=25, nullable=true)
     */
    private $palabraClave;

    /**
     * @var \Anuncio
     *
     * @ORM\ManyToOne(targetEntity="Anuncio", inversedBy="palabrasClave")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_anuncio", referencedColumnName="id")
     * })
     */
    private $anuncio;    

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
     * Set palabraClave
     *
     * @param string $palabraClave
     *
     * @return PalabrasClave
     */
    public function setPalabraClave($palabraClave)
    {
        $this->palabraClave = $palabraClave;

        return $this;
    }

    /**
     * Get palabraClave
     *
     * @return string
     */
    public function getPalabraClave()
    {
        return $this->palabraClave;
    }

    /**
     * Set anuncio
     *
     * @param \YDI\BackendBundle\Entity\Anuncio $anuncio
     *
     * @return PalabrasClave
     */
    public function setAnuncio(\YDI\BackendBundle\Entity\Anuncio $anuncio = null)
    {
        $this->anuncio = $anuncio;

        return $this;
    }

    /**
     * Get anuncio
     *
     * @return \YDI\BackendBundle\Entity\Anuncio
     */
    public function getAnuncio()
    {
        return $this->anuncio;
    }
}
