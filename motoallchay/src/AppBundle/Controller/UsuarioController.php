<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\User;
use AppBundle\Form\UserForm;

class UsuarioController extends Controller
{
    // Ruta '/admin/moto'
    // Este Controlador es el CRUD para la tabla user, el usuario
    // 'Administrador' es el único que puede acceder a este recurso

    /**
     * @Route("/admin/usuario", name="usuario_list")
     */
    public function indexAction(Request $request)
    {
        // Ruta ('/admin/usuario') raiz
        // En este método se hace una consulta de todos los usuarios y 
        // los pagina de a 10 para enviarlos a la vista, tambien se 
        // pueden hacer búsquedas por nombres de usuarios, el cual se envía
        // por parámetro ejem: '/admin/usuario?busqueda=Juan+José'

        // Obtenemos el modelo 'User' que será usado para las consultas
        $repository = $this->getDoctrine()->getRepository('AppBundle:User');

        // Obtenemos por parámetro el nombre del usuario a buscar
        // si éste existe
        $busqueda = $request->query->get('busqueda');

        // Obtenemos el número de la página por parámetro
        // ejem: '/admin/usuario?paginate=2' donde paginate es un entero
        // por defacto siempre será 1 si no se envia ninguno
        $paginate = $request->query->get('paginate') ?: 1;
        
        // Si se está buscando por nombre tenemos que validar que
        // la búsqueda por nombre sea correcta, emtonce validamos
        // el nombre con una expreción regular que solo permita
        // sólo letras y espacio
        if ($busqueda && preg_match('/^([A-z])+(\s[A-z]+)*/', $busqueda))
        {
            // Creamos una consulta en donde el nombre del usuario
            // coincida con los resultados y los enpaginamos
            $query = $repository->createQueryBuilder('u')
                ->where("u.name LIKE '$busqueda%'")
                ->setFirstResult(($paginate - 1) * 10)
                ->setMaxResults(10)
                ->getQuery();
        }
        else
        {
            // Creamos una consulta que solo liste todos los usuario
            // y los enpaginamos
            $query = $repository->createQueryBuilder('u')
                ->setFirstResult(($paginate - 1) * 10)
                ->setMaxResults(10)
                ->getQuery();
        }

        // Mandamos a ejecutar la consulta y obtenemos los usuarios
        $usuarios = $query->getResult();

        // Enviamos a la vista las siguientes variables
        // 'paginate' => Página actual del documento
        // 'usuarios' => Lista de todos los usuarios
        // 'busqueda' => La busqueda anterior
        return $this->render('usuario/listar.html.twig', array(
            'pagina'   => $paginate,
            'usuarios' => $usuarios,
            'busqueda' => $busqueda,
        ));
    }

    /**
     * @Route("/admin/usuario/crear", name="usuario_crear")
     */
    public function crearAction(Request $request)
    {
        // Ruta ('/admin/usuario/crear')
        // (GET) Se envia un formulario limpio de usuario
        // (POST) Se registra una nuevo usuario on los datos correctos

        // Creamos un objeto nuevo User que será necesario para
        // que el formulario pueda ser validado de acuerdo a las
        // variables requeridas del User
        $usuario = new User();

        // Creamos un formulario para el Usuario
        $form = $this->createForm(UserForm::class, $usuario);

        // Si fue POST se obtienen los datos enviados para su validación
        $form->handleRequest($request);

        // Si fue POST y el formulario es correcto
        if ($form->isSubmitted() && $form->isValid())
        {
            // Agregamos el estado del usuario si está activado
            $usuario->setActive($usuario->getActive()?1:0);

            // Configuramos la zona horaria
            date_default_timezone_set('America/Lima');

            // Agramos la fecha actual de su creación
            $usuario->setCreateAt(new \Datetime());

            // Obrenemos el encoder apara encriptar la contraseña
            $encoder = $this->get('security.password_encoder');

            // Encriptamos la contraseña
            $encodedPassword = $encoder->encodePassword(
                $usuario, $usuario->getPassword());

            // Agragamos la contraseña encriptada al nuevo usuario
            $usuario->setPassword($encodedPassword);

            // Obtenemos la Entidad de conexion ala base de datos,
            // luego agregamos al usuario y lo guardamos
            $em = $this->getDoctrine()->getManager();
            $em->persist($usuario);
            $em->flush();

            // Todo esta bien entonces enviamos un mensaje de éxito
            return $this->render('mensajes/mensaje_exito.html.twig', array(
                "mensage"     => "Se Registró el nuevo Usuario con éxito",
                'redirect_to' => 'usuario_list'
            ));
        }

        // Si fue GET se envia un nuevo formulario limpio
        // Si fue POST se enviará el formulario enviado con los 
        // errores que se cometieron
        return $this->render('usuario/crear.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/admin/usuario/actualizar/{id}", name="usuario_actualizar")
     */
    public function actualizarAction(Request $request, $id)
    {   
        // Ruta ('/admin/usuario/actualizar/{id}') {id} es entero 
        // (GET) Se envia un formulario con los datos del usuario
        // (POST) Se actualiza el usuario con los datos correctos

        // Obtenemos la coneción de la base de datos
        $em = $this->getDoctrine()->getManager();

        // Buscamos al usuario por el id que se envió por parámetro
        $usuario = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($id);

        // Si el usuario no existe o no se encontraron resultados
        // se envia una pagina de error 404 no encontrado
        if (!$usuario) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        // creamos un formulario con los datos de la moto
        $form = $this->createForm(UserForm::class, $usuario);

        // Si la petición fue POST se agrega al formulario
        // Los datos que se enviaron
        $form->handleRequest($request);

        // Si la petición fue POST y el formulario es válido
        if ($form->isSubmitted() && $form->isValid())
        {   
            // Obtenemos el encoder para encriptar la contraseña
            $encoder = $this->get('security.password_encoder');

            // Encriptamos la contraseña y lo agregamos al usuario
            $encodedPassword = $encoder->encodePassword(
                $usuario, $usuario->getPassword());
            $usuario->setPassword($encodedPassword);

            // Se actualiza la moto con los nuevos datos del formulario
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            // Todo esta bién entonces enviamos un mensaje de éxito
            return $this->render('mensajes/mensaje_exito.html.twig', array(
                "mensage"     => "Se Actualizó al Usuario con éxito",
                'redirect_to' => 'usuario_list'
            ));
        }

        // Si fue GET se envia un nuevo formulario limpio
        // Si fue POST se enviará el formulario enviado con los 
        // errores que se cometieron
        return $this->render('usuario/actualizar.html.twig', array(
            'id'   => $id,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/admin/usuario/eliminar/{id}", name="usuario_eliminar")
     */
    public function eliminarAction($id)
    {
        // Ruta ('/admin/usuario/eliminar/{id}') {id} es entero 
        // (GET) Se elimina el Usuario por el {id} enviado por parámetro

        // Obtenemos la coneción de la base de datos
        $em = $this->getDoctrine()->getManager();

        // Buscamos al usuario por el id que se envió por parámetro
        $usuario = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($id);

        // Si el usuario no existe o no se encontraron resultados
        // se envia una pagina de error 404 no encontrado
        if (!$usuario) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }
        // Agregamos la moto a ser eliminada, para luego eliminarlo
        $em->remove($usuario);
        $em->flush();

        // Mandamos un mensaje de éxito
        return $this->render('usuario/eliminar.html.twig');
    }

}
