<?php

namespace wslibs\php_monitor_file\drive;

use wslibs\php_monitor_file\i\IMonitorFile;
use wslibs\php_monitor_file\MonitorFile;
use wslibs\php_monitor_file\MonitorTools;

class GitMonitorFile implements IMonitorFile
{
    public function getChangeFiles()
    {
        $return = [];
        exec("git status -s",$return);
        $change_files = [];
        if(!empty($return)){
            foreach ($return as $k => $v){
                $b = explode(" ",$v);
                foreach ($b as $key => $value){
                    if(empty($value) || $value == ""){
                        unset($b[$key]);
                    }
                }
                $b = array_values($b);
                if($b[0] == "M"){   //文件修改
                    $change_files[] = ["type" => "change", "file" => realpath($b[1])];
                }else if($b[0] == "??"){   //文件新增
                    $change_files[] = ["type" => "add", "file" => realpath($b[1])];
                }else if($b[0] == "D"){   //文件删除
                    $change_files[] = ["type" => "del", "file" => realpath($b[1])];
                }
            }
        }

        if(MonitorFile::$_send_msg_key && MonitorFile::$_phone){
            MonitorTools::doSend($change_files, MonitorFile::$_phone);
        }

        return $change_files;
    }
}