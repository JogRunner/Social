<?php
	//引入语言包
	$pu_langpackage=new publiclp;

	$user_id = get_session('user_id');
	$user_point = get_session('user_point');

	echo $user_point;

	//兑换红包消耗10点积分
	$consume_point = 10;
	//使用积分兑换红包
	$user_point -= $consume_point;

	echo $user_point;

	global $tablePreStr;
	$t_users = $tablePreStr."users";
	$t_money = $tablePreStr."money";
	$result_rs=array();
	//更新数据库记录
	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('w', $dbServs);
	//update isns_users set user_point=2000 where user_id=1;
	$update_user_point_sql = "update $t_users set user_point=$user_point where user_id=$user_id";
	if($dbo->exeUpdate($update_user_point_sql))
	{
		
		//更新session值
		set_session('user_point', $user_point);
		//用户兑换记录
		$exchange_money = rand(5, 100);
		$exchange_datetime = date("Y-m-d H:i:s", time());

		//insert into isns_money (user_id, consume_point, exchange_datetime, exchange_money) value (1, 10, '2015-08-11 15:55:23', 33);
		$sql = "insert into $t_money (user_id, consume_point, exchange_datetime, exchange_money) 
				value ($user_id, $consume_point, '$exchange_datetime', $exchange_money)";

		if($dbo->exeUpdate($sql)){
			action_return(1,'', 'modules.php?app=user_settings&main_key=exchange_money&sub_key=exchange_result&exchange_money='.$exchange_money);
		}else{
			action_return(0,'error','-1');
		}
	}else{
		action_return(0,'error','-2');
	}

?>