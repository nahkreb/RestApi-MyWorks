<?php

// TCMB EVDS API key
$apiKey = 'YOUR_API_KEY';

// Seri adı (USD/TRY satış kuru)
$series = 'TP.DK.USD.S.YTL';

// Güncel tarih
$Today = date('d-m-Y');

// API URL
$url = "https://evds2.tcmb.gov.tr/service/evds/series=$series&startDate=$Today&endDate=$Today&type=json&aggregationTypes=avg&frequency=1";

// cURL oturumu başlatma
$ch = curl_init();

// cURL seçeneklerini ayarlama
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "key: $apiKey",
    "Content-Type: application/json"
));
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

// API çağrısını gerçekleştirme ve yanıtı al
$response = curl_exec($ch);

// cURL oturumunu kapatma
curl_close($ch);

// JSON yanıtını çözümleme
$data = json_decode($response, true);

// Gerekli veriyi çıkarma
$usdTryKuru = $data['items'][0]['TP_DK_USD_S_YTL'];

// Sonucu yazdırma
echo 'USD/TL '.$Today.' döviz kuru: ' . $usdTryKuru;

?>
