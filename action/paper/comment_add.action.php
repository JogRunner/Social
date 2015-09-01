<?php
	//引入语言包
	$pu_langpackage=new publiclp;
	//变量取得
	$comment_content= get_argp("comment_content");
	$comment_type 	= get_argp("comment_type");

	//$user_id 	= get_session('user_id');
	$commenter_id 	= get_sess_userid();
	if(empty($commenter_id))
	{
		header("location:error.php");
		exit;
	}
	$paper_id 	= get_argp("paper_id");

	//数据表定义区
	$t_comments	= $tablePreStr."comments";
	$t_papers 	= $tablePreStr."papers";

	$current_time = date('y-m-d H:i:s',time());
	$dbo = new dbex;

	//增加评论数
    //insert into isns_papers (user_id, content, picture, create_time) value (1, '纸条内容', '纸条路径', '2015-08-12 15:57:12');
    dbplugin('r');

	$get_comment_count_sql = "select $t_papers.comment_count,$t_papers.private_count from $t_papers where $t_papers.paper_id=$paper_id";
	$result_rs = $dbo->getRow($get_comment_count_sql);
	$comment_count = $result_rs['comment_count'];
	$private_count = $result_rs['private_count'];

	if($comment_type == 0)
		$comment_count += 1;
	else
		$private_count += 1;

	//读写分离定义函数
	dbtarget('w', $dbServs);
	/* update isns_papers set isns_papers.comment_count = 3 where isns_papers.paper_id=111114; */
	$update_comment_count_sql = "update $t_papers set $t_papers.comment_count=$comment_count,$t_papers.private_count=$private_count where $t_papers.paper_id=$paper_id";
	if($dbo->exeUpdate($update_comment_count_sql))
	{
		//插入纸条评论
		//insert into isns_comments (paper_id, comment_content, commenter_id, comment_time, comment_status, comment_type) 
		//value (1111111, "abc", 1, '2015-08-05 22:39:12', 0, 1);
		$sql = "insert into $t_comments (paper_id, comment_content, commenter_id, comment_time, comment_status, comment_type) 
		value ($paper_id, '$comment_content', $commenter_id, '$current_time', 0, $comment_type)";
		if($dbo->exeUpdate($sql)){
			//if(0 == $comment_type)
			//{
				action_return(1,'','modules.php?app=paper_show_detail&paper_id='.$paper_id);
			//}else{
				//action_return(1, '', 'modules.php?app=pick_paper_detail&paper_id='.$paper_id);
			//}
		}else{
			action_return(0,'error','-1');
		}
	}
	else{
		action_return(0, 'error', '-2');
	}
?>
