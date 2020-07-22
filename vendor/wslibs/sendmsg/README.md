[toc]
## 短信服务：

使用之前务必初始化sign(全局有效,项目唯一码获取方式为在后台注册获得或者联系管理员)

```php
use msg\SendMsg;

SendMsg::setSign('您的项目唯一码');
```

### 一，发送验证码 

(验证码类短信也属于变量短信,属于特殊的变量短信,需要在后台和短信商报备)

如下代码 返回true为成功 false为失败


```php
use msg\SendMsg;

/**
*用户手机号
*/
SendMsg::sendCodeMsg('17796908132');
```

### 二，校验验证码 

如下代码 返回true为验证码正确 false为验证码错误

```php
use msg\SendMsg;

/**
*用户手机号
*用户填写的验证码
*/
SendMsg::checkCode('177****8132','679478');
```


### 三，发送变量短信(此功能旨在提高短信发送效率)

(变量短信模板需要先在短信商和后台报备)

如下代码 返回true为成功 false为失败

```php
use msg\SendMsg;

/**
*用户手机号
*要使用的模板类型
*规定类型数组数组 模板所需的变量 顺序必须严格按照模板中*号的顺序排列
*/
SendMsg::sendTemMsg('177****8132','xx',[]));
```


### 四，发送其他短信

(发送任意内容的短信)

如下代码 返回true为成功 false为失败

```php
use msg\SendMsg;

/**
*用户手机号
*要发送的短信内容
*/
SendMsg::sendOtherMsg('177****8132','xxxxxxxxx');
```