<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Moto;
use AppBundle\Form\MotoForm;

class MotoController extends Controller
{
    /**
     * @Route("/admin/moto", name="moto_list")
     */
    public function indexAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Moto');

        $busqueda = $request->query->get('busqueda');

        $paginate = $request->query->get('paginate') ?: 1;
        
        if ($busqueda && preg_match('/^(\w*-*)+$/', $busqueda))
        {
            $query = $repository->createQueryBuilder('m')
                ->where("m.placa LIKE '$busqueda%'")
                ->setFirstResult(($paginate - 1) * 10)
                ->setMaxResults($paginate * 10)
                ->getQuery();
        }
        else {
            $query = $repository->createQueryBuilder('m')
                ->setFirstResult(($paginate - 1) * 10)
                ->setMaxResults($paginate * 10)
                ->getQuery();
        }

        $motos = $query->getResult();

        return $this->render('moto/listar.html.twig', 
            array('motos' => $motos, 'busqueda' => $busqueda, 'pagina' => $paginate)
        );
    }

    /**
     * @Route("/admin/moto/crear", name="moto_crear")
     */
    public function crearAction(Request $request)
    {
        $moto = new Moto();

        $form = $this->createForm(MotoForm::class, $moto);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($moto);
            $em->flush();

            return $this->redirectToRoute('moto_list');
        }

        return $this->render('moto/crear.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/admin/moto/actualizar/{id}", name="moto_actualizar")
     */
    public function actualizarAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $moto = $this->getDoctrine()
            ->getRepository('AppBundle:Moto')
            ->find($id);

        if (!$moto) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        $form = $this->createForm(MotoForm::class, $moto);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('moto_list');
        }

        return $this->render('moto/actualizar.html.twig', array(
            'form' => $form->createView(), 'id' => $id 
        ));
    }

    /**
     * @Route("/admin/moto/eliminar/{id}", name="moto_eliminar")
     */
    public function eliminarAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $moto = $this->getDoctrine()
            ->getRepository('AppBundle:Moto')
            ->find($id);

        if (!$moto) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        $em->remove($moto);
        $em->flush();
        return $this->render('moto/eliminar.html.twig');
    }
}
