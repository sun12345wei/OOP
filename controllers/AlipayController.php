<?php
/* 支付宝支付的控制器 */
namespace controllers;

use Yansongda\Pay\Pay;

class AlipayController
{
    // 登录账号 buftrb3324@sandbox.com
    // 配置
    public $config = [
        'app_id' => '2016091600526975',       
        // 支付宝公钥
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAw+MyPcDB2kTumX4ZcEP5XAplAvapKy4LzWetE1iBTRex3rERdJIu48QG9OWpM+RwFZGCvckVBiNuOlNAjJ/YNLY+oigztgRDmNfe1O6awVe1DvlYbCBt0O51MktGrNlBVLbFZCp4zQhfnN5ZOSX6goWP0qEyaD4XO5N4NLdziOlWpgVA0SXFOGnvnIR9TqHGdeosa0ZwpgdjZzMDIfzzXub2j/7M4+mFcNGimv1fYZ9Ihd4zvcb+TD36MEssK0KtY4O2xsvp5iKDOt9Q3hPaS5SpmOfYkMQ7GowICWUP+CSrJZl9FlQVaCPM+iNkrooVBY0z8SyZgxo9paIyd7quwwIDAQAB',
        // 商户应用密钥
        'private_key' => 'MIIEpQIBAAKCAQEAxPjtg2Gq1J97UIGJVQF3wWuMaESZa9S4mQltXZoYf3GIfmHJjvigBawA9DPfGuktabgaprdBdiK87L+BlD3jjBgoIpE38WH9CF0FJNUGAnCjGM4JNPPZT1qzTgH9OWztuZdSfmgbI0uOGtRf0SIWRxVr84n1W7jFQEzy41N4DaE+ms0OqDf7bGLl3iGiOqFWb9Jzj5qAdfNvl5trKA0nKGeFs3oqel3N/4gRWCWiw9gr69BjYrwlcrJww/DVjfsT/QnUIPkhv8fIi6cGSmAzv5kRou8MsyX6QcO4195DlT5lIyPV9eQXLmcIM4AozPnBL6t2w835nkQ/UArm1hX7RQIDAQABAoIBAQCgfEVgt3HtvWdGx9QyK3j8YflLdyEdx3sETbcK/aOaKtHQTzJfR9lx5Zv9XEoabgQvT+5/CW7PpywRJVOZifddTM1epL1U8catSsUHJn23/TgV3MGJoGjdjAPtuhcs88CZZ16yWXZLAqNs3r6W8dP+5MhI7I25xskIQ+neKEL3rouYwHzvNP3MpxQvGBaMVGIWaFZdv+14eOOTaZFSLM08eFhWCxhsCKtCVkKx9tGR+QsidKe53oDn9lszX1JnEmRRGWJ8B3ExOkL+puudKcMy9yImukyMIB06i+WIE+TfxOZicxKhvpuvek0hdOWy3oZqMghWmvduRESUvdLdWOFBAoGBAOzHv6G3Dcusfqc1E2VTxYu3WHES/bmFzOYFOj6agBPotswI1lbPT3nkE6/0ncncSbwyXy9XvOMQBV6lLkRz/9WWOT2uC1C/SPn338jQNCdlZIa4in2apaT+0hYHv0IDltVwtem5GXvfx8RJSOV6NZujgoYAHjeMVSsVRYGw3BVRAoGBANT1+nTDUoBmjjx5LbzuUtYpJ4omZnsZrUVN/RDmmXFEnvB99v2szjGwaQl5nYxkxnFRiDSSlnZZL9pjU0TMdrfvJqz8H1IwqoKwZoiURTKBq44iGt1k0DzRLg2XWtbEbBZrVnCmxwENBG5jePtwZM0wuKknbhX+JQK02cDkhhm1AoGBAMnRZ/E6ybN0j2+Nub6ALLztxaB7g3ACL6dPhVme7tQJYuV+EtNjWGaYlH9BjMxlyyNx+9eqfQE8wpbtTAZkqQcwSBHeqx5kLJuDP2c2r3102r5JxSFSEyYTXJYSfF6UGJhMvYE9mA8RqoAPxuZxyAek0rfMmfTW1DEorFnimXRBAoGAQmmrf7piAjT5apaH0wGnx63x7L1o/D7XsGNP5nNjqtUxk+JUomu/qfNP7mqE3YGX+ULp7StBzQqnR73t++ifpWDpeMzvn5wZsMP+Vd2XbtbNf3fXVO/ZmP3LFOX8qDl9U8iJnnwEV6mNljdjRVaZuPvWurnXNPia5issNWGPCHkCgYEA6ir1NdYIfAWV9RyeL+ceuizhG6qE+qhTPN5Y3PVyMWgikL4p2+/Mq3n9GTTdVVpPGcX4x9AbMLLj7f9PJY9MoNC6rRSEBp6yATZ/+Ed57zoHVpWOPik7IKNHGv9cTdboeEMtW15am9zgaoeoPx/yF7uHvggPs5Q1+aaJn699Huo=',
       
        // 通知地址
        'notify_url' => 'http://requestbin.fullcontact.com/1gv8put1',
        // 跳回地址
        'return_url' => 'http://localhost:9999/alipay/return',
        // 沙箱模式（可选）
        'mode' => 'dev',
    ];

