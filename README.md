[toc]
## PHP实现的网站挂马监视器：

此工具应用于服务器web项目的文件变化监测，一旦服务器文件被恶意篡改，可以及时知晓做出处理

### 一，检测文件变化

```php
require_once __DIR__ . '/vendor/autoload.php';
use wslibs\php_monitor_file\MonitorFile;

$files = [__DIR__.DIRECTORY_SEPARATOR."test",  __DIR__.DIRECTORY_SEPARATOR."test1"];
/*
    ps:建议加入定时任务，定期执行，执行结果可以去日志目录查看，短信通知，邮件通知等功能可以自行拓展

    $dirs array : 要监控的文件目录
    $log_dir string : 存放监控日志的目录
    $type int : 1:文件md5检测法 2:git检测法
    $_ignore_files_array array : 屏蔽的日志文件
*/
MonitorFile::start($files , __DIR__.DIRECTORY_SEPARATOR."log",1/*,['apidoc.json']*/);
```




