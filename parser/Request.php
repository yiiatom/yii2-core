<?php

namespace atom\parser;

use Yii;

class Request
{
    public $userAgent = '';

    public $referer = '';

    public $cookies = [];

    public $code;

    public $headers;

    public static function request($options = [])
    {
        $options = array_replace([
            'method' => 'GET', // GET|POST
            'url' => '',
            'returnTransfer' => true,
            'timeout' => 120,
            'userAgent' => '',
            'referer' => '',
            'fields' => '', // string|array (request uses multipart/form-data for array, and application/x-www-form-urlencoded for string)
            'followLocation' => true,
            'cookies' => [],
            'headers' => [],
        ], $options);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $options['url']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, $options['returnTransfer']);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $options['timeout']);
        curl_setopt($ch, CURLOPT_TIMEOUT, $options['timeout']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        if ($options['userAgent']) {
            curl_setopt($ch, CURLOPT_USERAGENT, $options['userAgent']);
        }
        if ($options['referer']) {
            curl_setopt($ch, CURLOPT_REFERER, $options['referer']);
        }
        if ($options['method'] == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $options['fields']);
        }
        if ($options['followLocation']) {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        }
        if ($options['cookies']) {
            $parts = [];
            foreach ($options['cookies'] as $key => $value) {
                $parts[] = "{$key}=" . urlencode($value);
            }
            curl_setopt($ch, CURLOPT_COOKIE, implode('; ', $parts));
        }
        if ($options['headers']) {
            $parts = [];
            foreach ($options['headers'] as $key => $value) {
                $parts[] = "{$key}: {$value}";
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $parts);
        }

        // Response headers
        $headers = [];
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, function($curl, $header) use (&$headers) {
            $len = strlen($header);
            $header = explode(':', $header, 2);
            if (count($header) == 2) {
                $headers[strtolower(trim($header[0]))][] = trim($header[1]);
            }
            return $len;
          }
        );

        // Decode response
        curl_setopt($ch, CURLOPT_ENCODING, true);
        $content = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Response cookies
        $cookies = [];
        if (isset($headers['set-cookie'])) {
            foreach ($headers['set-cookie'] as $v) {
                $parts = explode(';', $v, 2);
                if (count($parts) == 2) {
                    $p = explode('=', $parts[0], 2);
                    if (count($p) == 2) {
                        $cookies[$p[0]] = urldecode($p[1]);
                    }
                }
            }
        }

        return [
            'code' => $code,
            'headers' => $headers,
            'cookies' => $cookies,
            'content' => $content,
        ];
    }

    public function reset()
    {
        $this->referer = '';
        $this->cookies = [];
    }

    public function get($url, $useCache = true)
    {
        $cache_key = 'curl_' . md5($url);
        $response = Yii::$app->cache->get($cache_key);

        if ($response === false || !$useCache) {
            $response = self::request([
                'method' => 'GET',
                'url' => $url,
                'userAgent' => $this->userAgent,
                'referer' => $this->referer,
                'cookies' => $this->cookies,
            ]);

            if ($response['code'] == 200) {
                Yii::$app->cache->set($cache_key, $response, 12 * 3600);
            }
        }

        $this->referer = $url;
        $this->code = $response['code'];
        $this->headers = $response['headers'];
        $this->cookies = array_replace($this->cookies, $response['cookies']);

        return $response['content'];
    }

    public function post($url, $data, $headers = [])
    {
        $response = self::request([
            'method' => 'POST',
            'url' => $url,
            'userAgent' => $this->userAgent,
            'referer' => $this->referer,
            'fields' => $data,
            'cookies' => $this->cookies,
            'headers' => $headers,
        ]);
        $this->code = $response['code'];
        $this->referer = $url;
        $this->headers = $response['headers'];
        $this->cookies = array_replace($this->cookies, $response['cookies']);
        return $response['content'];
    }

    public static function getUrlParams($url)
    {
        $url_components = parse_url($url);
        parse_str($url_components['query'], $params);
        return $params;
    }
}
