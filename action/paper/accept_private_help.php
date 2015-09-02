<?php
	//引入语言包
	$pu_langpackage=new publiclp;
	//变量取得
	$private_id= get_argg("private_id");

	$user_id = get_sess_userid();

	if(empty($user_id))
	{
		header("location:error.php");
		exit;
	}
	
	$dbo = new dbex;
	//数据表定义区
	$t_comments	= $tablePreStr."comments";
	$t_papers 	= $tablePreStr."papers";

	dbplugin('r');
	$paperIdSql = "select paper_id from $t_comments where comment_id = $private_id";
	$res = $dbo->getRow($paperIdSql);
	$paper_id = $res['paper_id'];

	//读写分离定义函数
	dbtarget('w', $dbServs);
	$refuseAllPrivateSql = "update $t_comments set comment_status = 2 where paper_id = $paper_id and comment_type = 1";
	$paperStatusUpdate = "update $t_papers set paper_status = 2 where paper_id = $paper_id";
	$dbo->exeUpdate($refuseAllPrivateSql);
	$dbo->exeUpdate($paperStatusUpdate);
	
	/* update isns_papers set isns_papers.comment_count = 3 where isns_papers.paper_id=111114; */
	$update_comment_sql = "update $t_comments set comment_status = 1 where comment_id = $private_id and comment_type = 1";
	if($dbo->exeUpdate($update_comment_sql))
	{
		action_return(1, '', 'modules.php?app=user_settings&main_key=show_user_unread');
	}
	else{
		action_return(0, 'error', '-2');
	}
?>
