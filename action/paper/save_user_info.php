<?php
		//引入语言包
	$pu_langpackage=new publiclp;

	$user_id = get_sess_userid();
	$user_school = get_argp("user_school");
	$user_nickname = get_argp('user_nickname');

	
	if(empty($user_id))
		echo '1';
	else{

			//数据表定义区
		$t_users	= $tablePreStr."users";
		$dbo = new dbex;
		//增加评论数
    	dbtarget('w', $dbServs);

    	$updateSql = "update $t_users set user_nickname='$user_nickname',user_school='$user_school' where user_id=$user_id";
    	if($dbo->exeUpdate($updateSql))
    	{
    		set_session('user_school', $user_school);
    		echo '0';
    	}
    	else echo '1';
	}
?>