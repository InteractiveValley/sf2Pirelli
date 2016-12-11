<?php

namespace YDI\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Telefono
 *
 * @ORM\Table(name="telefono", uniqueConstraints={@ORM\UniqueConstraint(name="unico", columns={"id"})}, indexes={@ORM\Index(name="fk_telefono_usuario1_idx", columns={"id_usuario"})})
 * @ORM\Entity(repositoryClass="YDI\BackendBundle\Repository\TelefonoRepository")
 */
class Telefono {

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
     * @ORM\Column(name="GCMID", type="text", nullable=true)
     */
    private $gcmid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_descarga", type="date", nullable=true)
     */
    private $fechaDescarga;

    /**
     * @var \Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="telefonos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_usuario", referencedColumnName="id")
     * })
     */
    private $usuario;

    /**
     * @var \Booolean
     *
     * @ORM\Column(name="registroborrado", type="boolean", nullable=true)
     */
    private $registroBorrado = false;
	
    /**
     * @var \Booolean
     *
     * @ORM\Column(name="inactivo", type="boolean", nullable=true)
     */
    private $inactivo = false;

    /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     *
     * @ORM\OneToMany(targetEntity="ContadoresDispositivo", mappedBy="telefono")
     */
    private $contadoresDispositivo;

    /**
     * @var \Booolean
     *
     * @ORM\Column(name="enviar_notificacion", type="boolean", nullable=true)
     */
    private $enviarNotificacion = false;

    /**
     * @var \Booolean
     *
     * @ORM\Column(name="notificacion_anuncio", type="boolean", nullable=true)
     */
    private $notificacionAnuncio = false;

    /**
     * @var \Booolean
     *
     * @ORM\Column(name="notificacion_noticias", type="boolean", nullable=true)
     */
    private $notificacionNoticias = false;

    /**
     * @var \Booolean
     *
     * @ORM\Column(name="notificacion_taremoper", type="boolean", nullable=true)
     */
    private $notificacionTarEmOper = false;

    /**
     * Get codigosNotificacion
     *
     * @return string
     */
    public function getCodigosNotificacion() {
        $arreglo = array();
        if ($this->notificacionAnuncio) {
            $arreglo[] = "11";
        }
        if ($this->notificacionNoticias) {
            $arreglo[] = "13";
        }
        if ($this->notificacionTarEmOper) {
            $arreglo[] = "12";
        }
        return $arreglo;
    }

    /**
     * Get codigosNotificacion
     *
     * @return string
     */
    public function hasCodigosNotificacion() {
        $confirmar = false;
        if ($this->notificacionAnuncio) {
            $confirmar = true;
        }
        if ($this->notificacionNoticias) {
            $confirmar = true;
        }
        if ($this->notificacionTarEmOper) {
            $confirmar = true;
        }
        return $confirmar;
    }

