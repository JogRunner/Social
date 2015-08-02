<?php
	function paper_get_all_papers(){
		global $tablePreStr;
		$t_papers=$tablePreStr."papers";
		$t_users = $tablePreStr."users";
		$t_comments = $tablePreStr."comments";
		$result_rs=array();
		$dbo=new dbex;
	  	dbplugin('r');

		//$sql="select * from $t_papers";
	 	$sql = "select $t_papers.paper_id, $t_papers.user_id, $t_papers.content, $t_papers.picture, $t_users.user_name, $t_users.user_ico, $t_users.login_ip, 
	 			count($t_comments.paper_id) as count from $t_comments, $t_papers, $t_users
	 			where $t_comments.paper_id = $t_papers.paper_id and $t_users.user_id = $t_papers.user_id group by $t_comments.paper_id";
		$result_rs=$dbo->getALL($sql);

		return $result_rs;
	}

	function paper_get_user_send($userid){
		global $tablePreStr;
		$t_papers=$tablePreStr."papers";
		$t_users = $tablePreStr."users";
		$result_rs = array();
		$dbo = new dbex;
		dbplugin('r');

		$sql = "select * from $t_papers where $t_papers.user_id = $userid";
		$result_rs = $dbo->getALL($sql);
		return $result_rs;
	}
?>