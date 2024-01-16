<?php
if (!function_exists('curl_request')) {
   function curl_request($method, $url, $payload = [])
   {
      $api_url = getenv("apiURL") . $url;
      $headers = array(
         "Cache-Control: no-cache",
         "Pragma: no-cache",
         'Content-Type: application/json'
      );

      $ch = curl_init();

      switch ($method) {
         case "POST":
            curl_setopt($ch, CURLOPT_POST, 1);
            if ($payload) {
               curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            }
            break;

         case "PUT":
            curl_setopt($ch, CURLOPT_PUT, 1);
            break;

         case "PATCH":
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            if ($payload) {
               curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            }
            break;

         case "DELETE":
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            break;

         default:
            if ($payload) {
               $api_url = sprintf("%s?%s", $api_url, http_build_query($payload));
            }
      }

      curl_setopt($ch, CURLOPT_URL, $api_url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
      curl_setopt($ch, CURLOPT_HEADER, 1);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
      curl_setopt($ch, CURLOPT_TIMEOUT, 50000); //timeout in seconds

      $response = curl_exec($ch);

      $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
      $header = substr($response, 0, $header_size);
      $body = substr($response, $header_size);
      $res = ["code" => $httpcode, "header" => $header, "body" => $body];

      curl_close($ch);

      return $res;
   }
}