    /**
     * Get codigosNotificacion
     *
     * @return string
     */
    public function removeCodigoNotificacion($codigo) {
        if ($codigo == "11") {
            $this->notificacionAnuncio = false;
        }else if($codigo == "13") {
            $this->notificacionNoticias = false;
        }else if($codigo == "12") {
            $this->notificacionTarEmOper = false;
        }
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->contadoresDispositivo = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return Telefono
     */
    public function setId($id) {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set gcmid
     *
     * @param string $gcmid
     *
     * @return Telefono
     */
    public function setGcmid($gcmid) {
        $this->gcmid = $gcmid;

        return $this;
    }

    /**
     * Get gcmid
     *
     * @return string
     */
    public function getGcmid() {
        return $this->gcmid;
    }

    /**
     * Set fechaDescarga
     *
     * @param \DateTime $fechaDescarga
     *
     * @return Telefono
     */
    public function setFechaDescarga($fechaDescarga) {
        $this->fechaDescarga = $fechaDescarga;

        return $this;
    }

    /**
     * Get fechaDescarga
     *
     * @return \DateTime
     */
    public function getFechaDescarga() {
        return $this->fechaDescarga;
    }

    /**
     * Set usuario
     *
     * @param \YDI\BackendBundle\Entity\Usuario $usuario
     *
     * @return Telefono
     */
    public function setUsuario(\YDI\BackendBundle\Entity\Usuario $usuario) {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \YDI\BackendBundle\Entity\Usuario
     */
    public function getUsuario() {
        return $this->usuario;
    }

    /**
     * Add contadoresDispositivo
     *
     * @param \YDI\BackendBundle\Entity\ContadoresDispositivo $contadoresDispositivo
     *
     * @return Telefono
     */
    public function addContadoresDispositivo(\YDI\BackendBundle\Entity\ContadoresDispositivo $contadoresDispositivo) {
        $this->contadoresDispositivo[] = $contadoresDispositivo;

        return $this;
    }

    /**
     * Remove contadoresDispositivo
     *
     * @param \YDI\BackendBundle\Entity\ContadoresDispositivo $contadoresDispositivo
     */
    public function removeContadoresDispositivo(\YDI\BackendBundle\Entity\ContadoresDispositivo $contadoresDispositivo) {
        $this->contadoresDispositivo->removeElement($contadoresDispositivo);
    }

    /**
     * Get contadoresDispositivo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContadoresDispositivo() {
        return $this->contadoresDispositivo;
    }

    /**
     * Set enviarNotificacion
     *
     * @param boolean $enviarNotificacion
     *
     * @return Telefono
     */
    public function setEnviarNotificacion($enviarNotificacion) {
        $this->enviarNotificacion = $enviarNotificacion;

        return $this;
    }

    /**
     * Get enviarNotificacion
     *
     * @return boolean
     */
    public function getEnviarNotificacion() {
        return $this->enviarNotificacion;
    }

    /**
     * Set inactivo
     *
     * @param boolean $inactivo
     *
     * @return Telefono
     */
    public function setInactivo($inactivo) {
        $this->inactivo = $inactivo;

        return $this;
    }

    /**
     * Get inactivo
     *
     * @return boolean
     */
    public function getInactivo() {
        return $this->inactivo;
    }

    /**
     * Set notificacionAnuncio
     *
     * @param boolean $notificacionAnuncio
     *
     * @return Telefono
     */
    public function setNotificacionAnuncio($notificacionAnuncio) {
        $this->notificacionAnuncio = $notificacionAnuncio;

        return $this;
    }

    /**
     * Get notificacionAnuncio
     *
     * @return boolean
     */
    public function getNotificacionAnuncio() {
        return $this->notificacionAnuncio;
    }

    /**
     * Set notificacionNoticias
     *
     * @param boolean $notificacionNoticias
     *
     * @return Telefono
     */
    public function setNotificacionNoticias($notificacionNoticias) {
        $this->notificacionNoticias = $notificacionNoticias;

        return $this;
    }

    /**
     * Get notificacionNoticias
     *
     * @return boolean
     */
    public function getNotificacionNoticias() {
        return $this->notificacionNoticias;
    }

    /**
     * Set notificacionTarEmOper
     *
     * @param boolean $notificacionTarEmOper
     *
     * @return Telefono
     */
    public function setNotificacionTarEmOper($notificacionTarEmOper) {
        $this->notificacionTarEmOper = $notificacionTarEmOper;

        return $this;
    }

    /**
     * Get notificacionTarEmOper
     *
     * @return boolean
     */
    public function getNotificacionTarEmOper() {
        return $this->notificacionTarEmOper;
    }


    /**
     * Set registroBorrado
     *
     * @param boolean $registroBorrado
     *
     * @return Telefono
     */
    public function setRegistroBorrado($registroBorrado)
    {
        $this->registroBorrado = $registroBorrado;

        return $this;
    }

    /**
     * Get registroBorrado
     *
     * @return boolean
     */
    public function getRegistroBorrado()
    {
        return $this->registroBorrado;
    }
}