    // 跳转到支付宝
    public function pay()
    {
        // 接收订单编号
        $sn = $_POST['sn'];
        // 取出订单信息
        $order = new \models\Order;
        // 根据订单编号取出订单信息
        $data = $order->findBySn($sn);

        // 如果订单还未支付就跳到支付宝
        if( $data['status'] == 0 )
        {
            // 跳转到支付宝
            $alipay = Pay::alipay($this->config)->web([
                'out_trade_no' => $sn,
                'total_amount' => $data['money'],
                'subject' => '智聊系统用户充值 : '.$data['money'].'元',
            ]);
            $alipay->send();
        }
        else
        {
            die('订单状态不允许支付~');
        }
    }
    // 支付完成跳回
    public function return()
    {
        // 验证数据是否是支付宝发过来
        $data = Pay::alipay($this->config)->verify();

        echo '<h1>支付成功！</h1> <hr>';
        
        var_dump( $data->all() );
    }
    // 接收支付完成的通知
    public function notify()
    {
        // 生成支付类的对象
        $alipay = Pay::alipay($this->config);

        try{
            // 判断信息是否是支付宝发过来的，以及判断这个信息有没有被中途串改，如果被改了就抛出异常
            $data = $alipay->verify(); // 是的，验签就这么简单！

            // 支付成功
            if($data->trade_status == 'TRADE_SUCCESS' || $data->trade_status == 'TRADE_FINISHED')
            {
                // 更新订单状态
                $order = new \models\Order;
                // 获取订单信息
                $orderInfo = $order->findBySn($data->out_trade_no);
                // 如果订单的状态为未支付状态，说明是第一次收到消息，更新订单状态
                if($orderInfo['status'] == 0)
                {
                    // 开启事务
                    $order->startTrans();

                    // 设置订单为已支付状态
                    $ret1 = $order->setPaid($data->out_trade_no);
                    // 更新用户余额
                    $user = new \models\User;
                    $ret2 = $user->addMoney($orderInfo['money'], $orderInfo['user_id']);

                    // 判断
                    if($ret1 && $ret2)
                    {
                        // 提交事务
                        $order->commit();
                    }
                    else
                    {
                        // 回滚事务
                        $order->rollback();
                    }
                }
            }
        } catch (\Exception $e) {
            die('非法的消息');
        }

        // 回应支付宝服务器（如何不回应，支付宝会一直重复给你通知）
        $alipay->success()->send();
    }

    // 退款
    public function refund()
    {
        // 生成唯一退款订单号（以后使用这个订单号，可以到支付宝中查看退款的流程）
        $refundNo = md5( rand(1,99999) . microtime() );

        try{
            $order = [
                'out_trade_no' => '1536565087',    // 退款的本地订单号
                'refund_amount' => 0.01,              // 退款金额，单位元
                'out_request_no' => $refundNo,     // 生成 的退款订单号
            ];

            // 退款
            $ret = Pay::alipay($this->config)->refund($order);

            if($ret->code == 10000)
            {
                echo '退款成功！';
            }
            else
            {
                echo '失败';
                var_dump($ret);
            }
        }
        catch(\Exception $e)
        {
            var_dump( $e->getMessage() );
        }
    }
}