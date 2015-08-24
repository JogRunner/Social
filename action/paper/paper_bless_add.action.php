<?php
	//引入语言包
	$pu_langpackage=new publiclp;

	//数据表定义区
	$t_users	= $tablePreStr."users";
	$t_papers 	= $tablePreStr."papers";

	//变量取得
	$paper_id = intval(get_argp("paper_id"));
	$point_receiver_id 	= intval(get_argp("point_receiver_id"));
	$user_id = intval(get_argp('user_id'));
	$give_point = intval(get_argp('give_point'));

	$dbo = new dbex;
	//增加评论数
    //insert into isns_papers (user_id, content, picture, create_time) value (1, '纸条内容', '纸条路径', '2015-08-12 15:57:12');
    dbplugin('r');

	$check_sql = "select * from $t_papers where $t_papers.paper_id=$paper_id and $t_papers.user_id=$user_id";

	$result_rs = $dbo->getRow($check_sql);

	if(empty($result_rs))
	{
		action_return(0,'error','-1');
	}

	//读写分离定义函数
	dbtarget('w', $dbServs);
	/* update isns_papers set isns_papers.paper_status = 1 where isns_papers.paper_id=111114; */
	$update_paper_status_sql = "update $t_papers set $t_papers.paper_status=1, $t_papers.receiver_id=$point_receiver_id where $t_papers.paper_id=$paper_id";
	$result_rs = $dbo->exeUpdate($update_paper_status_sql);

	if($result_rs)
	{
		dbplugin('r');
		$query_receiver_point_sql = "select $t_users.user_point from $t_users where $t_users.user_id=$point_receiver_id";
		$result_rs = $dbo->getRow($query_receiver_point_sql);

		if(empty($result_rs))
		{
			action_return(0,'error','-2');
		}
		$receiver_point = $result_rs['user_point'];
		$receiver_point += $give_point;

		dbtarget('w', $dbServs);
		//插入纸条评论
		$add_user_point_sql = "update $t_users set $t_users.user_point=$receiver_point where $t_users.user_id=$point_receiver_id";

		if($dbo->exeUpdate($add_user_point_sql)){
			action_return(1,'','modules.php?app=pick_paper_detail&paper_id='.$paper_id);
		}else{
			action_return(0,'error','-3');
		}
	}
	else{
		action_return(0, 'error', '-4');
	}

?>