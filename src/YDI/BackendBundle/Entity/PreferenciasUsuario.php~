<?php

namespace YDI\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PreferenciasUsuario
 *
 * @ORM\Table(name="preferencias_usuario", indexes={@ORM\Index(name="fk_preferencias_usuario_subnivel_preferencia1_idx", columns={"id_subnivel_preferencia"})})
 * @ORM\Entity
 */
class PreferenciasUsuario
{
    /**
     * @var \Usuario
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="preferenciasUsuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_usuario", referencedColumnName="id")
     * })
     */
    private $usuario;

    /**
     * @var \SubnivelPreferencia
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="SubnivelPreferencia", inversedBy="preferenciasUsuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_subnivel_preferencia", referencedColumnName="id")
     * })
     */
    private $subnivelPreferencia;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="activo_flag", type="boolean")
     */
    private $activoFlag = true;
    
    
    
    public function __construct() {
        $this->activoFlag;
    }


    /**
     * Set activoFlag
     *
     * @param boolean $activoFlag
     *
     * @return PreferenciasUsuario
     */
    public function setActivoFlag($activoFlag)
    {
        $this->activoFlag = $activoFlag;

        return $this;
    }

    /**
     * Get activoFlag
     *
     * @return boolean
     */
    public function getActivoFlag()
    {
        return $this->activoFlag;
    }

    /**
     * Set usuario
     *
     * @param \YDI\BackendBundle\Entity\Usuario $usuario
     *
     * @return PreferenciasUsuario
     */
    public function setUsuario(\YDI\BackendBundle\Entity\Usuario $usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \YDI\BackendBundle\Entity\Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set subnivelPreferencia
     *
     * @param \YDI\BackendBundle\Entity\SubnivelPrefencia $subnivelPreferencia
     *
     * @return PreferenciasUsuario
     */
    public function setSubnivelPreferencia(\YDI\BackendBundle\Entity\SubnivelPreferencia $subnivelPreferencia)
    {
        $this->subnivelPreferencia = $subnivelPreferencia;

        return $this;
    }

    /**
     * Get subnivelPreferencia
     *
     * @return \YDI\BackendBundle\Entity\SubnivelPreferencia
     */
    public function getSubnivelPreferencia()
    {
        return $this->subnivelPreferencia;
    }
}
