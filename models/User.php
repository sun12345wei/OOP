<?php
namespace models;

class User extends Base
{
    // 设置头像
    public function setAvatar($path)
    {
        $stmt = self::$pdo->prepare('UPDATE users SET avatar=? WHERE id=?');
        $stmt->execute([
            $path,
            $_SESSION['id']
        ]);
    }

    public function add($email,$password)
    {
        $stmt = self::$pdo->prepare("INSERT INTO users (email,password) VALUES(?,?)");
        return $stmt->execute([
                                $email,
                                $password,
                            ]);
    }

    public function login($email, $password)
    {
        // 根据 email 和 password 查询数据库
        $stmt = self::$pdo->prepare("SELECT * FROM users WHERE email=? AND password=?");
        // 执行 SQL
        $stmt->execute([
            $email,
            $password
        ]);
        // 取出数据
        $user = $stmt->fetch();
        // 是否有这个账号
        if( $user )
        {
            // 登录成功，把用户信息保存到 SESSION
            $_SESSION['id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['money'] = $user['money'];
            $_SESSION['avatar'] = $user['avatar'];
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    // 为用户增加金额
    public function addMoney($money, $userId)
    {
        $stmt = self::$pdo->prepare("UPDATE users SET money+? WHERE id=?");
        return $stmt->execute([
            $money,
            $userId
        ]);
    }

    // 获取余额
    public function getMoney()
    {
        $id = $_SESSION['id'];
        // 查询数据库
        $stmt = self::$pdo->prepare('SELECT money FROM users WHERE id = ?');
        $stmt->execute([$id]);
        $money = $stmt->fetch( PDO::FETCH_COLUMN );
        // 更新到 SESSION 中
        $_SESSION['money'] = $money;
        return $money;
    }

    // 测试事物
    public function trans()
    {
        // 开启事务
        self::$pdo->exec('start transaction');

        // 执行多个 SQL 
        $ret1 = self::$pdo->exec("update users set email='abc@126.com' where id=2");
        $ret2 = self::$pdo->exec("update users set email='bcd@126.com',money='536.34' where id=3");

        // 只有都成功时才提交事务，否则回滚事务
        if($ret1 !== FALSE && $ret2 !== FALSE)
            self::$pdo->exec('commit');    // 提交事务
        else
            self::$pdo->exec('rollback');  // 回滚事务
    }

    public function getAll()
    {
        $stmt = self::$pdo->query('SELECT * FROM users');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}