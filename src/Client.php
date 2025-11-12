<?php
namespace Wangwei\SfExpress;

/**
 * 顺丰（丰桥）开放平台客户端实现
 *
 * 负责配置管理、签名生成、HTTP 请求发送以及响应解析，
 * 提供统一的 `call(serviceCode, payload)` 调用入口。
 */
class Client implements ClientInterface
{
    /** 顾客编码（partnerID） */
    protected string $partnerId;
    /** 校验码（checkword） */
    protected string $checkword;
    /** 运行环境：sandbox|prod */
    protected string $env; // sandbox|prod
    /** 请求超时（秒） */
    protected int $timeout;
    /** 沙盒地址 */
    protected string $sandboxUrl = 'http://sfapi-sbox.sf-express.com/std/service';
    /** 生产地址 */
    protected string $prodUrl    = 'https://sfapi.sf-express.com/std/service';

    /**
     * 构造函数
     *
     * @param array $config 包含以下键：
     *  - partner_id (string) 顾客编码
     *  - checkword  (string) 校验码
     *  - env        (string) 环境：sandbox|prod，默认 sandbox
     *  - timeout    (int)    超时秒数，默认 30
     *  - sandbox_url (string, 可选) 覆盖沙盒地址
     *  - prod_url    (string, 可选) 覆盖生产地址
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

    /**
     * 统一调用入口：发送请求并解析响应
     *
     * @param string $serviceCode 官方服务编码（如 EXP_RECE_CREATE_ORDER 等）
     * @param array  $payload     业务请求数据，将以 JSON_UNESCAPED_UNICODE 编码为 msgData
     * @return array              成功时返回解码后的数组；若响应非 JSON，返回形如 ["raw" => string]
     */
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

    /**
     * 生成签名（与官方示例一致）
     *
     * 算法：`base64_encode(md5(urlencode(msgData + timestamp + checkword), true))`
     *
     * @param string $msgData   JSON 字符串
     * @param int    $timestamp 时间戳（秒）
     * @return string           msgDigest
     */
    protected function sign(string $msgData, int $timestamp): string
    {
        $md5bin = md5(urlencode($msgData . $timestamp . $this->checkword), true);
        return base64_encode($md5bin);
    }

    /**
     * 发送 HTTP POST 请求
     *
     * Content-Type: application/x-www-form-urlencoded;charset=utf-8
     *
     * @param string $url      请求地址（随环境选择）
     * @param array  $postData 表单数据
     * @return string          原始响应字符串（可能为 JSON 或其他格式）
     */
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

    /**
     * 生成简易 UUID（32 位 MD5 拼接为 8-4-4-4-12 格式）
     *
     * @return string UUID 字符串
     */
    protected function createUuid(): string
    {
        $chars = md5(uniqid(mt_rand(), true));
        return substr($chars, 0, 8) . '-' . substr($chars, 8, 4) . '-' . substr($chars, 12, 4)
            . '-' . substr($chars, 16, 4) . '-' . substr($chars, 20, 12);
    }
}