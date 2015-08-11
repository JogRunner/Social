<?php
	//引入语言包
    $pu_langpackage=new publiclp;

	$user_id = get_session("user_id");
	//如果user_id为null判断为用户未登录，这时候需要跳转到登录界面
	
?>