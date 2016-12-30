<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SessionController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {   
		$helper = $this->get('security.authentication_utils');

	    return $this->render('session/index.html.twig', [
	        // last username entered by the user (if any)
	        'last_username' => $helper->getLastUsername(),
	        // last authentication error (if any)
	        'error' => $helper->getLastAuthenticationError(),
	    ]);
    }
}
