<?php
	header("content-type:text/html;charset=utf-8");

	require("foundation/asession.php");
	require("configuration.php");
	require("includes.php");
	require("foundation/module_users.php");
	require("foundation/fcontent_format.php");
	require("foundation/fplugin.php");
	require("api/base_support.php");

	$user_id  = get_sess_userid();

	if(empty($user_id) && $local_debug)
	{
		set_sess_username("FanJian");
		set_sess_userid("2");
	}
	
	$user_id = get_sess_userid();
	$user_name = get_sess_username();
	$user_ico = get_sess_userico();
    $other_user_id = get_argg('other_user_id');

	if(empty($user_id))
	{
		header("location:error.php");
		exit;
	}

	$is_visitor = false;
	if($other_user_id != null && $user_id != $other_user_id)
	{
		$is_visitor = true;
	}

	if($is_visitor == true)
	{
		$user_info = api_proxy('paper_related_get_user_info', $other_user_id);
	}else{
		$user_info = api_proxy('paper_related_get_user_info', $user_id);
	}

	if(empty($user_info))
	{
		echo "<script>alert('发生错误')</script>";
		exit;
	}
?>