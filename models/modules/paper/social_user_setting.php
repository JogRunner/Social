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

	$code = get_argg('code');
	if(!empty($code))
	save_weixin_session($code);

	$user_id  = get_sess_userid();

	if(empty($user_id) && $local_debug)
	{
		set_sess_username("FanJian");
		set_sess_userid("2");
	}
	
	$user_id = get_sess_userid();
	$user_name = get_sess_username();
	$user_ico = get_sess_userico();

	if(empty($user_id))
	{
		header("location:error.php");
		exit;
	}

	if($main_key == "show_user_send_papers")
	{
		//用户发出的纸条
		$data=api_proxy('paper_get_user_send', $user_id);
	}
	elseif($main_key == "show_user_comments")
	{
		$data=api_proxy('paper_related_get_user_comments', $user_id);
	}elseif($main_key == "user_money")
	{
		$data=api_proxy('money_get_user_money', $user_id);

		$user_point=api_proxy('money_get_user_point', $user_id);
		set_session('user_point',$user_point);
	}elseif ($main_key == 'exchange_money') 
	{
		$sub_key = get_argg('sub_key');
		$user_point=get_session('user_point');
		if($sub_key == 'exchange_result')
		{
			$exchange_money = get_argg('exchange_money');	
		}
	}elseif($main_key == 'show_user_unread')
	{
		$data = api_proxy('paper_get_user_send', $user_id);
		$temp = api_proxy('paper_related_get_private_comments', $user_id);
		foreach ($$temp as $key => $value) {
			$data[] = $value;
		}
		foreach ($data as $key => $value) {
			$data[$key]['comments'] = api_proxy('paper_get_comments', $value['paper_id']);
		}
	}


	$unread_count = api_proxy('paper_related_get_user_unreaded_count', $user_id);
?>