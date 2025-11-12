<?php
namespace Wangwei\SfExpress;

interface ClientInterface
{
    public function call(string $serviceCode, array $payload): array;
}