<?php

namespace wslibs\php_monitor_file;


use wslibs\php_monitor_file\i\IMonitorFile;

class MonitorFile
{
    public static $_root_dir;

    public static $_dirs;
    public static $_log_dir;

    public function __construct()
    {
        if(!empty($_SERVER['DOCUMENT_ROOT'])){
            MonitorFile::$_root_dir = $_SERVER['DOCUMENT_ROOT'];
        }else{
            MonitorFile::$_root_dir = $_SERVER['PHP_SELF'].DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR;
        }
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
        if(isset($_POST['_ping'])){
            echo "ping success";exit;
        }

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
     * @param $type int 检测方式 1为文件加密检测法 2为利用git检测 系统会自动检测该项目是否使用git版本控制 如果不 则使用文件加密检测法
     * @return array 返回结果
     */
    public static function start($dirs, $log_dir, $type = 1)
    {
        return (new MonitorFile())->setDirs($dirs)->setLogDir($log_dir)->doCheck($type);
    }
}