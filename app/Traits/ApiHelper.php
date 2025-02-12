<?php

namespace App\Traits;

trait ApiHelper
{
    /**
     * Perform login API request and return the token.
     */
    public function loginAPI()
    {
        $data = [
            "username" => "admin@brndaddo.com",
            "password" => "abcd1234"
        ];

        $response = $this->makePostRequest("https://somany-backend.brndaddo.ai/api/v1/login", $data);

        return $response['token'] ?? null;
    }

    /**
     * Perform a GET request.
     */
    public function makeGetRequest($url, $headers = [])
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $this->prepareHeaders($headers),
            CURLOPT_SSL_VERIFYPEER => $this->getSSLVerifier(),
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        return $error ? ['error' => $error] : json_decode($response, true);
    }

    /**
     * Perform a POST request.
     */
    public function makePostRequest($url, $data, $headers = [])
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => $this->prepareHeaders($headers),
            CURLOPT_SSL_VERIFYPEER => $this->getSSLVerifier(),
            CURLOPT_POSTFIELDS => json_encode($data),
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        return $error ? ['error' => $error] : json_decode($response, true);
    }

    /**
     * Get SSL Verifier (can be modified based on environment needs).
     */
    private function getSSLVerifier()
    {
        // Get the value of MY_CUSTOM_VAR from the .env file
        $customVar = config('app.curl'); // 'default_value' is the fallback in case MY_CUSTOM_VAR is not set
        return !(($customVar === "localhost"));
    }

    /**
     * Prepare headers for API requests.
     */
    private function prepareHeaders($extraHeaders = [])
    {
        $defaultHeaders = [
            "Content-Type: application/json",
        ];

        return array_merge($defaultHeaders, $extraHeaders);
    }

    private function mapFinishType($designFinish): string
    {
        $mapping = [
            'LUCIDO' => 'glossy',
            'FULL POLISHED' => 'glossy',
            'HIGH GLOSS FP' => 'glossy',
            'NANO' => 'glossy',
            'NANO FP' => 'glossy',
            'RUSTIC' => 'matt',
            'RUSTIC CARVING' => 'matt',
            'STONE' => 'matt',
            'WOOD' => 'glossy',
            'MATT' => 'matt',
            'GLOSSY' => 'glossy',
            'DAZZLE' => 'matt',
            'Metallic'=>'matt',
            'SUGAR HOME' => 'matt',
            'SATIN MATT' => 'matt',
            'SEMI GLOSSY' => 'matt',
            'MATT ENGRAVE' => 'matt',
            'PRM FULL POLISHED' => 'glossy',
            'ROTTO' => 'matt',
            'Lapato' => 'matt',
        ];
        return $mapping[$designFinish] ?? $designFinish; // Default to original value if not in mapping
    }

    private function mapCategoryType($brand_name): string
    {
        $mapping = [
            'Coverstone' => 'Large Format Slab',
            'Regalia Collection' => 'Large Format Tiles',
            'Porto Collection' => 'Large Format Tiles',
            'Sedimento Collection' => 'Large Format Tiles',
            'Colorato Collection' => 'Large Format Tiles',
            'Ceramica' => 'Ceramic',
            'Duragres' => ' Glazed Vitrified Tiles',
            'Vitro' => 'Polished Vitrified Tiles',
            'Durastone' => 'Heavy Duty Vitrified Tiles',
            'Italmarmi' => 'Subway Tiles',
        ];

        return $mapping[$brand_name] ?? $brand_name; // Default to original value if not in mapping
    }
}
