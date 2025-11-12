# Wangwei SF Express SDK (Composer Package)

PSR-4 PHP SDK for SF Express (Fengqiao). Provides a thin client and readable API methods.

## Install (Path Repository for Local Dev)

In your application's `composer.json`:

```json
{
  "repositories": [
    { "type": "path", "url": "../SF-CSIM-EXPRESS-SDK-PHP-V2.1.2/sf-express-sdk", "options": { "symlink": true } }
  ],
  "require": {
    "wangwei/sf-express-sdk": "dev-main"
  }
}
```

Then run:

```bash
composer update wangwei/sf-express-sdk
```

Alternatively, publish this folder to its own git repository and add it as a VCS repo.

## Usage

```php
use Wangwei\SfExpress\Client;
use Wangwei\SfExpress\Api;

$config = [
    'partner_id' => 'your-partner-id',
    'checkword'  => 'your-checkword',
    'env'        => 'sandbox', // or 'prod'
    'timeout'    => 30,
];

$client = new Client($config);
$api = new Api($client);

// Raw call
$res = $client->call('EXP_RECE_VALIDATE_WAYBILLNO', [ 'waybillNo' => '1234567890' ]);

// Convenience API method
$res2 = $api->validateWaybillNo([ 'waybillNo' => '1234567890' ]);
```

## Notes

- Signature matches official sample: `base64_encode(md5(urlencode(msgData+timestamp+checkword), true))`
- `payload` is JSON encoded with `JSON_UNESCAPED_UNICODE`
- Select endpoint by `env` (sandbox/prod); override URLs via `sandbox_url`/`prod_url` in config