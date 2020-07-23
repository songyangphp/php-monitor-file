<?php


namespace wslibs\php_monitor_file;

class MonitorTools
{
    public static function isGit()
    {
        $cmd_result = [];
        exec("git status",$cmd_result);
        foreach ($cmd_result as $k => $v){
            if(empty($v)){
                unset($cmd_result[$k]);
            }
        }

        if(isset($cmd_result[2]) && $cmd_result[2] == "No commits yet"){
            return false;
        }else{
            return true;
        }
    }

    public static function writeLog($log, $log_dir)
    {
        if(!is_dir(MonitorFile::$_log_dir)){
            @mkdir(MonitorFile::$_log_dir,0755,true);
        }
        ob_start();
        echo "<?php return ";
        var_export($log);
        echo ";";
        file_put_contents($log_dir,ob_get_contents());
        ob_end_clean();
    }

    public static function sortFiles($files_array)
    {
        $array = [];
        array_walk_recursive($files_array,
            function ($x) use (&$array)
            { $array[] = $x; }
        );

        $return = [];
        foreach ($array as $k => $v){
            if(strpos($v,'monitor_log') !== false){
                continue;
            }
            $return[$v] = md5_file($v);
        }

        return $return;
    }

    public static function listDirFiles($dir)
    {
        $arr = [];
        if (is_dir($dir)) {//如果是目录，则进行下一步操作
            $d = opendir($dir);//打开目录
            if ($d) {//目录打开正常
                while (($file = readdir($d)) !== false) {//循环读出目录下的文件，直到读不到为止
                    if  ($file != '.' && $file != '..') {//排除一个点和两个点
                        $file = $dir.DIRECTORY_SEPARATOR.$file;
                        if (is_dir(realpath($file))) {//如果当前是目录
                            $arr[$file] = self::listDirFiles(realpath($file));//进一步获取该目录里的文件
                        } else {
                            $arr[] = $file;//记录文件名
                        }
                    }
                }
            }
            closedir($d);//关闭句柄
        }

        return $arr;
    }

    public static function getLog($log_file_path)
    {
        $log_file = $log_file_path;
        if(file_exists($log_file)){
            return include_once $log_file;
        }else{
            return [];
        }
    }

    public static function checkFile($new_log, $old_log)
    {
        $change_files = [];
        foreach ($new_log as $k => $v){
            if(!isset($old_log[$k])){
                $change_files[] = ["type" => "add", "file" => $k];
            }else if($v != $old_log[$k]){
                $change_files[] = ["type" => "change", "file" => $k];
            }
            unset($old_log[$k]);
        }

        if(!empty($old_log)){
            foreach ($old_log as $k => $v){
                $change_files[] = ["type" => "del", "file" => $k];
            }
        }

        return $change_files;
    }
}