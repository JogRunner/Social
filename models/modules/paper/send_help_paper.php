<?php
	header("content-type:text/html;charset=utf-8");

	require("foundation/asession.php");
	require("configuration.php");
	require("includes.php");
	require("foundation/module_users.php");
	require("foundation/fcontent_format.php");
	require("foundation/fplugin.php");
	require("api/base_support.php");

	$code = get_argg('code');
	if(!empty($code))
		save_weixin_session($code);

	$user_id = get_sess_userid();

	if(empty($user_id) && $local_debug)
	{
		set_sess_username("FanJian");
		set_sess_userid("2");
	}
	
	$user_id = get_sess_userid();
	$user_name = get_sess_username();
	$user_ico = get_sess_userico();
	$user_school = get_session('user_school');
	
	if(empty($user_id))
	{
		header("location:error.php");
		exit;
	}

	//引入语言包
    $pu_langpackage=new publiclp;

	$user_id = get_sess_userid();

?>