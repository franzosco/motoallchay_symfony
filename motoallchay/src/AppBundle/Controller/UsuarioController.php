<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Usuario;
use AppBundle\Form\UsuarioForm;

class UsuarioController extends Controller
{
    /**
     * @Route("/usuario", name="usuario_list")
     */
    public function indexAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Usuario');

        $busqueda = $request->query->get('busqueda');

        $paginate = $request->query->get('paginate') ?: 1;
        
        if ($busqueda && preg_match('/^(\w*-*)+$/', $busqueda))
        {
            $query = $repository->createQueryBuilder('u')
                ->where("u.name LIKE '$busqueda%'")
                ->setFirstResult(($paginate - 1) * 10)
                ->setMaxResults($paginate * 10)
                ->getQuery();
        }
        else {
            $query = $repository->createQueryBuilder('u')
                ->setFirstResult(($paginate - 1) * 10)
                ->setMaxResults($paginate * 10)
                ->getQuery();
        }

        $usuarios = $query->getResult();

        return $this->render('usuario/listar.html.twig', array(
            'usuarios' => $usuarios, 'busqueda' => $busqueda, 'pagina' => $paginate
        ));
    }

    /**
     * @Route("/usuario/crear", name="usuario_crear")
     */
    public function crearAction(Request $request)
    {
        $usuario = new Usuario();

        $form = $this->createForm(UsuarioForm::class, $usuario);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $usuario->setActive($usuario->getActive()?1:0);

            date_default_timezone_set('America/Lima');
            $usuario->setCreateAt(new \Datetime());

            $encoder = $this->get('security.password_encoder');
            $encodedPassword = $encoder->encodePassword(
                $usuario, $usuario->getPassword());

            $usuario->setPassword($encodedPassword);

            $em = $this->getDoctrine()->getManager();
            $em->persist($usuario);
            $em->flush();

            return $this->redirectToRoute('usuario_list');
        }

        return $this->render('usuario/crear.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/usuario/actualizar/{id}", name="usuario_actualizar")
     */
    public function actualizarAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $usuario = $this->getDoctrine()
            ->getRepository('AppBundle:Usuario')
            ->find($id);

        if (!$usuario) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        $form = $this->createForm(UsuarioForm::class, $usuario);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $usuario->setPassword(sha1($usuario->getPassword()));

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('usuario_list');
        }

        return $this->render('usuario/actualizar.html.twig', array(
            'form' => $form->createView(), 'id' => $id 
        ));
    }

    /**
     * @Route("/usuario/eliminar/{id}", name="usuario_eliminar")
     */
    public function eliminarAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $usuario = $this->getDoctrine()
            ->getRepository('AppBundle:Usuario')
            ->find($id);

        if (!$usuario) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        $em->remove($usuario);
        $em->flush();

        return $this->render('usuario/eliminar.html.twig');
    }

}
