<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="moto")
 * @UniqueEntity("placa",message="Lo sentimos, la placa de la moto ya está registrada")
 */
class Moto
{
    /**
     * @ORM\Column(type="integer", length=10)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=7, unique=true)
     * @Assert\Regex(
     *     pattern     = "/^[A-z]{2,3}-[A-z]{2,3}$/",
     *     message="La placa no es inválida"
     * )
     * @Assert\NotBlank(
     *     message="No se ingresó la placa"
     * )
     */
    private $placa;

    /**
     * @ORM\Column(type="string", length=8)
     * @Assert\Regex(
     *     pattern     = "/^\d{8}$/",
     *     message="El DNI del cliente es inválido"
     * )
     * @Assert\NotBlank(
     *     message="No se ingresó el DNI"
     * )
     */
    private $cliente_dni;

    /**
     * @ORM\Column(type="string", length=25)
     * @Assert\NotBlank()
     */
    private $color;

    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\Regex(
     *     pattern     = "/^\w+$/",
     *     message="La marca de la moto no es válida"
     * )
     * @Assert\NotBlank(
     *     message="Ingrese la marca de la moto"
     * )
     */
    private $marca;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\OneToMany(targetEntity="Reparacion", mappedBy="reparacion")
     */
    private $reparaciones;

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
     * Set placa
     *
     * @param string $placa
     *
     * @return Moto
     */
    public function setPlaca($placa)
    {
        $this->placa = $placa;

        return $this;
    }

    /**
     * Get placa
     *
     * @return string
     */
    public function getPlaca()
    {
        return $this->placa;
    }

    /**
     * Set clienteDni
     *
     * @param string $clienteDni
     *
     * @return Moto
     */
    public function setClienteDni($clienteDni)
    {
        $this->cliente_dni = $clienteDni;

        return $this;
    }

    /**
     * Get clienteDni
     *
     * @return string
     */
    public function getClienteDni()
    {
        return $this->cliente_dni;
    }

    /**
     * Set color
     *
     * @param string $color
     *
     * @return Moto
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set marca
     *
     * @param string $marca
     *
     * @return Moto
     */
    public function setMarca($marca)
    {
        $this->marca = $marca;

        return $this;
    }

    /**
     * Get marca
     *
     * @return string
     */
    public function getMarca()
    {
        return $this->marca;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Moto
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
     * Constructor
     */
    public function __construct()
    {
        $this->reparaciones = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add reparacione
     *
     * @param \AppBundle\Entity\Reparacion $reparacione
     *
     * @return Moto
     */
    public function addReparacione(\AppBundle\Entity\Reparacion $reparacione)
    {
        $this->reparaciones[] = $reparacione;

        return $this;
    }

    /**
     * Remove reparacione
     *
     * @param \AppBundle\Entity\Reparacion $reparacione
     */
    public function removeReparacione(\AppBundle\Entity\Reparacion $reparacione)
    {
        $this->reparaciones->removeElement($reparacione);
    }

    /**
     * Get reparaciones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReparaciones()
    {
        return $this->reparaciones;
    }
}
