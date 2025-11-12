<?php

return [
    // 顾客编码（partnerID）
    'partner_id' => getenv('SF_PARTNER_ID') ?: 'your-partner-id',

    // 校验码（checkword）
    'checkword'  => getenv('SF_CHECKWORD') ?: 'your-checkword',

    // 环境：sandbox 或 prod
    'env'        => getenv('SF_ENV') ?: 'sandbox',

    // 请求超时秒数
    'timeout'    => (int)(getenv('SF_TIMEOUT') ?: 30),

    // 可选：覆盖默认沙盒地址
    'sandbox_url' => getenv('SF_SANDBOX_URL') ?: 'http://sfapi-sbox.sf-express.com/std/service',

    // 可选：覆盖默认生产地址
    'prod_url'    => getenv('SF_PROD_URL') ?: 'https://sfapi.sf-express.com/std/service',
];