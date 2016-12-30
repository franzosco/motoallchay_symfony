<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="symfony_demo_user")
 */
class User implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
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
     * @ORM\Column(type="string", length=60)
     * @Assert\Email(
     *     message = "El emailno es válido."
     * )
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var array
     *
     * @ORM\Column(type="json_array")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=8)
     * @Assert\Regex(
     *     pattern     = "/^\d{8}$/",
     *     message="El DNI no es válido"
     * )
     */
    private $dni;

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
     * @ORM\Column(type="string", length=15)
     */
    private $tipo;

    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\Regex(
     *     pattern     = "/^\d{9}$/",
     *     message="Ingrese los apellidos correctos"
     * )
     */
    private $telefono;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_at;

    /**
     * @ORM\OneToMany(targetEntity="Reparacion", mappedBy="reparacion")
     */
    private $reparaciones;


    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Returns the roles or permissions granted to the user for security.
     */
    public function getRoles()
    {
        $roles = $this->roles;

        // guarantees that a user always has at least one role for security
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * {@inheritdoc}
     */
    public function getSalt()
    {
        // See "Do you need to use a Salt?" at http://symfony.com/doc/current/cookbook/security/entity_provider.html
        // we're using bcrypt in security.yml to encode the password, so
        // the salt value is built-in and you don't have to generate one

        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
        // if you had a plainPassword property, you'd nullify it here
        // $this->plainPassword = null;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->reparaciones = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set dni
     *
     * @param string $dni
     *
     * @return User
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

    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
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
     * @return User
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
     * Set telefono
     *
     * @param string $telefono
     *
     * @return User
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
     * Add reparacione
     *
     * @param \AppBundle\Entity\Reparacion $reparacione
     *
     * @return User
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

    /**
     * Set tipo
     *
     * @param string $tipo
     *
     * @return User
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        if ($tipo === "Administrador") {
            $this->roles = array('ROLE_ADMIN');
        }
        else if ($tipo === "Gerente") {
            $this->roles = array('ROLE_GERENTE');
        }
        else if ($tipo === "Recepcionista") {
            $this->roles = array('ROLE_RECEPCIONISTA');
        }

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
     * @param boolean $active
     *
     * @return User
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
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
     * @return User
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
}
