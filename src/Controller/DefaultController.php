<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/default", name="default")
     */
    public function index(Request $request)
    {
      $em    = $this->getDoctrine()->getManager();
      $dql   = "SELECT c FROM  App:Currency c";
      $query = $em->createQuery($dql);

      $paginator  = $this->get('knp_paginator');
      $pagination = $paginator->paginate(
        $query, /* query NOT result */
        $request->query->getInt('page', 1)/*page number*/,
        10/*limit per page*/
      );

        return $this->render('default/index.html.twig', [
          'pagination' => $pagination,
        ]);
    }
}
