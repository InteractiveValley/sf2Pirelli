<?php

namespace YDI\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Noticia
 *
 * @ORM\Table(name="noticias")
 * @ORM\Entity
 */
class Noticia
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=180)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="url_hd", type="string", length=45)
     */
    private $urlHd;

    /**
     * @var string
     *
     * @ORM\Column(name="url_ld", type="string", length=45)
     */
    private $urlLd;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_hora_inicio", type="datetime")
     */
    private $fechaHoraInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_hora_terminacion", type="datetime")
     */
    private $fechaHoraTerminacion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_actualizacion", type="datetime")
     */
    private $fechaActualizacion;   

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return Noticia
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
     * @return Noticia
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
     * Set urlHd
     *
     * @param string $urlHd
     *
     * @return Noticia
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
     * @return Noticia
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
     * Set fechaHoraInicio
     *
     * @param \DateTime $fechaHoraInicio
     *
     * @return Noticia
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
     * @return Noticia
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
     * Set fechaActualizacion
     *
     * @param \DateTime $fechaActualizacion
     *
     * @return Noticia
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
