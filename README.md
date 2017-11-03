# msxiaoiceapi
[![Version](https://badge.fury.io/ph/kokororin%2Fmsxiaoice-php-api.svg)](https://packagist.org/packages/kokororin/kokororin/msxiaoice-php-api)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.6-8892BF.svg)](https://php.net/)

PHP implement of [yanwii/msxiaoiceapi](https://github.com/yanwii/msxiaoiceapi)

## Usage
```bash
$ composer require kokororin/msxiaoice-php-api:dev-master
```

```php
$api = new XiaoIceAPI(file_get_contents('/path/to/headers.txt'));
$api->chat('你是谁');
```
