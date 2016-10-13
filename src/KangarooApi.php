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
    private $userAgent = '';

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
        $this->baseApiUrl = $options['base_api_url'];
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
     * @return mixed
     */
    public function getCustomer($id = null, $options = [])
    {
        $result = $this->request('GET', '/customers/' . $id, $options);
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

        $response = $http->request($type, $path, ['headers' => $this->getHeaders()]);

        // $httpCode = $response->getStatusCode();

        // if ($httpCode > 400) {
        //     $result = json_decode((string) $response->getBody());
        //     $errorMessage = 'Error: ' . $result->error . ' path: ' . $path;
        //     throw new \Exception($errorMessage, (int) $httpCode);
        // }

        $body = (string) $response->getBody();
        $r = json_decode($body, true);

        if ($r === null) {
            throw new \Exception("Error: Recieved null result from API");
        }

        return $r;
    }
}
