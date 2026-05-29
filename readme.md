# 魔方财务 网关原路退款插件 [迷你哆云](https://www.miniduo.cn) 

> public/plugins/addons/ 放到这个目录

> 免预存招代理 Q:1283187190

## 原理说明

> 原路退款时会收集必要参数并调用对应*支付网关*的 refund 函数，需要支付网关提供退款接口

```php
zjmfhook(
                '网关名称',
                'gateways',
                ['out_refund_no' => $out_refund_no, 'transaction_id' => $transaction_id, 'amount' => $amount, 'total_amount' => $trans['amount_in']],
                'refund' // 支付插件需实现 refund 函数
```

![1](https://github.com/M1niduo/zjmf-gateway-refund/blob/main/屏幕截图_30-5-2026_0390_www.miniduo.cn.jpeg)

- 注意这个可追溯的交易流水号

![2](https://github.com/M1niduo/zjmf-gateway-refund/blob/main/屏幕截图_30-5-2026_04236_www.miniduo.cn.jpeg)
