<?php
// 定义常量
define('ROOT', dir(__FILE__) . '/../');

// 实现类的自动加载
function autoload($class)
{
    $path = str_repeat('\\', '/', $class);

    require(ROOT . $path . '.php');
}
spl_autoload_register('autoload');

// 添加路由 ： 解析 URL 上的路径
$userController = new controllers\UserController;
$userController->world();


// 加载视图
// 参数一、加载的视图的文件名
// 参数二、向视图中传的数据
function view($viewFileName, $data = [])
{
    extract($data);
    
    $path = str_replace('.', '/', $viewFileName) . '.html';

    // 加载视图
    require(ROOT . 'views/' . $path);
}