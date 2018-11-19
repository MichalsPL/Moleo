<?php

namespace App\Controller;

use App\Entity\Currency;
use App\Entity\ExchangeRateHistory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AjaxController extends AbstractController
{
  /**
   * @Route("/ajax/", name="ajax")
   */
  public function index()
  {
    $em = $this->getDoctrine()->getManager();
    $currencies = $em->getRepository(Currency::class)->findAll();
    return $this->render('ajax/index.html.twig', [
      'controller_name' => 'AjaxController',
      'currencies' => $currencies
    ]);
  }

  /**
   * @Route("/ajax/getCurrencies", name="getCurrencies")
   */
  public function getChartData(Request $request)
  {
    $result = [];
    $em = $this->getDoctrine()->getManager();
    $currencies = $em->getRepository(Currency::class)->findBy(['code' => ['USD', 'AUD']]);

    if (!$currencies) {
      return new JsonResponse(
        [
          'error' => 'no currencies found'
        ],
        JsonResponse::HTTP_CREATED
      );
    }

    foreach ($currencies as $currency) {
      $currencyData = [];
      $currencyData['name'] = $currency->getName();
      $currencyData['code'] = $currency->getCode();
      $currecyHistory = $em->getRepository(ExchangeRateHistory::class)->findResultBetweenDates('2018-11-15', '2018-11-19');
      $currencyDa = [];
      foreach ($currecyHistory as $currencyDateData) {
        $curentData = [];
        $curentData['date'] = $currencyDateData->getDate()->format('Y-M-d');
        $curentData['bid_price'] = $currencyDateData->getBidPrice();
        $curentData['ask_price'] = $currencyDateData->getAskPrice();
      }
      $currencyData['history'][] = $curentData;
      $result[]=$currencyData;
    }


    return new JsonResponse(

        $result
      ,
      JsonResponse::HTTP_CREATED
    );
  }
}
