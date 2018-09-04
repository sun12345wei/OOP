<?php
namespace controllers;

class TestController 
{
    public function testRedis()
    {
        // 连接 Redis
        $client = new \Predis\Client([
            'scheme' => 'tcp',
            'host'   => '127.0.0.1',
            'port'   => 32768,
        ]);

        // $client->set('name','tom');
        echo $client->get('name');
    }
}
