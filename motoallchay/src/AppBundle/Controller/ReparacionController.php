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
    // Ruta '/admin/reparacion'
    // Este Controlador es el CRUD para la tabla reparacion, los usuarios
    // que pueden acceder a este son 'Administrador y Recepcionista'

    /**
     * @Route("/admin/reparacion", name="reparacion_list")
     */
    public function indexAction(Request $request)
    {
        // Ruta ('/admin/reparacion') raiz
        // En este método se hace una consulta de todas las reparaciones y 
        // los pagina de a 10 para enviarlos a la vista, tambien se 
        // pueden hacer búsquedas por la placa de la moto el cual se envía
        // por parámetro ejem: '/admin/reparacion?busqueda=LM-23'

        // Obtenemos el ROL de Administrador
        $is_admin = $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN');

        // Obtenemos el Modelo Reparacion para hacer las consultas
        $repository = $this->getDoctrine()->getRepository('AppBundle:Reparacion');

        // Obtenemos por parámetro la placa de la moto a buscar
        $busqueda = $request->query->get('busqueda');

        // Obtenemos el número de la página por parámetro
        // ejem: '/admin/reparacion?paginate=2' donde paginate es un entero
        // por defacto siempre será 1 si no se envia ninguno
        $paginate = $request->query->get('paginate') ?: 1;
        
        // Si se está buscando por placa tenemos que validar que
        // la búsqueda por placa sea correcta, emtonce validamos
        // la placa con una expreción regular que solo permita 
        // números, letras y guión
        if ($busqueda && preg_match('/^(\w*-*)+$/', $busqueda))
        {   
            // Si es un usuario Administrador entonces puede obtener en la consulta
            // acceso a todas las reparaciones
            if ($is_admin)
            {
                // Creamos una consulta donde la placa coincida con la envia
                $query = $repository->createQueryBuilder('r')
                    ->innerJoin('AppBundle:Moto', 'm')
                    ->where("m.placa LIKE '$busqueda%'")
                    ->setFirstResult(($paginate - 1) * 10)
                    ->setMaxResults(10)
                    ->getQuery();
            }
            else
            {
                // Es un usuario de tipo Recepcionista solo puede ver
                // las reparaciones pendientes

                // Creamos un consulta en donde las coincidencias solo 
                // sean con las reparaciones pendientes
                $query = $repository->createQueryBuilder('r')
                    ->innerJoin('AppBundle:Moto', 'm')
                    ->where("m.placa LIKE '$busqueda%'")
                    ->andWhere("r.fecha_entrega IS NULL")
                    ->setFirstResult(($paginate - 1) * 10)
                    ->setMaxResults(10)
                    ->getQuery();
            }
        }
        else 
        {
            // No se está buscando nada

            // Si es un usuario Administrador entonces puede obtener en la consulta
            // acceso a todas las reparaciones
            if ($is_admin)
            {
                // Creamos una consulta donde la placa coincida con la envia
                $query = $repository->createQueryBuilder('r')
                    ->setFirstResult(($paginate - 1) * 10)
                    ->setMaxResults($paginate * 10)
                    ->getQuery();
            }
            else
            {
                // Es un usuario de tipo Recepcionista solo puede ver
                // las reparaciones pendientes

                // Creamos un consulta en donde las coincidencias solo 
                // sean con las reparaciones pendientes
                $query = $repository->createQueryBuilder('r')
                    ->where("r.fecha_entrega IS NULL")
                    ->setFirstResult(($paginate - 1) * 10)
                    ->setMaxResults($paginate * 10)
                    ->getQuery();
            }
        }

        // Mandamos a ejecutar la consulta
        $reparaciones = $query->getResult();

        // Enviamos a la vista las siguientes variables
        // 'pagina'       => Página actual del documento
        // 'reparaciones' => Lista de todas las reparaciones
        // 'busqueda'     => La busqueda anterior
        return $this->render('reparacion/listar.html.twig', array(
            'pagina'       => $paginate,
            'reparaciones' => $reparaciones, 
            'busqueda'     => $busqueda,
        ));
    }

    /**
     * @Route("/admin/reparacion/crear", name="reparacion_crear")
     */
    public function crearAction(Request $request)
    {
        // Ruta ('/admin/reparacion/crear')
        // (GET) Se envia un formulario limpio de reparación
        // (POST) Se registra la nueva reparación con los datos correctos

        // Creamos un formulario para la reparación
        $form = $this->createForm(ReparacionForm::class);

        // Si fue POST se obtienen los datos enviados para su validación
        $form->handleRequest($request);

        $error = null;

        // Si fue POST y el formulario es correcto
        if ($form->isSubmitted() && $form->isValid())
        {
            // Creamos un objeto Reparacion
            $reparacion = new Reparacion();

            // Obtenemos los datos del formulario
            $data = $form->getData();

            // Hacemos una consulta a la Entidad Moto por la placa
            $moto = $this->getDoctrine()
                ->getRepository('AppBundle:Moto')
                ->findOneByPlaca($data['placa']);
            
            if ($moto)
            {
                // Obtenemos el Usuario en sesión
                $usuario = $this->get('security.token_storage')
                    ->getToken()->getUser();
                
                // Configuramos la zona horaria
                date_default_timezone_set('America/Lima');

                // Agregamos ala reparación la fecha actual
                $reparacion->setFechaIngreso(new \Datetime());

                // Agregamos el precio
                $reparacion->setPrecio($data['precio']);

                // Agregamos la descripción
                $reparacion->setDescripcion($data['descripcion']);

                // Agregamos el servicio a usar
                $reparacion->setServicios($data['servicios']);

                // Agregamos la moto a ser reparada y el usuario quien
                // lo registró
                $reparacion->setMoto($moto);
                $reparacion->setUser($usuario);

                // Obtenemos la coneción de la base de datos
                $em = $this->getDoctrine()->getManager();

                // Asignamos la moto para su inserción
                $em->persist($reparacion);

                // Con este método hacemos la inserción o actualización
                // si la mota ya existe
                $em->flush();

                // Todo esta bien entonces enviamos un mensaje de éxito
                return $this->render('mensajes/mensaje_exito.html.twig', array(
                    "mensage"     => "Se Registró la nueva Reparación con éxito",
                    'redirect_to' => 'reparacion_list'
                ));
            }
            else
            {
                $error = "La moto aún no fue registrada";
            }

        }

        // Si fue GET se envia un nuevo formulario limpio
        // Si fue POST se enviará el formulario enviado con los 
        // errores que se cometieron
        return $this->render('reparacion/crear.html.twig', array(
            'form'  => $form->createView(),
            'error' => $error
        ));
    }

    /**
     * @Route("/admin/reparacion/actualizar/{id}", name="reparacion_actualizar")
     */
    public function actualizarAction(Request $request, $id)
    {   
        // Ruta ('/admin/reparacion/actualizar/{id}') {id} es entero 
        // (GET) Se envia un formulario con los datos de la reparación
        // (POST) Se actualiza la reparación con los datos correctos
        // Esta ruta solo es para el ADMINISTRADOR

        // Obtenemos la coneción de la base de datos
        $em = $this->getDoctrine()->getManager();

        // Buscamos la reparación por el id que se envió por parámetro
        $reparacion = $this->getDoctrine()
            ->getRepository('AppBundle:Reparacion')
            ->find($id);

        // Si la reparación no existe o no se encontraron resultados
        // se envia una pagina de error 404 no encontrado
        if (!$reparacion) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        // creamos un formulario con los datos de la reparación
        $form = $this->createForm(ReparacionForm::class, $reparacion);

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
                "mensage"     => "Se Actualizó la Reparación con éxito",
                'redirect_to' => 'reparacion_list'
            ));
        }

        // Si fue GET se envia un nuevo formulario limpio
        // Si fue POST se enviará el formulario enviado con los 
        // errores que se cometieron
        return $this->render('reparacion/actualizar.html.twig', array(
            'id'    => $id,
            'form'  => $form->createView(),
            'error' => null
        ));
    }

    /**
     * @Route("/admin/reparacion/entregar/{id}", name="reparacion_entregar")
     */
    public function entregarAction(Request $request, $id)
    {
        // Ruta ('/admin/reparacion/entregar/{id}') {id} es entero 
        // (GET) Se envia un formulario con los datos de la reparación
        // (POST) Se actualiza la reparación con los datos correctos
        // Esta ruta solo es para el RECEPCIONISTA

        // Obtenemos la coneción de la base de datos
        $em = $this->getDoctrine()->getManager();

        // Buscamos la reparación por el id que se envió por parámetro
        $reparacion = $this->getDoctrine()
            ->getRepository('AppBundle:Reparacion')
            ->find($id);

        // Si la reparación no existe o no se encontraron resultados
        // se envia una pagina de error 404 no encontrado
        if (!$reparacion) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        // creamos un formulario con los datos de la reparación
        $form = $this->createForm(ReparacionForm::class, $reparacion);

        // Si la petición fue POST se agrega al formulario
        // Los datos que se enviaron
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            // Actualizamos la reparación a Entregado
            $reparacion->setEstado("Entregado");
            
            // Se actualiza la moto con los nuevos datos del formulario
            $em->flush();

            // Todo esta bién entonces enviamos un mensaje de éxito
            return $this->render('mensajes/mensaje_exito.html.twig', array(
                "mensage"     => "Se entregó la moto reparada",
                'redirect_to' => 'reparacion_list'
            ));
        }

        // Si fue GET se envia un nuevo formulario limpio
        // Si fue POST se enviará el formulario enviado con los 
        // errores que se cometieron
        return $this->render('reparacion/actualizar.html.twig', array(
            'id'    => $id,
            'form'  => $form->createView(),
            'error' => null
        ));
    }

    /**
     * @Route("/admin/reparacion/eliminar/{id}", name="reparacion_eliminar")
     */
    public function eliminarAction($id)
    {
        // Ruta ('/admin/reparacion/eliminar/{id}') {id} es entero 
        // (GET) Se elimina la reparación por el {id} enviado por parámetro
        // Esta ruta es solo para el ADMINISTRADOR

        // Obtenemos la coneción de la base de datos
        $em = $this->getDoctrine()->getManager();

        // Buscamos la reparación por el id que se envió por parámetro
        $reparacion = $this->getDoctrine()
            ->getRepository('AppBundle:Reparacion')
            ->find($id);

        // Si la reparación no existe o no se encontraron resultados
        // se envia una pagina de error 404 no encontrado
        if (!$reparacion) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        // Agregamos la moto a ser eliminada
        $em->remove($reparacion);

        // Eliminamos la reparación
        $em->flush();

        // Mandamos un mensaje de éxsito
        return $this->render('reparacion/eliminar.html.twig');
    }

}
