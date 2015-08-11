<?php
	//修改用户积分
	function user_set_update_user_point($user_id, $user_point)
	{
		global $tablePreStr;
		$t_users = $tablePreStr."users";
		$result_rs=array();
		$dbo=new dbex;
		dbplugin('w');
		
		//update isns_users set user_point=2000 where user_id=1;
		$sql = "update $t_users set user_point=$user_point where user_id=$user_id";
		$result_rs = $dbo->exeUpdate($sql);
		return $result_rs;
	}
?>