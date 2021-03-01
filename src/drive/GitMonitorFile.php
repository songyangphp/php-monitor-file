<?php

namespace wslibs\php_monitor_file\drive;

use wslibs\php_monitor_file\i\IMonitorFile;
use wslibs\php_monitor_file\MonitorFile;

class GitMonitorFile implements IMonitorFile
{
    public function getChangeFiles()
    {
        $return = [];
        $cmd = "git -C ".MonitorFile::$_root_dir." status -s";
        exec($cmd,$return);
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
                $file_name = explode(DIRECTORY_SEPARATOR,realpath($b[1]));
                $file_name = end($file_name);
                if(in_array($file_name, MonitorFile::$_ignore_file)){
                    continue;
                }

                if($b[0] == "M"){   //文件修改
                    $change_files[] = ["type" => "change", "file" => realpath($b[1])];
                }else if($b[0] == "??"){   //文件新增
                    $change_files[] = ["type" => "add", "file" => realpath($b[1])];
                }else if($b[0] == "D"){   //文件删除
                    $change_files[] = ["type" => "del", "file" => realpath($b[1])];
                }
            }
        }

        return $change_files;
    }
}