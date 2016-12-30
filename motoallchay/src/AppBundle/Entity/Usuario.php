<?php

namespace AppBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="usuario")
 * @UniqueEntity("username",message="Lo sentimos, el nombre de usuario moto ya está registrado")
 * @UniqueEntity("email",message="Lo sentimos, el email ya está registrado")
 * @UniqueEntity("dni",message="Lo sentimos, el DNI ya está registrado")
 */
class Usuario implements UserInterface
{
    /**
     * @ORM\Column(type="integer", length=10)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32, unique=true)
     * @Assert\Regex(
     *     pattern     = "/^\w{3,32}$/",
     *     message="Ingrese un nombre de usuario correcto"
     * )
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=8)
     * @Assert\Regex(
     *     pattern     = "/^\d{8}$/",
     *     message="El DNI no es válido"
     * )
     */
    private $dni;

    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\NotBlank()
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\Regex(
     *     pattern     = "/^([A-z])+(\s[A-z]+)*$/",
     *     message="Ingrese un nombre correcto"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\Regex(
     *     pattern     = "/^([A-z])+(\s[A-z]+)*$/",
     *     message="Ingrese los apellidos correctos"
     * )
     */
    private $last_name;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\Email(
     *     message = "El emailno es válido."
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\Regex(
     *     pattern     = "/^\d{9}$/",
     *     message="Ingrese los apellidos correctos"
     * )
     */
    private $telefono;

    /**
     * @ORM\Column(type="string", length=15)
     * @Assert\NotBlank()
     */
    private $tipo;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $create_at;

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
     * Set username
     *
     * @param string $username
     *
     * @return Usuario
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Usuario
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Usuario
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Usuario
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
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
     * Set tipo
     *
     * @param string $tipo
     *
     * @return Usuario
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set active
     *
     * @param string $active
     *
     * @return Usuario
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return string
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return Usuario
     */
    public function setCreateAt($createAt)
    {
        $this->create_at = $createAt;

        return $this;
    }

    /**
     * Get createAt
     *
     * @return \DateTime
     */
    public function getCreateAt()
    {
        return $this->create_at;
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
     * Set dni
     *
     * @param string $dni
     *
     * @return Usuario
     */
    public function setDni($dni)
    {
        $this->dni = $dni;

        return $this;
    }

    /**
     * Get dni
     *
     * @return string
     */
    public function getDni()
    {
        return $this->dni;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
        
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
     * @return Usuario
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
