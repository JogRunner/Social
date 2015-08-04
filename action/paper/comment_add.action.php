<?php
	//引入语言包
	$pu_langpackage=new publiclp;
	//变量取得
	$comment_content = get_argp("comment_content")

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
	$paper_id = intval(get_argp("paper_id"))

	//数据表定义区
	$t_blog=$tablePreStr."comments";

	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('w', $dbServs);

	//$sql= "update $t_blog set log_title='$ulog_title',privacy='$privacy',log_sort='$ulog_sort',log_content='$ulog_txt',edit_time=NOW(),log_sort_name='$blog_sort_name' where user_id=$user_id and log_id=$ulog_id";modules.php?app=paper_show_detail&paper_id=<?php echo $paper['paper_id'];
	//sql语句还未写完整
	$sql = "insert into comments ...";

	if($dbo->create($sql)){
	    action_return(1,'','modules.php?app=paper_show_detail&paper_id='.$paper_id);
	}else{
		  action_return(0,'error','-1');
	}

?>
