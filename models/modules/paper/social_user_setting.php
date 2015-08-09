<?php

	$cur_menu = '3';
	$main_key = get_argg('main_key');
	if(!$main_key) $main_key = "show_user_send_papers";

	require("foundation/asession.php");
	require("configuration.php");
	require("includes.php");
	require("foundation/module_users.php");
	require("foundation/fcontent_format.php");
	require("foundation/fplugin.php");
	require("api/base_support.php");

	//引入语言包
	$pu_langpackage=new publiclp;

	$user_id = 1
	
	if($main_key == "show_user_send_papers")
	{
		//用户发出的纸条
		$data=api_proxy('paper_get_user_send', $user_id);
	}
	else if($main_key == "show_user_comments")
	{
		$data=api_proxy('paper_related_get_user_comments', $user_id);
	}elseif($main_key == "user_money")
	{
		$data=api_proxy('money_get_user_money', $user_id)
		$user_point=api_proxy('money_get_user_point', $user_id)
	}

	function get_status($status_code)
	{
		if($status_code)
		{
			switch ($status_code) {
				case '0': return '已完成';
					# code...
					break;
				case '1': return '等待接受';
					break;
				case '2': return '已接受，等待确认';
					break;
				case '3': return '已确认帮助者';
					break; 
				default:  return '未知状态';
					# code...
					break;
			}
		}
		return '未知状态';
	}

	function get_reply_str($status_code)
	{
		if($status_code)
		{
			switch ($status_code) {
				case '0': return '接受';
					# code...
					break;
				
				default: return '未接受';
					# code...
					break;
			}
		}
	}
?>