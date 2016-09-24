# ip_helper_for_ci
get userip, ip location helper for CI,  Support ci2, ci3, and ci4 PHP获取用户真实IP地址, 地理位置的辅助函数.
支持查询 淘宝, 新浪, pconline IP数据库信息, 可以随意却换.

#使用方法
将 ip_helper.php 下载后放到CI的 application 目录中的 helpers文件夹 , 如  application\helpers\ip_helper.php

##设置CI按需自动加载本辅助函数,
打开 application\config\autoload.php 文件, 在 $autoload['helper'] 这个数组里面增加 ip_helper.php辅助函数的加载, 注意,只需要添加 _helper.php 前面的文件名即可, 如 ip ,即表示加载 ip_helper.php, 如果有多个辅助函数需要加载的话,直接真加一个数组值即可, 如: $autoload['helper'] = array('ip','myhelper1','myhelper2');


/*
| -------------------------------------------------------------------
|  Auto-load Helper Files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['helper'] = array('url', 'file');
*/
$autoload['helper'] = array('ip');

#调用方法
加载后再任意视图,控制器和模型里面可以直接使用, 调用方式如下

 getIpLocation() 不传API服务商,将默认调用淘宝API
 

调用淘宝API查询IP地理位置
<?php echo getIpLocation('taobao'); ?>

调用新浪API查询IP地理位置
<?php echo getIpLocation('sina'); ?>


调用pconline API查询IP地理位置
<?php echo getIpLocation('pconline'); ?>


