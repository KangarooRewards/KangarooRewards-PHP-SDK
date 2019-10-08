<?php

namespace KangarooRewards\Api;

class KangarooApi
{
    /**
     * @var mixed
     */
    private $token;

    /**
     * @var string
     */
    private $apiVersion = 'application/vnd.kangaroorewards.api.v1+json;';

    /**
     * @var string
     */
    private $baseApiUrl = 'https://api.kangaroorewards.com';

    /**
     * @var string
     */
    private $userAgent = 'API Client/1.0';

    /**
     * @var array
     */
    private $headers = [];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct($options)
    {
        if (empty($options['access_token'])) {
            throw new InvalidArgumentException('Required option not passed: "access_token"');
        }

        $this->token = $options['access_token'];

        if (!empty($options['base_api_url'])) {
            $this->baseApiUrl = $options['base_api_url'];
        }

        if (!empty($options['user_agent'])) {
            $this->userAgent = $options['user_agent'];
        }

        if(isset($options['headers'])) {
            $this->headers = $options['headers'];
        }
    }

    /**
     * @param array $options
     * @return mixed
     */
    public function me($options = [])
    {
        return $this->request('GET', '/me', $options);
    }

    /**
     * @param array $options
     * @return mixed
     */
    public function getCustomers($options = [])
    {
        return $this->request('GET', '/customers', $options);
    }

    /**
     * @param $id
     * @param $options
     * @return mixed
     */
    public function getCustomer($id, $options = [])
    {
        return $this->request('GET', '/customers/' . $id, $options);
    }

    /**
     * @param $payload
     * @return mixed
     */
    public function createCustomer($data = [])
    {
        return $this->request('POST', '/customers', null, $data);
    }

    /**
     * @param $id
     * @param $payload
     * @return mixed
     */
    public function updateCustomer($id, $data = [])
    {
        return $this->request('PUT', '/customers/' . $id, null, $data);
    }

    /**
     * @param $id
     * @param $options
     * @return mixed
     */
    public function getCustomerTransactions($id = null, $options = [])
    {
        return $this->request('GET', '/customers/' . $id . '/transactions', $options);
    }

    /**
     * @param $id
     * @param $options
     * @return mixed
     */
    public function getCustomerNotifications($id = null, $options = [])
    {
        return $this->request('GET', '/customers/' . $id . '/notifications', $options);
    }

    /**
     * @param array $options
     * @return mixed
     */
    public function getBranches($options = [])
    {
        return $this->request('GET', '/branches', $options);
    }

    /**
     * @param array $options
     * @return mixed
     */
    public function getOffers($options = [])
    {
        return $this->request('GET', '/offers', $options);
    }

    /**
     * @param array $options
     * @return mixed
     */
    public function getProducts($options = [])
    {
        return $this->request('GET', '/products', $options);
    }

    /**
     * @return mixed
     */
    protected function getBaseApiUrl()
    {
        return $this->baseApiUrl;
    }

    /**
     *  Returns headers used for request
     *
     * @return array
     */
    protected function getHeaders()
    {
        return array_merge([
            'Content-Type' => 'application/json',
            'Accept' => $this->apiVersion,
            'Authorization' => 'Bearer ' . $this->token,
            'User-Agent' => $this->userAgent,
        ], $this->headers);
    }

    /**
     * @param $type
     * @param $path
     * @param $options
     * @param array $data
     * @return mixed
     */
    protected function request($type, $path, $options = null, $data = [])
    {
        if (is_array($options)) {
            $qs = http_build_query($options);
            $path .= '?' . $qs;
        }

        $http = new \GuzzleHttp\Client(['base_uri' => $this->getBaseApiUrl()]);

        if ($data) {
            $response = $http->request($type, $path, [
                'headers' => $this->getHeaders(),
                'json' => $data,
            ]);
        } else {
            $response = $http->request($type, $path, [
                'headers' => $this->getHeaders(),
            ]);
        }

        $body = (string) $response->getBody();
        $r = json_decode($body, true);

        if ($r === null) {
            throw new \Exception("Error: Recieved null result from API");
        }

        return $r;
    }
}
