<?php

$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => "https://api.paymongo.com/v1/links",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode([
    'data' => [
      'attributes' => [
        'amount' => 10000,
        'description' => 'Reserve an accommodation',
        'remarks' => 'Checkout link for gcash payment'
      ]
    ]
  ]),
  CURLOPT_HTTPHEADER => [
    "accept: application/json",
    "authorization: Basic " . base64_encode("sk_test_R8ftDrMbfgQWsjnHHJv51tNV"),
    "content-type: application/json"
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
  exit;
}

$data = json_decode($response, true);

if (isset($data['data']['attributes']['checkout_url'])) {
  $checkoutUrl = $data['data']['attributes']['checkout_url'];
  header("Location: $checkoutUrl");
  exit;
} else {
  echo "<h3>Failed to generate payment link</h3>";
  echo "<pre>";
  print_r($data);
  echo "</pre>";
}
