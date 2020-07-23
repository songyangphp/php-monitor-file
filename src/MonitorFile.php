<?php

namespace wslibs\php_monitor_file;


use wslibs\php_monitor_file\i\IMonitorFile;

class MonitorFile
{
    public static $_root_dir;

    public static $_dirs;
    public static $_log_dir;
    public static $_send_msg_key = null;
    public static $_phone = null;

    public function __construct()
    {
        MonitorFile::$_root_dir = $_SERVER['PHP_SELF'].DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR;
    }

    public function setDirs($_dirs)
    {
        MonitorFile::$_dirs = $_dirs;
        return $this;
    }

    public function setLogDir($_log_dir)
    {
        MonitorFile::$_log_dir = $_log_dir;
        return $this;
    }

    public function doCheck($type)
    {
        if($type == 2 && MonitorTools::isGit()){
            $class_name = "GitMonitorFile";
        }else{
            $class_name = "Md5MonitorFile";
        }

        $class_name = "\\wslibs\\php_monitor_file\\drive\\".$class_name;
        if(class_exists($class_name)){
            $drive_object = new $class_name();
            if($drive_object instanceof IMonitorFile){
                return $drive_object->getChangeFiles();
            }else{
                return [];
            }
        }else{
            return [];
        }
    }

    /**
     * 启动检测
     * @param $dirs string|array 要检测的目录
     * @param $log_dir string 日志存放目录
     * @param $type int 检测方式 1为文件加密检测法 2为利用git检测 系统会自动检测该项目是否使用git版本控制 如果不 则使用文件加密检测
     * @param $send_msg_sign string 短信提醒中短信平台的key 如不传入则不发送短信
     * @param $phone string 短信提醒向该手机号发送提醒短信 如不传入则不发送短信
     * @return array 返回结果
     */
    public static function start($dirs, $log_dir, $type = 1 ,$send_msg_sign = null, $phone = null)
    {
        MonitorFile::$_send_msg_key = $send_msg_sign;
        MonitorFile::$_phone = $phone;
        MonitorTools::initSendMsg(MonitorFile::$_send_msg_key);
        return (new MonitorFile())->setDirs($dirs)->setLogDir($log_dir)->doCheck($type);
    }
}