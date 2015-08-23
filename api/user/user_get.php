<?php
	//获得用户名
	function user_get_user_name($user_id)
	{
		global $tablePreStr;
		$t_users = $tablePreStr."users";
		$result_rs=array();
		$dbo=new dbex;
		dbplugin('r');
		
		$sql = "select user_name from $t_users where user_id=$user_id";
		$result_rs = $dbo->getRow($sql);
		return $result_rs;
	}

	//获得用户头像
	function user_get_user_ico($user_id)
	{
		global $tablePreStr;
		$t_users = $tablePreStr."users";
		$result_rs=array();
		$dbo=new dbex;
		dbplugin('r');
		
		$sql = "select user_ico from $t_users where user_id=$user_id";
		$result_rs = $dbo->getRow($sql);
		return $result_rs;
	}
?>