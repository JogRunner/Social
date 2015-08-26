<?php
	header("content-type:text/html;charset=utf-8");

	require("foundation/asession.php");
	require("configuration.php");
	require("includes.php");
	require("foundation/module_users.php");
	require("foundation/fcontent_format.php");
	require("foundation/fplugin.php");
	require("api/base_support.php");

	//$user_id = get_sess_userid();

	//if(empty($user_id))
	{
		$code = get_argg('code');
		if(!empty($code))
			save_weixin_session($code);
	}
	if($local_debug)
	{
		set_sess_username("FanJian");
		set_sess_userid("2");
	}
	
	$user_id = get_sess_userid();
	$user_name = get_sess_username();
	
	if(empty($user_id))
	{
		header("location:error.php");
		exit;
	}

	//引入语言包
    $pu_langpackage=new publiclp;

	$user_id = get_sess_userid();
	//如果user_id为null判断为用户未登录，这时候需要跳转到登录界面
	$title_label = '写纸条';
?>