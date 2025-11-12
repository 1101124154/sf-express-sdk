<?php
namespace Wangwei\SfExpress;

class Api
{
    protected ClientInterface $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function createOrder(array $payload): array
    { return $this->client->call('EXP_RECE_CREATE_ORDER', $payload); }

    public function searchOrderResp(array $payload): array
    { return $this->client->call('EXP_RECE_SEARCH_ORDER_RESP', $payload); }

    public function updateOrder(array $payload): array
    { return $this->client->call('EXP_RECE_UPDATE_ORDER', $payload); }

    public function filterOrderBsp(array $payload): array
    { return $this->client->call('EXP_RECE_FILTER_ORDER_BSP', $payload); }

    public function searchRoutesByMailNo(array $payload): array
    { return $this->client->call('EXP_RECE_SEARCH_ROUTES', $payload); }

    public function searchRoutesByOrderNo(array $payload): array
    { return $this->client->call('EXP_RECE_SEARCH_ROUTES', $payload); }

    public function getSubMailNo(array $payload): array
    { return $this->client->call('EXP_RECE_GET_SUB_MAILNO', $payload); }

    public function querySfWaybillFees(array $payload): array
    { return $this->client->call('EXP_RECE_QUERY_SFWAYBILL', $payload); }

    public function registerRoute(array $payload): array
    { return $this->client->call('EXP_RECE_REGISTER_ROUTE', $payload); }

    public function createReverseOrder(array $payload): array
    { return $this->client->call('EXP_RECE_CREATE_REVERSE_ORDER', $payload); }

    public function cancelReverseOrder(array $payload): array
    { return $this->client->call('EXP_RECE_CANCEL_REVERSE_ORDER', $payload); }

    public function deliveryNotice(array $payload): array
    { return $this->client->call('EXP_RECE_DELIVERY_NOTICE', $payload); }

    public function registerWaybillPicture(array $payload): array
    { return $this->client->call('EXP_RECE_REGISTER_WAYBILL_PICTURE', $payload); }

    public function wantedIntercept(array $payload): array
    { return $this->client->call('EXP_RECE_WANTED_INTERCEPT', $payload); }

    public function queryDeliverTm(array $payload): array
    { return $this->client->call('EXP_RECE_QUERY_DELIVERTM', $payload); }

    public function cloudPrintWaybills(array $payload): array
    { return $this->client->call('COM_RECE_CLOUD_PRINT_WAYBILLS', $payload); }

    public function uploadRoute(array $payload): array
    { return $this->client->call('EXP_RECE_UPLOAD_ROUTE', $payload); }

    public function searchPromitm(array $payload): array
    { return $this->client->call('EXP_RECE_SEARCH_PROMITM', $payload); }

    public function checkPickupTime(array $payload): array
    { return $this->client->call('EXP_EXCE_CHECK_PICKUP_TIME', $payload); }

    public function validateWaybillNo(array $payload): array
    { return $this->client->call('EXP_RECE_VALIDATE_WAYBILLNO', $payload); }
}