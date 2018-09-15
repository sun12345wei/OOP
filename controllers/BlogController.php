<?php
namespace controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use models\Blog;

class BlogController
{
    public function agreements_list()
    {
        $id = $_GET['id'];

        // 获取这个日志所有点赞的用户
        $model = new \models\Blog;
        $data = $model->agreeList($id);

        // 转成 JSON 返回
        echo json_encode([
            'status_code' => 200,
            'data' => $data,
        ]);
    }

    // 点赞
    public function agreements()
    {
        $id = $_GET['id'];
        // 判断登录
        if(!isset($_SESSION['id']))
        {
            echo json_encode([
                'status_code' => '403',
                'message' => '必须先登录'
            ]);
            exit;
        }

        // 点赞
        $model = new \models\Blog;
        $ret = $model->agree($id);
        if($ret)
        {
            echo json_encode([
                'status_code' => '200',
            ]);
            exit;
        }
        else
        {
            echo json_encode([
                'status_code' => '403',
                'message' => '已经点赞过了'
            ]);
            exit;
        }
    }

    // 获取最新的10个日志
    public function makeExcel()
    {
        // 获取当前标签页
        $spreadsheet = new Spreadsheet();
        // 获取当前工作
        $sheet = $spreadsheet->getActiveSheet();

        // 设置第1行内容
        $sheet->setCellValue('A1', '标题');
        $sheet->setCellValue('B1', '内容');
        $sheet->setCellValue('C1', '发表时间');
        $sheet->setCellValue('D1', '是发公开');

        // 取出数据库中的日志
        $model = new \models\Blog;
        // 获取最新的20个日志
        $blogs = $model->getNew();

        $i=2; // 第几行
        foreach($blogs as $v)
        {
            $sheet->setCellValue('A'.$i, $v['title']);
            $sheet->setCellValue('B'.$i, $v['content']);
            $sheet->setCellValue('C'.$i, $v['created_at']);
            $sheet->setCellValue('D'.$i, $v['is_show']);
            $i++;
        }

        $date = date('Ymd');

        // 生成 excel 文件
        $writer = new Xlsx($spreadsheet);
        $writer->save(ROOT . 'excel/'.date('y-').'.xlsx');

        // 调用 header 函数设置协议头，告诉浏览器开始下载文件

        // 下载文件路径
        $file = ROOT . 'excel/'.$date.'.xlsx';
        // 下载时文件名
        $fileName = '最新的20条日志-'.$date.'.xlsx';

        // 告诉浏览器这是一个二进程文件流   
        Header ( "Content-type: application/octet-stream" ); 
        // 请求范围的度量单位  
        Header ( "Accept-Ranges: bytes" );  
        // 告诉浏览器文件尺寸    
        Header ( "Accept-Length: " . filesize ( $file ) );  
        //开始下载，下载时的文件名
        Header ( "Content-Disposition: attachment; filename=" . $fileName );    

        // 读取服务器上的一个文件并以文件流的形式输出给浏览器
        readfile($file);
    }

    // 显示私有日志
    public function content()
    {
        // 1. 接收ID，并取出日志信息
        $id = $_GET['id'];
        $model = new Blog;
        $blog = $model->find($id);

        // 2. 判断这个日志是不是我的日志
        if($_SESSION['id'] != $blog['user_id'])
            die('无权访问！');

        // 3. 加载视图
        view('blogs.content', [
            'blog' => $blog,
        ]);
    }

    public function update()
    {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $is_show = $_POST['is_show'];
        $id = $_POST['id'];

        $blog = new Blog;
        $blog->update($title, $content, $is_show, $id);

        // 如果日志是公开的就生成静态页
        if($is_show == 1)
        {
            $blog->makeHtml($id);
        }
        else
        {
            // 如果改为私有，就要将原来的静态页删除掉
            $blog->deleteHtml($id);
        }

        message('修改成功！', 2, '/blog/index');
    }

    public function edit()
    {
        $id = $_GET['id'];
        // 根据ID取出日志的信息

        $blog = new Blog;
        $data = $blog->find( $id );

        view('blogs.edit', [
            'data' => $data,
        ]);

    }

    public function delete()
    {
        $id = $_POST['id'];

        $blog = new Blog;
        $blog->delete($id);

        // 静态页删除掉
        $blog->deleteHtml($id);

        message('删除成功',2,'/blog/index');
        
    }

    // 显示添加日志的表单
    public function create()
    {
        view('blogs.create');
    }

    public function store()
    {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $is_show = $_POST['is_show'];

        $blog = new Blog;
        // 添加新日志并返回 新日志的ID
        $blog->add($title,$content,$is_show);

        // 如果日志是公开的就生成静态页
        if($is_show == 1)
        {
            $blog->makeHtml($id);
        }

        // 跳转
        message('发表成功', 2, '/blog/index');
    }

    // 日志列表
    public function index()
    {
        $blog = new Blog;
        // 搜索数据
        $data = $blog->search();
        // 加载视图
        view('blogs.index', $data);
    }

    // 为所有的日志生成详情页
    public function content_to_html()
    {
        $blog = new Blog;
        $blog->content2html();
    }

    public function index2html()
    {
        $blog = new Blog;
        $blog->index2html();
    }

    public function display()
    {
        // 接收日志ID
        $id = (int)$_GET['id'];

        $blog = new Blog;

        // 把浏览量+1，并输出（如果内存中没有就查询数据库，如果内存中有直接操作内存）
        $display = $blog->getDisplay($id);

        // 返回多个数据时必须要用 JSON

        echo json_encode([
            'display' => $display,
            'email' => isset($_SESSION['email']) ? $_SESSION['email'] : '',
            'money' => $_SESSION['money'],
            'avatar' => $_SESSION['avatar']=='' ? '/images/avatar.jpg' : $_SESSION['avatar'],
        ]);
    }

    public function displayToDb()
    {
        $blog = new Blog;
        $blog->displayToDb();
    }
}