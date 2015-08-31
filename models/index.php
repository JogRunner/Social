<?php
	header("content-type:text/html;charset=utf-8");
	if(!file_exists('docs/install.lock')){
		header("location:install/index.php");
		exit;
	}
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
		set_sess_userid('2');
	}
	
	$user_id = get_sess_userid();
	$user_name = get_sess_username();
	
	if(empty($user_id))
	{
		header("location:error.php");
		exit;
	}
	
	$pu_langpackage=new publiclp;
	//获取所有纸条信息
	$data=api_proxy('paper_get_top_papers');

	$main_key = "show_all_papers";
?>