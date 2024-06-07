<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;

class Covid19 {

  public function __construct()
	{
    $this->url = 'https://disease.sh';
    $this->params = '/v3/covid-19/countries/russia';
	}

  public function data()
  {
    $covid19data = null;
    $statusCode = null;
    $error = null;
    $client = new Client(['base_uri' => $this->url]);

    try {
      $response = $client->request('GET', $this->params, [
        'http_errors' => false
      ]);
      $covid19data = json_decode((string) $response->getBody(), true);
      $statusCode = $response->getStatusCode();

    } catch (ConnectException $e) {
      $error = Psr7\str($e->getRequest());
      if ($e->hasResponse()) {
        $error = Psr7\str($e->getResponse());
      }
    } catch (RequestException $e) {
      $error = Psr7\str($e->getRequest());
      if ($e->hasResponse()) {
        $error = Psr7\str($e->getResponse());
      }
    }

    return [
      'data' => $covid19data,
      'httpStatus' => $statusCode,
      'error' => $error
    ];
  }
}