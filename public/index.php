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


$userController = new controllers\UserController;
$userController->hello();




function view($a,$b)
{
    
}