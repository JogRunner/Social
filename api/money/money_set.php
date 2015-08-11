<?php 
	
	//修改用户
	function money_set_add_user_money($user_id, $consume_point, $exchange_datetime, $exchange_money)
	{
		global $tablePreStr;
		$t_money = $tablePreStr."money";
		$result_rs=array();
		$dbo=new dbex;
		dbplugin('w');
		
		//insert into isns_money (user_id, consume_point, exchange_datetime, exchange_money) value (1, 10, '2015-08-11 15:55:23', 33);
		$sql = "insert into $t_money (user_id, consume_point, exchange_datetime, exchange_money) 
			value ($user_id, $consume_point, '$exchange_datetime', $exchange_money)";
		$result_rs = $dbo->exeUpdate($sql);
		return $result_rs;
	}
	
?>