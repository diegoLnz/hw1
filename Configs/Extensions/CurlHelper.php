<?php

class CurlHelper 
{
    private $ch;

    public function __construct() 
    {
        $this->ch = curl_init();
    }

    private function setOptions($url, $method, $headers = [], $data = null) 
    {
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, $method);

        if (!empty($headers)) {
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);
        }

        if (!empty($data)) {
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
        }
    }

    public function get($url, $headers = []): bool|string
    {
        $this->setOptions($url, 'GET', $headers);
        return $this->execute();
    }

    public function post($url, $data, $headers = ['Content-Type: application/json']): bool|string
    {
        $this->setOptions($url, 'POST', $headers, $data);
        return $this->execute();
    }

    public function put($url, $data, $headers = ['Content-Type: application/json']): bool|string
    {
        $this->setOptions($url, 'PUT', $headers, $data);
        return $this->execute();
    }

    public function delete($url, $headers = []): bool|string
    {
        $this->setOptions($url, 'DELETE', $headers);
        return $this->execute();
    }

    private function execute(): bool|string
    {
        $response = curl_exec($this->ch);
        $error = curl_error($this->ch);

        if ($error) {
            return 'Curl error: ' . $error;
        }

        return $response;
    }

    public function close() 
    {
        curl_close($this->ch);
    }
}
