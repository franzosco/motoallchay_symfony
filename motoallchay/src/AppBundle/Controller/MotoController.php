<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Moto;
use AppBundle\Form\MotoForm;

class MotoController extends Controller
{
    // Ruta '/admin/moto'
    // Este Controlador es el CRUD para la tabla moto, los usuarios
    // que pueden acceder a este son 'Administrador y Recepcionista'

    /**
     * @Route("/admin/moto", name="moto_list")
     */
    public function indexAction(Request $request)
    {
        // Ruta ('/admin/moto') raiz
        // En este método se hace una consulta de todas las motos y 
        // los pagina de a 10 para enviarlos a la vista, tambien se 
        // pueden hacer búsquedas por la placa de la moto el cual se envía
        // por parámetro ejem: '/admin/moto?busqueda=LM-23'

        // Obtenemos el modelo 'Moto' que será usado para las consultas
        $repository = $this->getDoctrine()->getRepository('AppBundle:Moto');

        // Obtenemos por parámetro la placa de la moto a buscar
        $busqueda = $request->query->get('busqueda');

        // Obtenemos el número de la página por parámetro
        // ejem: '/admin/moto?paginate=2' donde paginate es un entero
        // por defacto siempre será 1 si no se envia ninguno
        $paginate = (int) $request->query->get('paginate') ?: 1;
        
        // Si se está buscando por placa tenemos que validar que
        // la búsqueda por placa sea correcta, emtonce validamos
        // la placa con una expreción regular que solo permita
        // números, letras y guión
        if ($busqueda && preg_match('/^(\w*-*)+$/', $busqueda))
        {
            // Creamos una consulta en donde la placa de la moto tenga
            // coincida con los resultados y los enpaginamos
            $query = $repository->createQueryBuilder('m')
                ->where("m.placa LIKE '$busqueda%'")
                ->setFirstResult(($paginate - 1) * 10)
                ->setMaxResults(10)
                ->getQuery();
        }
        else
        {
            // Creamos una consulta que solo liste las motos y los empagine
            $query = $repository->createQueryBuilder('m')
                ->setFirstResult(($paginate - 1) * 10)
                ->setMaxResults(10)
                ->getQuery();
        }

        // Mandamos a ejecutar la consulta y obtenemos las motos
        $motos = $query->getResult();

        // Enviamos a la vista las siguientes variables
        // 'motos'    => Lista de todas las motos 
        // 'busqueda' => La busqueda anterior
        // 'paginate' => Página actual del documento
        return $this->render('moto/listar.html.twig', array(
            'motos'    => $motos,
            'busqueda' => $busqueda,
            'pagina'   => $paginate
        ));
    }

    /**
     * @Route("/admin/moto/crear", name="moto_crear")
     */
    public function crearAction(Request $request)
    {
        // Ruta ('/admin/moto/crear')
        // (GET) Se envia un formulario limpio de moto
        // (POST) Se registra una nueva moto con los datos correctos

        // Creamos un objeto nuevo Moto que será necesario para
        // que el formulario pueda ser validado de acuerdo a las
        // variables requeridas de la Moto
        $moto = new Moto();

        // Creamos un formulario para la moto
        $form = $this->createForm(MotoForm::class, $moto);

        // Si fue POST se obtienen los datos enviados para su validación
        $form->handleRequest($request);

        // Si fue POST y el formulario es correcto
        if ($form->isSubmitted() && $form->isValid()) {

            // Obtenemos la coneción de la base de datos
            $em = $this->getDoctrine()->getManager();

            // Asignamos la moto para su inserción
            $em->persist($moto);

            // Con este método hacemos la inserción o actualización
            // si la mota ya existe
            $em->flush();

            // Todo esta bien entonces enviamos un mensaje de éxito
            return $this->render('mensajes/mensaje_exito.html.twig', array(
                "mensage"     => "Se Registró la nueva moto con éxito",
                'redirect_to' => 'moto_list'
            ));
        }

        // Si fue GET se envia un nuevo formulario limpio
        // Si fue POST se enviará el formulario enviado con los 
        // errores que se cometieron
        return $this->render('moto/crear.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/admin/moto/actualizar/{id}", name="moto_actualizar")
     */
    public function actualizarAction(Request $request, $id)
    {   
        // Ruta ('/admin/moto/actualizar/{id}') {id} es entero 
        // (GET) Se envia un formulario con los datos de la moto
        // (POST) Se actualiza la moto con los datos correctos

        // Obtenemos la coneción de la base de datos
        $em = $this->getDoctrine()->getManager();

        // Buscamos la moto por el id que se envió por parámetro
        $moto = $this->getDoctrine()
            ->getRepository('AppBundle:Moto')
            ->find($id);

        // Si la moto no existe o no se encontraron resultados
        // se envia una pagina de error 404 no encontrado
        if (!$moto) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        // creamos un formulario con los datos de la moto
        $form = $this->createForm(MotoForm::class, $moto);

        // Si la petición fue POST se agrega al formulario
        // Los datos que se enviaron
        $form->handleRequest($request);

        // Si la petición fue POST y el formulario es válido
        if ($form->isSubmitted() && $form->isValid())
        {
            // Se actualiza la moto con los nuevos datos del formulario
            $em->flush();

            // Todo esta bién entonces enviamos un mensaje de éxito
            return $this->render('mensajes/mensaje_exito.html.twig', array(
                "mensage"     => "Se Actualizó la moto con éxito",
                'redirect_to' => 'moto_list'
            ));
        }

        // Si fue GET se envia un nuevo formulario limpio
        // Si fue POST se enviará el formulario enviado con los 
        // errores que se cometieron
        return $this->render('moto/actualizar.html.twig', array(
            'form' => $form->createView(), 
            'id'   => $id
        ));
    }

    /**
     * @Route("/admin/moto/eliminar/{id}", name="moto_eliminar")
     */
    public function eliminarAction($id)
    {   
        // Ruta ('/admin/moto/eliminar/{id}') {id} es entero 
        // (GET) Se elimina la moto por el {id} enviado por parámetro

        // Obtenemos la coneción de la base de datos
        $em = $this->getDoctrine()->getManager();

        // Buscamos la moto por el id que se envió por parámetro
        $moto = $this->getDoctrine()
            ->getRepository('AppBundle:Moto')
            ->find($id);

        // Si la moto no existe o no se encontraron resultados
        // se envia una pagina de error 404 no encontrado
        if (!$moto) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }
        // Agregamos la moto a ser eliminada
        $em->remove($moto);

        // Eliminamos la moto
        $em->flush();

        // Mandamos un mensaje de éxito
        return $this->render('moto/eliminar.html.twig');
    }
}
