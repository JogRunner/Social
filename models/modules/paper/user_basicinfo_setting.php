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

	$user_info = api_proxy('paper_related_get_user_info', $user_id);

	if(empty($user_info))
	{
		echo "<script>alert('发生错误')</script>";
		exit;
	}
?>