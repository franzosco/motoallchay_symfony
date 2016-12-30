<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class ReporteController extends Controller
{
    /**
     * @Route("/reporte", name="reportes_list")
     */
    public function indexAction(Request $request)
    {
        return $this->render('reporte/reportes.html.twig');
    }

    /**
     * @Route("/reporte/monto_dia", name="reporte_monto_dia")
     */
    public function montoDiaAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Reparacion');
        
        // Configuramos la zona horaria 
        date_default_timezone_set('America/Lima');

        // Obtenemos la fecha de hoy
        $hoy = date("Y-m-d");

        $query = $repository->createQueryBuilder('r')
            ->select("COUNT(r.id) AS cantidad, SUM(r.precio) AS monto")
            ->where("r.fecha_entrega LIKE '$hoy%'")
            ->getQuery();

        $resultados = $query->getResult();

        return $this->render('reporte/monto_dia.html.twig', array(
            'resultados' => $resultados[0], 'hoy' => $hoy
        ));
    }

    /**
     * @Route("/reporte/moto_mas_reparada", name="reporte_moto_mas")
     */
    public function motoMasReparadaaAction(Request $request)
    {
        $sql = "SELECT reparacion.moto_id AS id, COUNT(reparacion.id) AS cantidad, " .
            "SUM(reparacion.precio) AS monto FROM reparacion " .
            "GROUP BY reparacion.moto_id ORDER BY cantidad DESC LIMIT 1";

        $em = $this->getDoctrine()->getManager();

        $connection = $em->getConnection();

        $statement = $connection->prepare($sql);

        $statement->execute();

        $resultados = $statement->fetchAll()[0];

        $moto = $this->getDoctrine()
            ->getRepository('AppBundle:Moto')
            ->find($resultados['id']);

        return $this->render('reporte/moto_mas_reparada.html.twig', array(
            'resultados' => $resultados, 'moto' => $moto
        ));
    }

    /**
     * @Route("/reporte/servicios_mas", name="reporte_servicios_mas")
     */
    public function ServicioMasAction(Request $request)
    {
        $sql = "SELECT reparacion.servicios , COUNT(reparacion.id) AS cantidad, " .
            "SUM(reparacion.precio) AS monto FROM reparacion " .
            "GROUP BY reparacion.servicios ORDER BY cantidad DESC";

        $em = $this->getDoctrine()->getManager();

        $connection = $em->getConnection();

        $statement = $connection->prepare($sql);

        $statement->execute();

        $resultados = $statement->fetchAll();

        return $this->render('reporte/servicios_mas.html.twig', array(
            'resultados' => $resultados
        ));
    }

    /**
     * @Route("/reporte/listar_trabajadores", name="reporte_trabajadores_listar")
     */
    public function listaTrabajadoresAction(Request $request)
    {

        $repository = $this->getDoctrine()->getRepository('AppBundle:Usuario');

        $query = $repository->createQueryBuilder('u')
            ->where("u.active = 1")
            ->getQuery();

        $resultados = $query->getResult();

        return $this->render('reporte/listar_trabajadores.html.twig', array(
            'resultados' => $resultados
        ));
    }

}