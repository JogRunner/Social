<?php

	//获取用户的红包信息
	function money_get_user_money($user_id)
	{
		global $tablePreStr;
		$t_money = $tablePreStr."money";
		$result_rs=array();
		$dbo=new dbex;
		dbplugin('r');
		/*select isns_money.*, isns_users.user_point from iwebsns.isns_money, isns_users where isns_users.user_id=isns_money.user_id and isns_money.user_id=1;*/
		$sql = "select $t_money.* from $t_money where $t_money.user_id=$user_id";
		$result_rs = $dbo->getALL($sql);
		return $result_rs;
		 
	}
	/*获得用户的积分数*/
	function money_get_user_point($user_id)
	{
		global $tablePreStr;
		$t_users = $tablePreStr."users";

		$result_rs=array();
		$dbo=new dbex;
		dbplugin('r');

		$sql = "select $t_users.user_point from $t_users where $t_users.user_id=$user_id";

		$result_rs = $dbo->getRow($sql);
		return $result_rs['user_point'];
	}

?>