<?php
class Blog
{
    /***************常量*****************/
    const BLOG_COUNT = 100;
    const VERSION = '1.0';

    /***************属性*****************/
    // 公有属性
    public $title;
    public $content;

    /***************方法*****************/
    // 公有方法
    public function getTitle()
    {
        echo 'getTitle';
    }
    public function getContent()
    {
        echo 'getContent';
    }
}