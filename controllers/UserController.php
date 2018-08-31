<?php
namespace controllres;

// 引入模型类
use models\User;

class UserController
{
    public function hello()
    {
        // 取数据
        $user = new User;
        $user = $user->getName();

        // 加载视图
        view('users.hello', [
            'name' => $name
        ]);
    }
}