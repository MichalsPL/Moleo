<?php
/**
 * Created by PhpStorm.
 * User: michal
 * Date: 11/19/18
 * Time: 10:33 AM
 */

namespace App\Command;


class NBPCommand extends AbstractCommand
{

  protected function fetchData(): array
  {
    $tables = ['a', 'b', 'c'];
    $data = [];
    foreach ($tables as $table) {
      $xmlData = file_get_contents('http://api.nbp.pl/api/exchangerates/tables/' . $table . '/');
      $data[] = json_decode($xmlData, true);
    }
    return $data;
  }

  protected function prepareData(array $rawData): array
  {
    $preparedData = [];
    foreach ($rawData as $table) {
      foreach ($table[0]['rates'] as $rate) {
        $rate['name'] = $rate['currency'];
        unset($rate['currency']);
        if (isset($rate['mid'])) {
          $rate['ask'] = null;
          $rate['bid'] = null;
        } else {
          $rate['mid'] = null;
        }
        $preparedData[] = $rate;
      }
    }
    return $preparedData;
  }
}