<?php

namespace YDI\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GooglePlaces
 *
 * @ORM\Table(name="google_places", indexes={@ORM\Index(name="fk_google_places_establecimiento1", columns={"id_establecimiento"})})
 * @ORM\Entity
 */
class GooglePlaces
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
     * @var \Establecimiento
     *
     * @ORM\ManyToOne(targetEntity="Establecimiento", inversedBy="googlePlaces")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_establecimiento", referencedColumnName="id")
     * })
     */
    private $establecimiento;
    
    /**
     * @var string
     *
     * @ORM\Column(name="id_places", type="string", length=45, nullable=true)
     */
    private $places;

    /**
     * @var float
     *
     * @ORM\Column(name="latitud", type="float", precision=10, scale=0, nullable=true)
     */
    private $latitud;

    /**
     * @var float
     *
     * @ORM\Column(name="longitud", type="float", precision=10, scale=0, nullable=true)
     */
    private $longitud;



    /**
     * Set places
     *
     * @param string $places
     *
     * @return GooglePlaces
     */
    public function setPlaces($places)
    {
        $this->places = $places;

        return $this;
    }

    /**
     * Get places
     *
     * @return string
     */
    public function getPlaces()
    {
        return $this->places;
    }

    /**
     * Set latitud
     *
     * @param float $latitud
     *
     * @return GooglePlaces
     */
    public function setLatitud($latitud)
    {
        $this->latitud = $latitud;

        return $this;
    }

    /**
     * Get latitud
     *
     * @return float
     */
    public function getLatitud()
    {
        return $this->latitud;
    }

    /**
     * Set longitud
     *
     * @param float $longitud
     *
     * @return GooglePlaces
     */
    public function setLongitud($longitud)
    {
        $this->longitud = $longitud;

        return $this;
    }

    /**
     * Get longitud
     *
     * @return float
     */
    public function getLongitud()
    {
        return $this->longitud;
    }

    /**
     * Set establecimiento
     *
     * @param \YDI\BackendBundle\Entity\Establecimiento $establecimiento
     *
     * @return GooglePlaces
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
