<?php
	function paper_get_all_papers(){
		global $tablePreStr;
		$t_papers=$tablePreStr."papers";
		$t_users = $tablePreStr."users";
		$t_comments = $tablePreStr."comments";
		$result_rs=array();
		$dbo=new dbex;
	  	dbplugin('r');
	  	/*select isns_papers.*, isns_users.*, count(isns_comments.paper_id) as count 
		from isns_papers left join isns_comments on isns_comments.paper_id = isns_papers.paper_id left join isns_users on isns_users.user_id=isns_papers.user_id group by isns_papers.paper_id;*/
	 	$sql = "select 
	 	$t_papers.*, 
	 	$t_users.*, 
	 	count($t_comments.paper_id) as count 
		from $t_papers left join $t_comments on $t_comments.paper_id = $t_papers.paper_id 
		left join $t_users on $t_users.user_id=$t_papers.user_id group by $t_papers.paper_id;";
		$result_rs=$dbo->getALL($sql);

		return $result_rs;
	}

	//获得用户发出的纸条
	function paper_get_user_send($user_id){
		global $tablePreStr;
		$t_papers	= $tablePreStr."papers";
		$t_comments = $tablePreStr."comments";

		$result_rs 	= array();
		$dbo 		= new dbex;
		dbplugin('r');

		/*select isns_papers.*, 
(select count(isns_comments.comment_id) from isns_comments where isns_comments.paper_id=isns_papers.paper_id and isns_comments.comment_type=0) as public_count,
(select count(isns_comments.comment_id) from isns_comments where isns_comments.paper_id=isns_papers.paper_id and isns_comments.comment_type=1) as private_count  
from isns_papers where isns_papers.user_id = 1;*/
		$sql = "select $t_papers.*, 
(select count($t_comments.comment_id) from $t_comments where $t_comments.paper_id=$t_papers.paper_id and $t_comments.comment_type=0) as public_count,
(select count($t_comments.comment_id) from $t_comments where $t_comments.paper_id=$t_papers.paper_id and $t_comments.comment_type=1) as private_count  
from $t_papers where $t_papers.user_id = $user_id";

		$result_rs = $dbo->getALL($sql);
		return $result_rs;
	}

	//获得纸条的详细信息，不包括评论信息
	function paper_get_content( $paper_id ){
		global $tablePreStr;
		$t_papers 	= $tablePreStr."papers";
		$t_users	= $tablePreStr."users";

		$paper_detail_rs	= array();
		$dbo=new dbex;
	  	dbplugin('r');

	  	/*select isns_users.user_id, isns_users.user_name, isns_users.user_ico, isns_users.login_ip, isns_papers.paper_id, isns_papers.content, isns_papers.picture from isns_users, isns_papers where isns_users.user_id in 
			(select isns_papers.user_id from iwebsns.isns_papers where isns_papers.paper_id='111111') and isns_papers.paper_id='111111';*/

		$paper_detail_sql = "select $t_users.user_id, $t_users.user_name, $t_users.user_ico, $t_users.login_ip, $t_papers.paper_id, $t_papers.content, $t_papers.picture 
			from $t_users, $t_papers where $t_users.user_id in (select $t_papers.user_id from $t_papers where $t_papers.paper_id=$paper_id) and $t_papers.paper_id=$paper_id";

		$paper_detail_rs 	= $dbo->getRow($paper_detail_sql);

		return $paper_detail_rs;
	}

	//获得纸条的所有评论信息
	function paper_get_comments( $paper_id ){

		global $tablePreStr;
		$t_users	= $tablePreStr."users";
		$t_comments = $tablePreStr."comments";

		//评论类型
		$comment_type = 0;

		$paper_comments_rs 	= array();
		$dbo=new dbex;
	  	dbplugin('r');

	  	/*select isns_users.user_id, isns_users.user_name, isns_users.user_ico, isns_users.login_ip, isns_comments.paper_id, isns_comments.comment_content, isns_comments.comment_time, isns_comments.comment_status
			from isns_comments, isns_users where isns_users.user_id = isns_comments.commenter_id and isns_comments.paper_id = '111111';*/
		
		$paper_comments_sql = "select $t_users.user_id, $t_users.user_name, $t_users.user_ico, $t_users.login_ip, $t_comments.paper_id, $t_comments.comment_content, 
			$t_comments.comment_time, $t_comments.comment_status from $t_comments, $t_users where $t_users.user_id = $t_comments.commenter_id 
				and $t_comments.paper_id = $paper_id and $comment_type = 0";

		$paper_comments_rs 	= $dbo->getALL($paper_comments_sql);

		return $paper_comments_rs;
	}
?>