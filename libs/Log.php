<?php
namespace libs;

class Log
{
    // 参数：日志文件名
    public function __construct($fileName)
    {
        // 打开日志文件
        $this->fp = fopen(ROOT . 'logs/'.$fileName.'.log','a');
    }

    // 向日志文件中追加内容
    public function log($content)
    {
        // 获取当前时间
        fwrite($this->fp, $content . "\r\n");
    }
}