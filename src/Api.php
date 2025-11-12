<?php
namespace Wangwei\SfExpress;

/**
 * 业务方法封装层（API）
 *
 * 将常用业务动作封装为可读方法，并映射到官方 serviceCode，
 * 便于在业务层直接调用，无需记忆具体编码。
 */
class Api
{
    /** 底层客户端，用于实际发起请求 */
    protected ClientInterface $client;

    /**
     * @param ClientInterface $client 底层客户端实现（可通过依赖注入传入）
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * 下单
     * serviceCode: EXP_RECE_CREATE_ORDER
     * @param array $payload 下单请求数据
     * @return array 响应数据
     */
    public function createOrder(array $payload): array
    { return $this->client->call('EXP_RECE_CREATE_ORDER', $payload); }

    /**
     * 订单查询
     * serviceCode: EXP_RECE_SEARCH_ORDER_RESP
     */
    public function searchOrderResp(array $payload): array
    { return $this->client->call('EXP_RECE_SEARCH_ORDER_RESP', $payload); }

    /**
     * 订单确认/取消
     * serviceCode: EXP_RECE_UPDATE_ORDER
     */
    public function updateOrder(array $payload): array
    { return $this->client->call('EXP_RECE_UPDATE_ORDER', $payload); }

    /**
     * 订单筛选
     * serviceCode: EXP_RECE_FILTER_ORDER_BSP
     */
    public function filterOrderBsp(array $payload): array
    { return $this->client->call('EXP_RECE_FILTER_ORDER_BSP', $payload); }

    /**
     * 路由查询（按运单号）
     * serviceCode: EXP_RECE_SEARCH_ROUTES
     */
    public function searchRoutesByMailNo(array $payload): array
    { return $this->client->call('EXP_RECE_SEARCH_ROUTES', $payload); }

    /**
     * 路由查询（按订单号）
     * serviceCode: EXP_RECE_SEARCH_ROUTES
     */
    public function searchRoutesByOrderNo(array $payload): array
    { return $this->client->call('EXP_RECE_SEARCH_ROUTES', $payload); }

    /**
     * 申请子单
     * serviceCode: EXP_RECE_GET_SUB_MAILNO
     */
    public function getSubMailNo(array $payload): array
    { return $this->client->call('EXP_RECE_GET_SUB_MAILNO', $payload); }

    /**
     * 运单费用查询
     * serviceCode: EXP_RECE_QUERY_SFWAYBILL
     */
    public function querySfWaybillFees(array $payload): array
    { return $this->client->call('EXP_RECE_QUERY_SFWAYBILL', $payload); }

    /**
     * 路由注册
     * serviceCode: EXP_RECE_REGISTER_ROUTE
     */
    public function registerRoute(array $payload): array
    { return $this->client->call('EXP_RECE_REGISTER_ROUTE', $payload); }

    /**
     * 逆向单创建
     * serviceCode: EXP_RECE_CREATE_REVERSE_ORDER
     */
    public function createReverseOrder(array $payload): array
    { return $this->client->call('EXP_RECE_CREATE_REVERSE_ORDER', $payload); }

    /**
     * 逆向单取消
     * serviceCode: EXP_RECE_CANCEL_REVERSE_ORDER
     */
    public function cancelReverseOrder(array $payload): array
    { return $this->client->call('EXP_RECE_CANCEL_REVERSE_ORDER', $payload); }

    /**
     * 妥投通知
     * serviceCode: EXP_RECE_DELIVERY_NOTICE
     */
    public function deliveryNotice(array $payload): array
    { return $this->client->call('EXP_RECE_DELIVERY_NOTICE', $payload); }

    /**
     * 运单影像注册
     * serviceCode: EXP_RECE_REGISTER_WAYBILL_PICTURE
     */
    public function registerWaybillPicture(array $payload): array
    { return $this->client->call('EXP_RECE_REGISTER_WAYBILL_PICTURE', $payload); }

    /**
     * 改址拦截
     * serviceCode: EXP_RECE_WANTED_INTERCEPT
     */
    public function wantedIntercept(array $payload): array
    { return $this->client->call('EXP_RECE_WANTED_INTERCEPT', $payload); }

    /**
     * 预计时效查询
     * serviceCode: EXP_RECE_QUERY_DELIVERTM
     */
    public function queryDeliverTm(array $payload): array
    { return $this->client->call('EXP_RECE_QUERY_DELIVERTM', $payload); }

    /**
     * 云打印运单
     * serviceCode: COM_RECE_CLOUD_PRINT_WAYBILLS
     */
    public function cloudPrintWaybills(array $payload): array
    { return $this->client->call('COM_RECE_CLOUD_PRINT_WAYBILLS', $payload); }

    /**
     * 路由上传
     * serviceCode: EXP_RECE_UPLOAD_ROUTE
     */
    public function uploadRoute(array $payload): array
    { return $this->client->call('EXP_RECE_UPLOAD_ROUTE', $payload); }

    /**
     * 承诺时效查询
     * serviceCode: EXP_RECE_SEARCH_PROMITM
     */
    public function searchPromitm(array $payload): array
    { return $this->client->call('EXP_RECE_SEARCH_PROMITM', $payload); }

    /**
     * 上门揽收时段查询
     * serviceCode: EXP_EXCE_CHECK_PICKUP_TIME
     */
    public function checkPickupTime(array $payload): array
    { return $this->client->call('EXP_EXCE_CHECK_PICKUP_TIME', $payload); }

    /**
     * 运单号校验
     * serviceCode: EXP_RECE_VALIDATE_WAYBILLNO
     */
    public function validateWaybillNo(array $payload): array
    { return $this->client->call('EXP_RECE_VALIDATE_WAYBILLNO', $payload); }
}