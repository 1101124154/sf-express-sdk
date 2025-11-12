<?php
namespace Wangwei\SfExpress;

/**
 * 顺丰（丰桥）客户端接口
 *
 * 定义与开放平台交互的统一方法签名。
 */
interface ClientInterface
{
    /**
     * 统一调用入口
     *
     * @param string $serviceCode 官方服务编码（如 EXP_RECE_CREATE_ORDER 等）
     * @param array  $payload     业务请求数据（将被 JSON 编码为 msgData）
     * @return array              解码后的响应数组；若非 JSON 响应，返回形如 ["raw" => string]
     */
    public function call(string $serviceCode, array $payload): array;
}