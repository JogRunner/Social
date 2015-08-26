<?php
	require("api/base_support.php");
	//引入语言包
	$pu_langpackage=new publiclp;
	//变量取得
	$least_paper_id= get_argg("least_paper_id");
	//返回数量
	$return_num = 10;

	$result = array();

	if($least_paper_id <= 0)
	{
		echo json_encode($result);
	}

	//数据表定义区
	$t_papers 	= $tablePreStr."papers";
	$t_users	= $tablePreStr."users";

	$dbo = new dbex;
	dbplugin('r');

	$get_more_papers_sql = "select $t_papers.*, $t_users.user_name from $t_papers, $t_users where $t_users.user_id=$t_papers.user_id and $t_papers.paper_id < $least_paper_id limit $return_num";
	//						  select isns_papers.*, isns_users.user_name from isns_papers, isns_users where isns_users.user_id=isns_papers.user_id and isns_papers.paper_id < 222239 limit 2;
	//$get_more_papers_sql = "select * from $t_papers where $t_papers.paper_id < $least_paper_id limit $return_num";
	
	$result = $dbo->getALL($get_more_papers_sql);

	$result = calc_all_distance($result);

	echo json_encode($result);
?>
