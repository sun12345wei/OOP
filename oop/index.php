<?php

// 引入类文件
require('blog.php');

// 实例化 blog 类
$blog = new Blog;

// 设置属性
$blog->title = '哈哈';
$blog->content = '你好'；

// 调用方法
$blog->getTitle();
$blog->getContent();

// 输出类常量
echo Blog::BLOG_COUNT;
echo Blog::VERSION;