<?php

use Behat\Behat\Context\Context;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Class ApiContext
 */
class ApiContext implements Context
{
    private const AUTHORIZATION_HEADER = 'Bearer %s';
    private const AUTHENTICATION_URL = '/api/v1/login';
    private const JSON_CONTENT = 'application/json';

    /**
     * @var string
     */
    private $token;

    /**
     * @var
     */
    private $language;

    /**
     * @var string
     */
    private $host;

    /**
     * @var null|Response
     */
    private $response;

    /**
     * @param string $host
     */
    public function __construct(string $host)
    {
        $this->host = $host;
        $this->language = 'EN';
    }

    /**
     * @param string $username
     * @param string $password
     *
     * @throws GuzzleException
     *
     * @Given I login as :username with :password
     */
    public function iLogin(string $username, string $password): void
    {
        $this->post(
            self::AUTHENTICATION_URL,
            [
                'username' => $username,
                'password' => $password,
            ]
        )        ;

        $result = $this->getContent();

        if (!empty($result['token'])) {
            $this->token = $result['token'];
        }
    }

    /**
     * @param string $language
     *
     * @Given I use language :language
     */
    public function iUseLanguage(string $language): void
    {
        $this->language = $language;
    }

    /**
     * @param string      $url
     * @param mixed       $json
     * @param null|string $token
     *
     * @throws GuzzleException
     */
    public function post(string $url, $json, ?string $token = null): void
    {
        $client = new Client();

        $this->response = $client->request(
            'POST',
            $this->host.$url,
            [
                GuzzleHttp\RequestOptions::JSON => $json,
                GuzzleHttp\RequestOptions::HTTP_ERRORS => false,
                GuzzleHttp\RequestOptions::HEADERS => [
                    'Content-Type' => self::JSON_CONTENT,
                    'JWTAuthorization' => sprintf(self::AUTHORIZATION_HEADER, $token),
                ],
            ]
        );
    }

    /**
     * @param string      $url
     * @param mixed       $json
     * @param null|string $token
     *
     * @throws GuzzleException
     */
    public function put(string $url, $json, ?string $token = null): void
    {
        $client = new Client();

        $this->response = $client->request(
            'PUT',
            $this->host.$url,
            [
                GuzzleHttp\RequestOptions::JSON => $json,
                GuzzleHttp\RequestOptions::HTTP_ERRORS => false,
                GuzzleHttp\RequestOptions::HEADERS => [
                    'Content-Type' => self::JSON_CONTENT,
                    'JWTAuthorization' => sprintf(self::AUTHORIZATION_HEADER, $token),
                ],
            ]
        );
    }

    /**
     * @param string      $url
     * @param null|string $token
     *
     * @throws GuzzleException
     */
    public function delete(string $url, ?string $token = null): void
    {
        $client = new Client();

        $this->response = $client->request(
            'DELETE',
            $this->host.$url,
            [
                GuzzleHttp\RequestOptions::HTTP_ERRORS => false,
                GuzzleHttp\RequestOptions::HEADERS => [
                    'Content-Type' => self::JSON_CONTENT,
                    'JWTAuthorization' => sprintf(self::AUTHORIZATION_HEADER, $token),
                ],
            ]
        );
    }

    /**
     * @param string      $url
     * @param null|string $token
     *
     * @throws GuzzleException
     */
    public function get(string $url, ?string $token = null): void
    {
        $client = new Client();

        $this->response = $client->request(
            'GET',
            $this->host.$url,
            [
                GuzzleHttp\RequestOptions::HTTP_ERRORS => false,
                GuzzleHttp\RequestOptions::HEADERS => [
                    'Content-Type' => self::JSON_CONTENT,
                    'JWTAuthorization' => sprintf(self::AUTHORIZATION_HEADER, $token),
                ],
            ]
        );
    }

    /**
     * @Then I get token
     */
    public function iGetToken(): void
    {
        TestCase::assertNotNull($this->token);
    }

    /**
     * @param string $code
     *
     * @Then I get :code result code
     */
    public function iGetResultCode(string $code): void
    {
        $statusCode = $this->getResponse()->getStatusCode();
        $message = sprintf('Expect "%s" http code, given "%s", content: %s', $code, $statusCode, $this->response->getBody());

        TestCase::assertEquals($code, $statusCode, $message);
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return \json_decode((string) $this->response->getBody(), true);
    }

    /**
     * @return null|Response
     */
    public function getResponse(): ?Response
    {
        return $this->response;
    }

    /**
     * @return null|string
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }
}
