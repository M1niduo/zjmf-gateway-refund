<?php
namespace addons\gateway_refund;

use app\admin\lib\Plugin;

class GatewayRefundPlugin extends Plugin
{
     // 插件基本信息 /*
    public $info = array(
        'name' => 'GatewayRefund',  // 插件英文名，改成你的插件英文就行了
        'title' => '网关原路退款插件',
        'description' => '网关原路退款插件，调用相应支付网关的 refund 函数',
        'status' => 1,  // 状态
        'author' => '<a href=\"https://www.miniduo.com\">迷你哆云</a>',  // 开发者
        'version' => '1.0',  // 版本号
        'module' => 'addons',  // 插件模块
        'lang' => [  // 一级菜单语言
            'chinese' => '网关原路退款插件',  // 中文
            'chinese_tw' => '网关原路退款插件',  // 台湾
            'english' => 'Gateway Refund Plugin',  // 英文
        ]
    );

    // 插件安装
    public function install()
    {
        // 安装成功返回true，失败false
        return true;
    }

    // 插件卸载
    public function uninstall()
    {
        return true;
    }

    /**
     * 监听 invoice_refunded 钩子
     */
    public function invoiceRefunded($param) {
        $invoiceid = $param['invoiceid'];
        $amount = $param['amount'];
        // 最新的一条
        $account = \think\Db::name('accounts')->where(['gateway' => '退款至接口', 'amount_out' => $amount, 'invoice_id' => $invoiceid])->order('pay_time', 'desc')->find();
        if (!$account) {
            return;
        }
        // 找到退款的那条数据
        $trans = \think\Db::name('accounts')->where(['id' => $account['refund']])->find();
        $out_refund_no = $account['id'];
        $gateway = $trans['gateway'];
        $transaction_id = $trans['trans_id'];
        // account_id 退款的 invoice_id
        try {
            $res = zjmfhook(
                $gateway,
                'gateways',
                ['out_refund_no' => $out_refund_no, 'transaction_id' => $transaction_id, 'amount' => $amount, 'total_amount' => $trans['amount_in']],
                'refund' // gateway插件的函数名称
            );
            active_log_final('原路退款返回：' . json_encode($res));
        } catch(\Exception $e) {
            active_log_final('原路退款报错：' . json_encode($res));
        }
    }
}