<?php
	//引入语言包
	$pu_langpackage=new publiclp;
	//变量取得
	$comment_content = get_argp("comment_content");

	/*$privacy=short_check(get_argp("privacy"));
	$ulog_title=short_check(get_argp("blog_title"));
	if(get_argp("blog_sort_list")){
		$ulog_sort=short_check(get_argp("blog_sort_list"));
	}else{
		$ulog_sort=0;
	}
	$ulog_txt=big_check(get_argp("CONTENT"));
	$blog_sort_name=short_check(get_argp('blog_sort_name'));*/
	
	//从session中获取用户id和用户名
	//$user_id=get_sess_userid();
	//$user_name=get_sess_username();

	$user_id = "1";
	$paper_id = get_argp("paper_id");

	//echo "123456789";

	//数据表定义区
	$t_comments=$tablePreStr."comments";

	$current_time = date('y-m-d H:i:s',time());

	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('w', $dbServs);

	//insert into isns_comments (paper_id, comment_content, commenter_id, comment_time, comment_status) 
	//value (1111111, "abc", 1, '2015-08-05 22:39:12', 0);

	$sql = "insert into $t_comments (paper_id, comment_content, commenter_id, comment_time, comment_status) 
	value ($paper_id, '$comment_content', $user_id, '$current_time', 0);";

	if($dbo->exeUpdate($sql)){
		action_return(1,'','modules.php?app=paper_show_detail&paper_id='.$paper_id);
	}else{
		action_return(0,'error','-1');
	}

?>
