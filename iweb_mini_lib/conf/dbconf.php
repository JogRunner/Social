<?php
$host="localhost:3306";//mysql数据库服务器,比如localhost:3306
$user="test"; //mysql数据库默认用户名
$pwd="abccba"; //mysql数据库默认密码
$db="social"; //默认数据库名
global $tablePreStr;//设置外部变量
$tablePreStr="isns_";//表前缀

//当前提供服务的mysql数据库
global $dbServs;
$dbServs=array($host,$db,$user,$pwd);
?>