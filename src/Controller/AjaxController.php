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
  public function getChartData(Request $request)
  {

    $requestedCurrencies = $request->request->get('currencies');
    $em = $this->getDoctrine()->getManager();
    $currencies = $em->getRepository(Currency::class)->findBy(['code' => $requestedCurrencies]);
    $result = $this->prepareData($currencies);
    return new JsonResponse(
      $result,
      JsonResponse::HTTP_CREATED
    );
  }

  private function prepareData($currencies)
  {
    $result = ['error' => 'no currencies found'];
    if ($currencies) {
      $result = [];
      foreach ($currencies as $currency) {
        $currencyData = [];
        $currencyData['name'] = $currency->getName();
        $currencyData['code'] = $currency->getCode();
        $em = $this->getDoctrine()->getManager();
        $currecyHistory = $em->getRepository(ExchangeRateHistory::class)->findBy(['currency'=>$currency],['date'=>'ASC']);
        foreach ($currecyHistory as $currencyDateData) {
          $curentData = [];
          $curentData['date'] = $currencyDateData->getDate()->format('Y-m-d');
          if ($currencyDateData->getMidPrice()) {
            $curentData['mid_price'] = $currencyDateData->getMidPrice();
          } else {
            $curentData['mid_price'] = ($currencyDateData->getBidPrice() + $currencyDateData->getAskPrice()) / 2;
          }
          $currencyData['history'][] = $curentData;
        }
        $result[] = $currencyData;
      }
    }
    return $result;
  }
}
