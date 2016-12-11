<?php

namespace YDI\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Usuario
 *
 * @ORM\Table(name="usuario", uniqueConstraints={@ORM\UniqueConstraint(name="email_UNIQUE", columns={"email"})}, indexes={@ORM\Index(name="fk_usuario_codigop1_idx", columns={"id_codigop"})})
 * @ORM\Entity
 */
class Usuario
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
     * @ORM\Column(name="nombre", type="string", length=60, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=60, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=15, nullable=true)
     */
    private $telefono;
    
    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=250, nullable=true)
     */
    private $url;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_settings", type="date", nullable=true)
     */
    private $fechaSettings;

    /**
     * @var \Codigop
     *
     * @ORM\ManyToOne(targetEntity="Codigop", inversedBy="usuarios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_codigop", referencedColumnName="id")
     * })
     */
    private $codigoPostal;
    
    /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     *
     * @ORM\OneToMany(targetEntity="PreferenciasUsuario", mappedBy="usuario")
     */
    private $preferenciasUsuario;

    /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     *
     * @ORM\OneToMany(targetEntity="Navegacion", mappedBy="usuario")
     */
    private $navegaciones;

    /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     *
     * @ORM\OneToMany(targetEntity="Puntosxusuario", mappedBy="usuario")
     */
    private $puntosXUsuario;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Rechazos", mappedBy="usuario")
     */
    private $rechazos;

    /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     *
     * @ORM\OneToMany(targetEntity="Telefono", mappedBy="usuario")
     */
    private $telefonos;
    
    /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     *
     * @ORM\OneToMany(targetEntity="Tarjeta", mappedBy="usuario")
     */
    private $tarjetas;

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->preferenciasUsuario = new \Doctrine\Common\Collections\ArrayCollection();
        $this->navegaciones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->puntosXUsuario = new \Doctrine\Common\Collections\ArrayCollection();
        $this->rechazos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->telefonos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tarjetas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Usuario
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
     * Set email
     *
     * @param string $email
     *
     * @return Usuario
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return Usuario
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Usuario
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set fechaSettings
     *
     * @param \DateTime $fechaSettings
     *
     * @return Usuario
     */
    public function setFechaSettings($fechaSettings)
    {
        $this->fechaSettings = $fechaSettings;

        return $this;
    }

    /**
     * Get fechaSettings
     *
     * @return \DateTime
     */
    public function getFechaSettings()
    {
        return $this->fechaSettings;
    }

    /**
     * Set codigoPostal
     *
     * @param \YDI\BackendBundle\Entity\Codigop $codigoPostal
     *
     * @return Usuario
     */
    public function setCodigoPostal(\YDI\BackendBundle\Entity\Codigop $codigoPostal = null)
    {
        $this->codigoPostal = $codigoPostal;

        return $this;
    }

    /**
     * Get codigoPostal
     *
     * @return \YDI\BackendBundle\Entity\Codigop
     */
    public function getCodigoPostal()
    {
        return $this->codigoPostal;
    }

    /**
     * Add preferenciasUsuario
     *
     * @param \YDI\BackendBundle\Entity\PreferenciasUsuario $preferenciasUsuario
     *
     * @return Usuario
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

    /**
     * Add navegacione
     *
     * @param \YDI\BackendBundle\Entity\Navegacion $navegacione
     *
     * @return Usuario
     */
    public function addNavegacione(\YDI\BackendBundle\Entity\Navegacion $navegacione)
    {
        $this->navegaciones[] = $navegacione;

        return $this;
    }

    /**
     * Remove navegacione
     *
     * @param \YDI\BackendBundle\Entity\Navegacion $navegacione
     */
    public function removeNavegacione(\YDI\BackendBundle\Entity\Navegacion $navegacione)
    {
        $this->navegaciones->removeElement($navegacione);
    }

    /**
     * Get navegaciones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNavegaciones()
    {
        return $this->navegaciones;
    }

    /**
     * Add puntosXUsuario
     *
     * @param \YDI\BackendBundle\Entity\Puntosxusuario $puntosXUsuario
     *
     * @return Usuario
     */
    public function addPuntosXUsuario(\YDI\BackendBundle\Entity\Puntosxusuario $puntosXUsuario)
    {
        $this->puntosXUsuario[] = $puntosXUsuario;

        return $this;
    }

    /**
     * Remove puntosXUsuario
     *
     * @param \YDI\BackendBundle\Entity\Puntosxusuario $puntosXUsuario
     */
    public function removePuntosXUsuario(\YDI\BackendBundle\Entity\Puntosxusuario $puntosXUsuario)
    {
        $this->puntosXUsuario->removeElement($puntosXUsuario);
    }

    /**
     * Get puntosXUsuario
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPuntosXUsuario()
    {
        return $this->puntosXUsuario;
    }

    /**
     * Add rechazo
     *
     * @param \YDI\BackendBundle\Entity\Rechazos $rechazo
     *
     * @return Usuario
     */
    public function addRechazo(\YDI\BackendBundle\Entity\Rechazos $rechazo)
    {
        $this->rechazos[] = $rechazo;

        return $this;
    }

    /**
     * Remove rechazo
     *
     * @param \YDI\BackendBundle\Entity\Rechazos $rechazo
     */
    public function removeRechazo(\YDI\BackendBundle\Entity\Rechazos $rechazo)
    {
        $this->rechazos->removeElement($rechazo);
    }

    /**
     * Get rechazos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRechazos()
    {
        return $this->rechazos;
    }

    /**
     * Add telefono
     *
     * @param \YDI\BackendBundle\Entity\Telefono $telefono
     *
     * @return Usuario
     */
    public function addTelefono(\YDI\BackendBundle\Entity\Telefono $telefono)
    {
        $this->telefonos[] = $telefono;

        return $this;
    }

    /**
     * Remove telefono
     *
     * @param \YDI\BackendBundle\Entity\Telefono $telefono
     */
    public function removeTelefono(\YDI\BackendBundle\Entity\Telefono $telefono)
    {
        $this->telefonos->removeElement($telefono);
    }

    /**
     * Get telefonos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTelefonos()
    {
        return $this->telefonos;
    }

    /**
     * Add tarjeta
     *
     * @param \YDI\BackendBundle\Entity\Tarjeta $tarjeta
     *
     * @return Usuario
     */
    public function addTarjeta(\YDI\BackendBundle\Entity\Tarjeta $tarjeta)
    {
        $this->tarjetas[] = $tarjeta;

        return $this;
    }

    /**
     * Remove tarjeta
     *
     * @param \YDI\BackendBundle\Entity\Tarjeta $tarjeta
     */
    public function removeTarjeta(\YDI\BackendBundle\Entity\Tarjeta $tarjeta)
    {
        $this->tarjetas->removeElement($tarjeta);
    }

    /**
     * Get tarjetas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTarjetas()
    {
        return $this->tarjetas;
    }
}
