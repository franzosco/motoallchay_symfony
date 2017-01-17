<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class ReporteController extends Controller
{
    // Ruta '/admin/reporte'
    // Este Controlador para los reportes de la applicación
    // que pueden acceder a este son 'Administrador y Gerente'

    /**
     * @Route("/admin/reporte", name="reportes_list")
     */
    public function indexAction(Request $request)
    {
        // Ruta ('/admin/reporte') raiz
        // Lista todos los reportes

        return $this->render('reporte/reportes.html.twig');
    }

    /**
     * @Route("/admin/reporte/monto_dia", name="reporte_monto_dia")
     */
    public function montoDiaAction(Request $request)
    {
        // Ruta ('/admin/reporte/monto_dia')
        // Hace un reporte del monto recaudado del día

        // Obtenemos el modelo 'Reparacion' que será usado para las consultas
        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:Reparacion');
        
        // Configuramos la zona horaria 
        date_default_timezone_set('America/Lima');

        // Obtenemos la fecha de hoy
        $hoy = date("Y-m-d");

        // Hacemos una consulta contando la cantidad de reparaciones entregadas,
        // sumando el precio de las reparaciones del dia
        $query = $repository->createQueryBuilder('r')
            ->select("COUNT(r.id) AS cantidad, SUM(r.precio) AS monto")
            ->where("r.fecha_entrega LIKE '$hoy%'")
            ->getQuery();

        // Ejecutamos la consulta y obtenemos el primero
        $resultados = $query->getResult()[0];

        // Enviamos a la vista las variables
        // 'hoy'        => Fecha de el dia
        // 'resultados' => Array que contiene la cantidad y el monto del día
        return $this->render('reporte/monto_dia.html.twig', array(
            'hoy'        => $hoy,
            'resultados' => $resultados,
        ));
    }

    /**
     * @Route("/admin/reporte/moto_mas_reparada", name="reporte_moto_mas")
     */
    public function motoMasReparadaaAction(Request $request)
    {
        // Ruta ('/admin/reporte/moto_mas_reparada')
        // Hace un reporte de la moto con mayor número de reparaciones

        // Creamos una consulta SQL
        $sql = "SELECT reparacion.moto_id AS id, COUNT(reparacion.id) AS cantidad, " .
            "SUM(reparacion.precio) AS monto FROM reparacion " .
            "GROUP BY reparacion.moto_id ORDER BY cantidad DESC LIMIT 1";

        // Obtenemos la entidad de conexión a la base de datos
        $em = $this->getDoctrine()->getManager();

        // Conectamos ala base de datos
        $connection = $em->getConnection();

        // Agregamos la consulta
        $statement = $connection->prepare($sql);

        // Ejecutamos la consulta
        $statement->execute();

        // Obtenemos los resultados
        $resultados = $statement->fetchAll()[0];

        // Obtenemos la moto con mayor numero de reparaciones
        $moto = $this->getDoctrine()
            ->getRepository('AppBundle:Moto')
            ->find($resultados['id']);

        // Enviamos a la vista las variables
        // 'moto'       => la moto con mayor número de reparaciones
        // 'resultados' => Array que contiene la cantidad y el monto total
        return $this->render('reporte/moto_mas_reparada.html.twig', array(
            'moto'       => $moto,
            'resultados' => $resultados,
        ));
    }

    /**
     * @Route("/admin/reporte/servicios_mas", name="reporte_servicios_mas")
     */
    public function ServicioMasAction(Request $request)
    {   
        // Ruta ('/admin/reporte/servicios_mas')
        // Hace un reporte que lista los servicios de forma descendente 
        // más usados

        // Creamos una consulta SQL
        $sql = "SELECT reparacion.servicios , COUNT(reparacion.id) AS cantidad, " .
            "SUM(reparacion.precio) AS monto FROM reparacion " .
            "GROUP BY reparacion.servicios ORDER BY cantidad DESC";

        // Obtenemos la entidad de conexión a la base de datos
        $em = $this->getDoctrine()->getManager();

        // Conectamos ala base de datos
        $connection = $em->getConnection();

        // Agregamos la consulta
        $statement = $connection->prepare($sql);

        // Ejecutamos la consulta
        $statement->execute();

        // Obtenemos los resultados
        $resultados = $statement->fetchAll();

        // Enviamos a la vista las variables
        // 'resultados' => Array que contiene los servicios, la cantidad de
        // reparaciones y el monto recaudado por cada una de los servicios
        return $this->render('reporte/servicios_mas.html.twig', array(
            'resultados' => $resultados
        ));
    }

    /**
     * @Route("/admin/reporte/listar_trabajadores", name="reporte_trabajadores_listar")
     */
    public function listaTrabajadoresAction(Request $request)
    {
        // Ruta ('/admin/reporte/listar_trabajadores')
        // Hace un reporte que lista los trabajadores activos en la empresa

        // Obtenemos el modelo 'User' que será usado para las consultas
        $repository = $this->getDoctrine()->getRepository('AppBundle:User');

        // Creamos una consulta de todos los usuarios activos
        $query = $repository->createQueryBuilder('u')
            ->where("u.is_active = 1")
            ->getQuery();

        // Obtemos los usuarios activos
        $resultados = $query->getResult();

        // Enviamos ala vista los usuarios
        return $this->render('reporte/listar_trabajadores.html.twig', array(
            'resultados' => $resultados
        ));
    }

}