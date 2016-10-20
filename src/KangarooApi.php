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
    }

    /**
     * @param array $options
     * @return mixed
     */
    public function me($options = [])
    {
        $result = $this->request('GET', '/me', $options);
        return $result['data'];
    }

    /**
     * @param array $options
     * @return mixed
     */
    public function getCustomers($options = [])
    {
        $result = $this->request('GET', '/customers', $options);
        return $result['data'];
    }

    /**
     * @param $id
     * @param $options
     * @return mixed
     */
    public function getCustomer($id = null, $options = [])
    {
        $result = $this->request('GET', '/customers/' . $id, $options);
        return $result['data'];
    }

    /**
     * @param $payload
     * @return mixed
     */
    public function createCustomer($data = [])
    {
        $result = $this->request('POST', '/customers', null, $data);
        return $result['data'];
    }

    /**
     * @param $id
     * @param $options
     * @return mixed
     */
    public function getCustomerTransactions($id = null, $options = [])
    {
        $result = $this->request('GET', '/customers/' . $id . '/transactions', $options);
        return $result['data'];
    }

    /**
     * @param array $options
     * @return mixed
     */
    public function getBranches($options = [])
    {
        $result = $this->request('GET', '/branches', $options);
        return $result['data'];
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
        return [
            'Content-Type' => 'application/json',
            'Accept' => $this->apiVersion,
            'Authorization' => 'Bearer ' . $this->token,
            'User-Agent' => $this->userAgent,
        ];
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

        $response = $http->request($type, $path, [
            'headers' => $this->getHeaders(),
            'json' => $data,
        ]);

        $body = (string) $response->getBody();
        $r = json_decode($body, true);

        if ($r === null) {
            throw new \Exception("Error: Recieved null result from API");
        }

        return $r;
    }
}
