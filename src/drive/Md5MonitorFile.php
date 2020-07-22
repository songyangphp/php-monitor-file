<?php


namespace wslibs\php_monitor_file\drive;

use wslibs\php_monitor_file\i\IMonitorFile;
use wslibs\php_monitor_file\MonitorFile;
use wslibs\php_monitor_file\MonitorTools;

class Md5MonitorFile implements IMonitorFile
{
    public function getChangeFiles()
    {
        $dirs = [];
        if(!is_array(MonitorFile::$_dirs)){
            $dirs[] = MonitorFile::$_dirs;
        }else{
            $dirs = MonitorFile::$_dirs;
        }

        if(!$dirs) return false;

        $return = [];
        foreach ($dirs as $k => $v){
            if(!is_dir($v)) continue;
            $file_list = MonitorTools::sortFiles(MonitorTools::listDirFiles($v));
            $log_file_name = explode(DIRECTORY_SEPARATOR,$v);
            $log_file_path = MonitorFile::$_log_dir.DIRECTORY_SEPARATOR.end($log_file_name)."_monitor_log.php";

            if(!file_exists($log_file_path)){
                MonitorTools::writeLog($file_list,$log_file_path);
            }else{
                $old_log = MonitorTools::getLog($log_file_path);
                $new_log = $file_list;
                $check_file_list = MonitorTools::checkFile($new_log, $old_log);
                if(!empty($check_file_list)){
                    $return[end($log_file_name)] = $check_file_list;
                }
                MonitorTools::writeLog($new_log,$log_file_path);
            }
        }

        $array = [];
        foreach ($return as $k => $v){
            foreach ($v as $value){
                $array[] = $value;
            }
        }

        if(!empty(MonitorFile::$_send_msg_key) && !empty(MonitorFile::$_phone)){
            MonitorTools::doSend($array, MonitorFile::$_phone);
        }

        return $array;
    }
}