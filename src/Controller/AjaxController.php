<?php

namespace App\Controller;

use App\Entity\Currency;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AjaxController extends AbstractController
{
    /**
     * @Route("/ajax", name="ajax")
     */
    public function index()
    {
      $em    = $this->getDoctrine()->getManager();
      $currencies= $em->getRepository(Currency::class)->findAll();
        return $this->render('ajax/index.html.twig', [
            'controller_name' => 'AjaxController',
          'currencies'=>$currencies
        ]);
    }
}
