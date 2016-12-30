<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Reparacion;
use AppBundle\Entity\Usuario;
use AppBundle\Entity\Moto;
use AppBundle\Form\ReparacionForm;

class ReparacionController extends Controller
{
    /**
     * @Route("/reparacion", name="reparacion_list")
     */
    public function indexAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Reparacion');

        $busqueda = $request->query->get('busqueda');

        $paginate = $request->query->get('paginate') ?: 1;
        
        if ($busqueda && preg_match('/^(\w*-*)+$/', $busqueda))
        {
            $query = $repository->createQueryBuilder('r')
                ->innerJoin('AppBundle:Moto', 'm')
                ->where("m.placa LIKE '$busqueda%'")
                ->setFirstResult(($paginate - 1) * 10)
                ->setMaxResults($paginate * 10)
                ->getQuery();
        }
        else {
            $query = $repository->createQueryBuilder('r')
                ->setFirstResult(($paginate - 1) * 10)
                ->setMaxResults($paginate * 10)
                ->getQuery();
        }

        $reparaciones = $query->getResult();

        return $this->render('reparacion/listar.html.twig', 
            array('reparaciones' => $reparaciones, 'busqueda' => $busqueda, 'pagina' => $paginate)
        );
    }

    /**
     * @Route("/reparacion/crear", name="reparacion_crear")
     */
    public function crearAction(Request $request)
    {

        $form = $this->createForm(ReparacionForm::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $reparacion = new Reparacion();
            $data = $form->getData();

            $moto = $this->getDoctrine()
                ->getRepository('AppBundle:Moto')
                ->findOneByPlaca($data['placa']);
            
            $usuario = $this->getDoctrine()
                ->getRepository('AppBundle:Usuario')
                ->find(1);
            
            date_default_timezone_set('America/Lima');
            $reparacion->setFechaIngreso(new \Datetime());
            $reparacion->setPrecio($data['precio']);
            $reparacion->setDescripcion($data['descripcion']);
            $reparacion->setServicios($data['servicios']);

            $reparacion->setMoto($moto);
            $reparacion->setUsuario($usuario);

            $em = $this->getDoctrine()->getManager();
            $em->persist($reparacion);
            $em->flush();

            return $this->redirectToRoute('reparacion_list');
        }

        return $this->render('reparacion/crear.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/reparacion/actualizar/{id}", name="reparacion_actualizar")
     */
    public function actualizarAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $reparacion = $this->getDoctrine()
            ->getRepository('AppBundle:Reparacion')
            ->find($id);

        if (!$reparacion) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        $form = $this->createForm(ReparacionForm::class, $reparacion);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();

            return $this->redirectToRoute('reparacion_list');
        }

        return $this->render('reparacion/actualizar.html.twig', array(
            'form' => $form->createView(), 'id' => $id 
        ));
    }

    /**
     * @Route("/reparacion/eliminar/{id}", name="reparacion_eliminar")
     */
    public function eliminarAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $reparacion = $this->getDoctrine()
            ->getRepository('AppBundle:Reparacion')
            ->find($id);

        if (!$reparacion) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        $em->remove($reparacion);
        $em->flush();

        return $this->render('reparacion/eliminar.html.twig');
    }

}
