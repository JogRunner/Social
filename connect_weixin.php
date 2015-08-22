<?php 
	header("content-type:text/html;charset=utf-8");

require("foundation/asession.php");
require("configuration.php");
require("includes.php");
require("foundation/module_users.php");
require("foundation/fcontent_format.php");
require("foundation/fplugin.php");
require("api/base_support.php");

//define your token
define("TOKEN", "whatareyounongshane");
$wechatObj = new wechat();

$wechatObj->createMenu();
$wechatObj->valid();
$wechatObj->responseMsg();
?>