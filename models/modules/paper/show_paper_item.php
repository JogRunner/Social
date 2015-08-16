<?php
	require("foundation/asession.php");
	require("configuration.php");
	require("includes.php");
	require("foundation/module_users.php");
	require("foundation/fcontent_format.php");
	require("foundation/fplugin.php");
	require("api/base_support.php");

	//引入语言包
	$pu_langpackage=new publiclp;

	$cur_user = 1;
	$paper_id = get_argg('paper_id');

	$content = array();
	$content = api_proxy('paper_get_content', $paper_id);
	$comment = api_proxy('paper_get_comments',$paper_id);

	if($content['user_id'] == $cur_user)
		$main_key = "show_user_send_paper_item";
	else 
		$main_key = "show_user_comment_item";
	
	$content['comments'] = $comment;

	$data = array($content);
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