<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ReparacionController extends Controller
{
    /**
     * @Route("/reparacion", name="reparacion_list")
     */
    public function indexAction(Request $request)
    {
        return $this->render('reparacion/listar.html.twig');
    }

    /**
     * @Route("/reparacion/crear")
     */
    public function crearAction()
    {
        return $this->render('reparacion/crear.html.twig');
    }

    /**
     * @Route("/reparacion/actualizar/{id}")
     */
    public function actualizarAction($id)
    {
        return $this->render('reparacion/actualizar.html.twig');
    }

    /**
     * @Route("/reparacion/eliminar/{id}")
     */
    public function eliminarAction($id)
    {
        return $this->render('reparacion/eliminar.html.twig');
    }

}
