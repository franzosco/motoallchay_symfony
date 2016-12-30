<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Reparacion;
use AppBundle\Entity\User;
use AppBundle\Entity\Moto;
use AppBundle\Form\ReparacionForm;

class ReparacionController extends Controller
{
    /**
     * @Route("/admin/reparacion", name="reparacion_list")
     */
    public function indexAction(Request $request)
    {
        $is_admin = $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN');

        $repository = $this->getDoctrine()->getRepository('AppBundle:Reparacion');

        $busqueda = $request->query->get('busqueda');

        $paginate = $request->query->get('paginate') ?: 1;
        
        if ($busqueda && preg_match('/^(\w*-*)+$/', $busqueda))
        {   
            if ($is_admin)
            {
                $query = $repository->createQueryBuilder('r')
                    ->innerJoin('AppBundle:Moto', 'm')
                    ->where("m.placa LIKE '$busqueda%'")
                    ->setFirstResult(($paginate - 1) * 10)
                    ->setMaxResults($paginate * 10)
                    ->getQuery();
            }
            else
            {
                $query = $repository->createQueryBuilder('r')
                    ->innerJoin('AppBundle:Moto', 'm')
                    ->where("m.placa LIKE '$busqueda%'")
                    ->andWhere("r.fecha_entrega IS NULL")
                    ->setFirstResult(($paginate - 1) * 10)
                    ->setMaxResults($paginate * 10)
                    ->getQuery();
            }
        }
        else {
            if ($is_admin)
            {
                $query = $repository->createQueryBuilder('r')
                    ->setFirstResult(($paginate - 1) * 10)
                    ->setMaxResults($paginate * 10)
                    ->getQuery();
            }
            else
            {
                $query = $repository->createQueryBuilder('r')
                    ->where("r.fecha_entrega IS NULL")
                    ->setFirstResult(($paginate - 1) * 10)
                    ->setMaxResults($paginate * 10)
                    ->getQuery();
            }
        }

        $reparaciones = $query->getResult();

        return $this->render('reparacion/listar.html.twig', 
            array('reparaciones' => $reparaciones, 'busqueda' => $busqueda, 'pagina' => $paginate)
        );
    }

    /**
     * @Route("/admin/reparacion/crear", name="reparacion_crear")
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
                ->getRepository('AppBundle:User')
                ->find(1);
            
            date_default_timezone_set('America/Lima');
            $reparacion->setFechaIngreso(new \Datetime());
            $reparacion->setPrecio($data['precio']);
            $reparacion->setDescripcion($data['descripcion']);
            $reparacion->setServicios($data['servicios']);

            $reparacion->setMoto($moto);
            $reparacion->setUser($usuario);

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
     * @Route("/admin/reparacion/actualizar/{id}", name="reparacion_actualizar")
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
            $reparacion->setEstado("Entregado");
            $em->flush();

            return $this->redirectToRoute('reparacion_list');
        }

        return $this->render('reparacion/actualizar.html.twig', array(
            'form' => $form->createView(), 'id' => $id 
        ));
    }

    /**
     * @Route("/admin/reparacion/entregar/{id}", name="reparacion_entregar")
     */
    public function entregarAction(Request $request, $id)
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
            $reparacion->setEstado("Entregado");
            $em->flush();

            return $this->render('mensajes/mensaje_exito.html.twig', array(
                "mensage" => "Se entregó la moto reparada", 'redirect_to' => 'reparacion_list'
            ));
        }

        return $this->render('reparacion/actualizar.html.twig', array(
            'form' => $form->createView(), 'id' => $id 
        ));
    }

    /**
     * @Route("/admin/reparacion/eliminar/{id}", name="reparacion_eliminar")
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
