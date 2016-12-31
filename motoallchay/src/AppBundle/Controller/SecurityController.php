<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controlador usado para la seguridad y login de los usuarios
 */
class SecurityController extends Controller
{
    /**
     * @Route("/login", name="security_login")
     */
    public function loginAction()
    {
        // Registra en sesión al usuario si este es correcto
        $helper = $this->get('security.authentication_utils');

        return $this->render('security/index.html.twig', [
            'last_username' => $helper->getLastUsername(),
            'error' => $helper->getLastAuthenticationError(),
        ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logoutAction()
    {
        throw new \Exception('This should never be reached!');
    }

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        // Enviamos el formulario de sesión o si está logeado
        // Le mostramos la lista de acciones que puede hacer el usuario
        return $this->render('security/index.html.twig', [
            'last_username' => '',
            'error' => '',
        ]);
    }
}
