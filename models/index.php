<?php
	header("content-type:text/html;charset=utf-8");
	if(!file_exists('docs/install.lock')){
		header("location:install/index.php");
	}
	require("foundation/asession.php");
	require("configuration.php");
	require("includes.php");
	require("foundation/module_users.php");
	require("foundation/fcontent_format.php");
	require("foundation/fplugin.php");
	require("api/base_support.php");
	
	if(empty(get_sess_userid()))
	{
		$code = get_argg('code');
		if(!empty($code))
			save_weixin_session($code);
	}

	if(empty(get_sess_userid()))
	{
		header("location:error.php");
		exit;
	}
	echo get_sess_username();
	die;
	
	$pu_langpackage=new publiclp;
	//获取所有纸条信息
	$all_papers=api_proxy('paper_get_all_papers');
	//标签
	$title_label = '广场';

?>