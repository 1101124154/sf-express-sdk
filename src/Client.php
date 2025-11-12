<?php
namespace Wangwei\SfExpress;

class Client implements ClientInterface
{
    protected string $partnerId;
    protected string $checkword;
    protected string $env; // sandbox|prod
    protected int $timeout;
    protected string $sandboxUrl = 'http://sfapi-sbox.sf-express.com/std/service';
    protected string $prodUrl    = 'https://sfapi.sf-express.com/std/service';

    /**
     * @param array $config [partner_id, checkword, env, timeout, sandbox_url?, prod_url?]
     */
    public function __construct(array $config)
    {
        $this->partnerId = (string)($config['partner_id'] ?? '');
        $this->checkword  = (string)($config['checkword'] ?? '');
        $this->env        = (string)($config['env'] ?? 'sandbox');
        $this->timeout    = (int)($config['timeout'] ?? 30);
        if (!empty($config['sandbox_url'])) {
            $this->sandboxUrl = (string)$config['sandbox_url'];
        }
        if (!empty($config['prod_url'])) {
            $this->prodUrl = (string)$config['prod_url'];
        }
    }

    public function call(string $serviceCode, array $payload): array
    {
        $msgData   = json_encode($payload, JSON_UNESCAPED_UNICODE);
        $requestID = $this->createUuid();
        $timestamp = time();
        $msgDigest = $this->sign($msgData, $timestamp);

        $postData = [
            'partnerID'   => $this->partnerId,
            'requestID'   => $requestID,
            'serviceCode' => $serviceCode,
            'timestamp'   => $timestamp,
            'msgDigest'   => $msgDigest,
            'msgData'     => $msgData,
        ];

        $url    = $this->env === 'prod' ? $this->prodUrl : $this->sandboxUrl;
        $result = $this->post($url, $postData);

        $decoded = json_decode($result, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }
        return ['raw' => $result];
    }

    protected function sign(string $msgData, int $timestamp): string
    {
        $md5bin = md5(urlencode($msgData . $timestamp . $this->checkword), true);
        return base64_encode($md5bin);
    }

    protected function post(string $url, array $postData): string
    {
        $postdata = http_build_query($postData);
        $options  = [
            'http' => [
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded;charset=utf-8',
                'content' => $postdata,
                'timeout' => $this->timeout,
            ]
        ];
        $context = stream_context_create($options);
        $result  = file_get_contents($url, false, $context);
        return $result === false ? '' : $result;
    }

    protected function createUuid(): string
    {
        $chars = md5(uniqid(mt_rand(), true));
        return substr($chars, 0, 8) . '-' . substr($chars, 8, 4) . '-' . substr($chars, 12, 4)
            . '-' . substr($chars, 16, 4) . '-' . substr($chars, 20, 12);
    }
}