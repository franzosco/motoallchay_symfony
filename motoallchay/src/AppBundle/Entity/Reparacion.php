<?php

namespace AppBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/*
 * Este es el Modelo Reparación que se conecta con la base de datos,
 * Este modelo mapea la base de datos y ahce las consultas mas 
 * fáciles.
 */

/**
 * @ORM\Entity
 * @ORM\Table(name="reparacion")
 */
class Reparacion
{
    /**
     * @ORM\Column(type="integer", length=10)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_ingreso;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fecha_entrega;

    /**
     * @ORM\Column(type="string")
     */
    private $servicios;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="decimal")
     */
    private $precio;

    /**
     * @ORM\ManyToOne(targetEntity="Moto", inversedBy="motos")
     * @ORM\JoinColumn(name="moto_id", referencedColumnName="id")
     */
    private $moto;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="users")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $usuario;


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
     * Set fechaIngreso
     *
     * @param \DateTime $fechaIngreso
     *
     * @return Reparacion
     */
    public function setFechaIngreso($fechaIngreso)
    {
        $this->fecha_ingreso = $fechaIngreso;

        return $this;
    }

    /**
     * Get fechaIngreso
     *
     * @return \DateTime
     */
    public function getFechaIngreso()
    {
        return $this->fecha_ingreso;
    }

    /**
     * Set fechaEntrega
     *
     * @param \DateTime $fechaEntrega
     *
     * @return Reparacion
     */
    public function setFechaEntrega($fechaEntrega)
    {
        $this->fecha_entrega = $fechaEntrega;

        return $this;
    }

    /**
     * Get fechaEntrega
     *
     * @return \DateTime
     */
    public function getFechaEntrega()
    {
        return $this->fecha_entrega;
    }

    /**
     * Set moto
     *
     * @param \AppBundle\Entity\Moto $moto
     *
     * @return Reparacion
     */
    public function setMoto(\AppBundle\Entity\Moto $moto = null)
    {
        $this->moto = $moto;

        return $this;
    }

    /**
     * Get moto
     *
     * @return \AppBundle\Entity\Moto
     */
    public function getMoto()
    {
        return $this->moto;
    }

    /**
     * Set usuario
     *
     * @param \AppBundle\Entity\User $usuario
     *
     * @return Reparacion
     */
    public function setUser(\AppBundle\Entity\User $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->usuario;
    }

    /**
     * Set servicios
     *
     * @param string $servicios
     *
     * @return Reparacion
     */
    public function setServicios($servicios)
    {
        $this->servicios = $servicios;

        return $this;
    }

    /**
     * Get servicios
     *
     * @return string
     */
    public function getServicios()
    {
        return $this->servicios;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Reparacion
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
     * Set precio
     *
     * @param string $precio
     *
     * @return Reparacion
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return string
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set Placa
     *
     * @param string $placa
     *
     * @return Reparacion
     */
    public function setPlaca($placa)
    {
        // Nada que hacer
        return $this;
    }    

    /**
     * Get Placa
     *
     * @return string
     */
    public function getPlaca(){
        return $this->moto->getPlaca();
    }

    /**
     * Set Estado
     *
     * @param string $estado
     *
     * @return Reparacion
     */
    public function setEstado($estado)
    {    
        if($estado === "Pendiente")
        {
            $this->fecha_entrega = null;
        }

        if($estado === "Entregado" && $this->fecha_entrega == null)
        {
            date_default_timezone_set('America/Lima');
            $this->fecha_entrega = new \DateTime();
        }

        return $this;
    }

    /**
     * Get Estado
     *
     * @return string
     */
    public function getEstado(){
        if ($this->fecha_entrega)
        {
            return "Entregado";
        }
        return "Pendiente";
    }
}
