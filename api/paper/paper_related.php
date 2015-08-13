<?php
	//获得用户参与的纸条信息
	function paper_related_get_user_comments($user_id)
	{
		global $tablePreStr;
		$t_papers=$tablePreStr."papers";
		$t_users = $tablePreStr."users";
		$t_comments = $tablePreStr."comments";
		$result_rs=array();
		$dbo=new dbex;
		dbplugin('r');

		$sql = "select * from $t_users,$t_papers,$t_comments where 
		$t_users.user_id = $t_papers.user_id and $t_papers.paper_id = $t_comments.paper_id 
		and $t_comments.commenter_id = $user_id";

		$result_rs = $dbo->getALL($sql);
		return $result_rs;
	}
?>