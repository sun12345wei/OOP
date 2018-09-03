<?php





// 把内存中的浏览量回写

function displayToDb()
{
    // 1. 先取出内存中所有的浏览量
    // 连接  Redis
    $redis = new \Predis\Client([
        'scheme' => 'tcp',
        'host' => '127.0.0.1',
        'port' => 32768,
    ]);

    $data = $redis->hgetall('blog_displays');
}

// 2、更新回数据库
foreach($data as $k => $v)
{
    $id = str_replace;
}