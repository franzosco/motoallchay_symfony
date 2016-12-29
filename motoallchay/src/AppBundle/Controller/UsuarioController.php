<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UsuarioController extends Controller
{
    /**
     * @Route("/usuario", name="usuario_list")
     */
    public function indexAction(Request $request)
    {
        return $this->render('session/index.html.twig');
    }

    /**
     * @Route("/usuario/crear")
     */
    public function crearAction()
    {
        return $this->render('usuario/crear.html.twig');
    }

    /**
     * @Route("/usuario/actualizar/{id}")
     */
    public function actualizarAction($id)
    {
        return $this->render('usuario/actualizar.html.twig');
    }

    /**
     * @Route("/usuario/eliminar/{id}")
     */
    public function eliminarAction($id)
    {
        return $this->render('usuario/eliminar.html.twig');
    }

}
