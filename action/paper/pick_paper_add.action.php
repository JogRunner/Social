<?php
	//引入语言包
	$pu_langpackage=new publiclp;
	//变量取得
	$user_id = get_session('user_id');
	/*表单提交的数据*/
	//纸条文字内容
	$comment_content = get_argp('pick_paper_reason');
	//comment_type = 1为我抢评论类型
	$comment_type = intval(get_argp('comment_type'));

	$paper_id 	= get_argp("paper_id");

	//数据表定义区
	$t_comments	= $tablePreStr."comments";
	$t_papers 	= $tablePreStr."papers";

	$current_time = date('y-m-d H:i:s',time());
	$dbo = new dbex;

	/*后面可以将下面的查询语句封装到api中*/
	//增加评论数
    //insert into isns_papers (user_id, content, picture, create_time) value (1, '纸条内容', '纸条路径', '2015-08-12 15:57:12');
    dbplugin('r');

	$get_comment_count_sql = "select $t_papers.comment_count from $t_papers where $t_papers.paper_id=$paper_id";
	$result_rs = $dbo->getRow($get_comment_count_sql);
	$comment_count = $result_rs['comment_count'];
	$comment_count += 1;

	//读写分离定义函数
	dbtarget('w', $dbServs);
	/* update isns_papers set isns_papers.comment_count = 3 where isns_papers.paper_id=111114; */
	$update_comment_count_sql = "update $t_papers set $t_papers.comment_count=$comment_count where $t_papers.paper_id=$paper_id";

	if($dbo->exeUpdate($update_comment_count_sql))
	{
		//插入纸条评论
		//insert into isns_comments (paper_id, comment_content, commenter_id, comment_time, comment_status, comment_type) 
		//value (1111111, "abc", 1, '2015-08-05 22:39:12', 0, 1);
		$sql = "insert into $t_comments (paper_id, comment_content, commenter_id, comment_time, comment_status, comment_type) 
		value ($paper_id, '$comment_content', $user_id, '$current_time', 0, $comment_type)";

		if($dbo->exeUpdate($sql)){
			action_return(1,'','modules.php?app=paper_show_detail&paper_id='.$paper_id);
		}else{
			action_return(0,'error','-1');
		}
	}
	else{
		action_return(0, 'error', '-2');
	}

?>
