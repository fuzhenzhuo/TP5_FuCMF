<?php
//支付配置
return [
    'ali_charge' => [
        'name' => '支付宝支付',
        'use_sandbox' => true,
        'partner' => '2088331615592618',
        'app_id' => '2019032863697573',
        'sign_type' => 'RSA2',
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAjMVfKAaM4OBwqNuMRlAAR5dauwdFAjXHgAEIyNl6sHpbxu5Y9Xn2KN45RLbdwryvAIUucstzbUcD9yJmMaZJI9mup71qySVuwa40Kf3jde/+zs7jPteOGkL7gMM7XtzdKPWB5/fD0Zm+gvXHJtaGQeRxMAdRa3bzNKynjY/tHPsvT7mIggeVWRYnCdcz/NvKJsxE0G+IoOShULeFOTsITO2mdGrDu9knFxN0+vwrm1o5elVvw9BJODRv3/l3CcYgbobUgtsgp1mV2jpyq3TU+opn90jp8Wfn4Bb+6kIHdJWA9x/bCswENUQPR3XNTaUdz4acUdodz3p6KDxRd2hxcQIDAQAB',
        'rsa_private_key' => Env::get('config_path').'security/alipay/private_key.txt',
//        'rsa_private_key' => Env::get('config_path').'security/alipay/private_key.txt',

        'limit_pay' => [
        ],
        'notify_url' => 'http://39.100.69.132/v1/testNotify/notifyProcess',
        'return_raw' => false,
    ],

    'wx_charge' => [
        'name' => '微信支付',
        //微信支付配置数组
        'app_id' => 'wxc4f5c531a9c17ab1',
        'mch_id' => '1504442721',
        'md5_key' => 'Hg3t5s7df0uoZotj7EvQKYULN7zLFUsc',
        'app_cert_pem' => Env::get('config_path').'security/wechat/apiclient_cert.pem',
        'app_key_pem' =>  Env::get('config_path').'security/wechat/apiclient_key.pem',
        'sign_type' => 'MD5',// MD5  HMAC-SHA256
        'limit_pay' => [
        ],
        'fee_type' => 'CNY',// 货币类型  当前仅支持该字段
        'notify_url' => 'http://39.100.69.132/v1/testNotify/notifyProcess',
        'return_raw' => false,
    ]
];
