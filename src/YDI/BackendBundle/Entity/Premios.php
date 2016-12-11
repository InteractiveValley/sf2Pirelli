<?php

namespace YDI\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Premios
 *
 * @ORM\Table(name="premios", indexes={@ORM\Index(name="fk_premios_establecimiento1_idx", columns={"id_establecimiento"})})
 * @ORM\Entity
 */
class Premios
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
     * @ORM\Column(name="descripcion", type="string", length=60, nullable=true)
     */
    private $descripcion;

    /**
     * @var integer
     *
     * @ORM\Column(name="valorPuntos", type="integer", nullable=true)
     */
    private $valorpuntos;

    /**
     * @var \Establecimiento
     *
     * @ORM\OneToOne(targetEntity="Establecimiento", inversedBy="premios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_establecimiento", referencedColumnName="id")
     * })
     */
    private $establecimiento;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="numeropremio", type="string", length=6, nullable=true)
     */
    private $numeroPremio;
    


    /**
     * Set id
     *
     * @param integer $id
     *
     * @return Premios
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
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Premios
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set valorpuntos
     *
     * @param integer $valorpuntos
     *
     * @return Premios
     */
    public function setValorpuntos($valorpuntos)
    {
        $this->valorpuntos = $valorpuntos;

        return $this;
    }

    /**
     * Get valorpuntos
     *
     * @return integer
     */
    public function getValorpuntos()
    {
        return $this->valorpuntos;
    }

    /**
     * Set establecimiento
     *
     * @param \YDI\BackendBundle\Entity\Establecimiento $establecimiento
     *
     * @return Premios
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
     * Set numeroPremio
     *
     * @param string $numeroPremio
     *
     * @return Premios
     */
    public function setNumeroPremio($numeroPremio)
    {
        $this->numeroPremio = $numeroPremio;

        return $this;
    }

    /**
     * Get numeroPremio
     *
     * @return string
     */
    public function getNumeroPremio()
    {
        return $this->numeroPremio;
    }
}
