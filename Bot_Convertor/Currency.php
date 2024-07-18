<?php

declare(strict_types=1);

class Currency
{
    const string CB_RATE_API_URL = 'https://cbu.uz/uz/arkhiv-kursov-valyut/json/';
    private GuzzleHttp\Client $http;
    private PDO               $pdo;

    public function __construct()
    {
        $this->http = new GuzzleHttp\Client(['base_uri' => self::CB_RATE_API_URL]);
        $this->pdo  = DB::connect();
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getRates()
    {
        return json_decode($this->http->get('')->getBody()->getContents());
    }

    public function getUsd()
    {
        return $this->getRates()[0];
    }


    public function convert(
        int    $chatId,
        string $originalCurrency,
        string $targetCurrency,
        float  $amount
    ) {
        $now    = date('Y-m-d H:i:s');
        $status = "{$originalCurrency}2{$targetCurrency}";
        $rate   = $this->getUsd()->Rate;

        $stmt = $this->pdo->prepare("INSERT INTO users (chat_id, amount,status, created_at) VALUES (:chatId, :amount, :status, :createdAt)");
        $stmt->bindParam(':chatId', $chatId);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':createdAt', $now);
        $stmt->execute();

        if ($originalCurrency === 'usd') {
            $result = $amount * $rate;
        } else {
            $result = $amount / $rate;
        }

        $result = number_format($result, 0, '', '.');
return $result;
        if ($originalCurrency === 'usd') {
            return $result." $originalCurrency";
        }

        return $result." $targetCurrency";
    }
}