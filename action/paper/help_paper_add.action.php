<?php
	//引入语言包
	$pu_langpackage=new publiclp;
	//变量取得
	$user_id = get_session('user_id');

	//判断用户是否登录
	if(null == $user_id)
	{
		//暂时直接指定，后序需要跳转页面登录
		$user_id = 1;
		set_session('user_id', $user_id);
	}

	/*表单提交的数据*/
	//纸条文字内容
	$paper_content = get_argp('comment_text');
	//纸条图片内容
	$up_load_num=count($_FILES['attach']['name']);

	//判断上传图片数量
	if($up_load_num > 0){
		//检测上传相片是否都为空
	    $is_true=0;
	    for($i=0;$i<count($_FILES['attach']['name']);$i++){
	    	if(!empty($_FILES['attach']['name'][$i]))
	    		$is_true++;
	    }
		if($is_true==0){
			action_return(0,$a_langpackage->a_no_pht,"-2");
		}

		//开始上传图片
		$base_root="uploadfiles/paper_pictures/";//图片存放目录
	    $up = new upload();
	    $up->set_dir($base_root, ''.$user_id);//目录设置
	    //$up->set_thumb(180,180); //缩略图设置
	    $fs = $up->execute();

	    foreach($fs as $index=>$realtxt){
			if($realtxt['flag']==1){
			    $fileSrcStr=str_replace(dirname(__FILE__),"",$realtxt['dir']).$realtxt['name'];
			    //$thumb_src=str_replace(dirname(__FILE__),"",$realtxt['dir']).$realtxt['thumb'];
	    	}
		}
	}

	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('w',$dbServs);

	global $tablePreStr;
    $t_papers = $tablePreStr."papers";

    //insert into isns_papers (user_id, content, picture, create_time) value (1, '纸条内容', '纸条路径', '2015-08-12 15:57:12');
    $sql = "insert into $t_papers (user_id, content, picture, create_time) 
    	value ($user_id, '$paper_content', '$fileSrcStr', now())";
    if($dbo->exeUpdate($sql)){
		$t_users = $tablePreStr."users";
		$sql = "select user_papercount from $t_users where user_id=$user_id";
		dbplugin('r');
		$result_rs=array();
		$result_rs=$dbo->getRow($sql);

		$user_papercount = $result_rs['user_papercount'];
		$user_papercount += 1;
		dbtarget('w',$dbServs);
		$sql = "update $t_users set user_papercount=$user_papercount where user_id=$user_id";
		if($dbo->exeUpdate($sql)){
			//执行成功返回到我发的纸条界面
			action_return(1,'','modules.php?app=user_settings');
		}else
		{
			action_return(0, 'error', '-3');
		}
	}

?>
