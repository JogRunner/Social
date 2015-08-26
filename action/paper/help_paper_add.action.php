<?php
	/*
	//引入语言包
	$pu_langpackage=new publiclp;
	//变量取得
	$user_id = get_session('user_id');

	//表单提交的数据
	//纸条文字内容
	$paper_content = get_argp('comment_text');
	//纸条图片内容
	$up_load_num=count($_FILES['attach']['name']);
	$help_location = get_argp('help-location');
	$help_last_time= get_argp('help-last-time');
	
	//本来应该中文提取的
	if(strlen($help_location) >= 40)
	{
		$help_location = substr($help_location, 0,40);
	}

	//判断上传图片数量
	if($up_load_num > 0){
		//检测上传相片是否都为空
	    $is_true=0;
	    for($i=0;$i<count($_FILES['attach']['name']);$i++){
	    	if(!empty($_FILES['attach']['name'][$i]))
	    		$is_true++;
	    }
		if($is_true != 0)
		{
			//开始上传图片
			$base_root="uploadfiles/paper_pictures/";//图片存放目录
		    $up = new upload();
		    $up->set_dir($base_root, ''.$user_id);//目录设置

		    $up->set_thumb(800, 800); //缩略图设置

		    //$fs = $up->execute();
		    $fs = $up->execute_only_thumb();

		    foreach($fs as $index=>$realtxt){
				if($realtxt['flag']==1){
				    $fileSrcStr=str_replace(dirname(__FILE__),"",$realtxt['dir']).$realtxt['name'];
				    //$thumb_src=str_replace(dirname(__FILE__),"",$realtxt['dir']).$realtxt['thumb'];
		    	}
			}
		}
	}

	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('w',$dbServs);

	global $tablePreStr;
    $t_papers = $tablePreStr."papers";

    //insert into isns_papers (user_id, content, picture, create_time) value (1, '纸条内容', '纸条路径', '2015-08-12 15:57:12');
    $sql = "insert into $t_papers (user_id, content, picture, create_time, help_location, help_last_time) 
    	value ($user_id, '$paper_content', '$fileSrcStr', now(), '$help_location', '$help_last_time')";
    
    if($help_last_time == '')
    {
    	$sql = "insert into $t_papers (user_id, content, picture, create_time, help_location) 
    	value ($user_id, '$paper_content', '$fileSrcStr', now(), '$help_location')";
    }
	
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
	*/

	//异步返回结果
	$res = array();



	//引入语言包
	$pu_langpackage=new publiclp;
	//变量取得
	$user_id = get_session('user_id');
	//表单提交的数据
	//纸条文字内容
	//$paper_content = get_argp('comment_text');
	//纸条图片内容
	//$help_location = get_argp('help-location');
	//$help_last_time= get_argp('help-last-time');

	$mime = array(
		'image/png' => '.png',
		'image/jpg' => '.jpg',
		'image/jpeg' => '.jpg',
		'image/pjpeg' => '.jpg',
		'image/gif' => '.gif',
		'image/bmp' => '.bmp',
		'image/x-png' => '.png',
	);

	$base64 = $_POST['base64'];
	$type = $_POST['type'];
	$paper_info = $_POST['paper_info'];	


	//当上传了图片就进行保存
	if(null != $base64 && null != $type)
	{
	    $imgtype = $mime[$type];
	    if($imgtype){
	        preg_match('/(.*)base64,(.*)/', $base64, $matches);
	        $base64 = $matches['2'];
	        $base64 = base64_decode($base64);

	        date_default_timezone_set('PRC');

	        $user_picture_diratory = $base_root.$user_id;
	        if (!file_exists($user_picture_diratory))
	        { 
	            mkdir ($user_picture_diratory);
	        }

	        //用户上传图片路径
			$base_root="uploadfiles/paper_pictures/";//图片存放目录
	        $imgname = date('Ymdhis',time()).mt_rand(10,99);

	        $imgurl = $base_root.$user_id.'/'.$imgname.$imgtype; //生成文件名
	        $imgurlname = $imgname.$imgtype;

	        $ress = file_put_contents($imgurl, $base64);
	        if($ress){
	            //$st = new SaeStorage();
	            $fileSrcStr = $imgurl;

	            $res['status'] = '0';
	            $res['msg'] = "图片上传成功";
	        }else{
	            $res['status'] = '1';
	            $res['msg'] = '上传图片错误，请检查文件夹权限';

	            @unlink($imgurl);
	            echo json_encode($res);
	            die;
	        }
	    }else{
	        $res['status'] = '1';
	        $res['msg'] = '格式错误';

	        @unlink($imgurl);
	        echo json_encode($res);
	        die;
	    }
	}else{
	    $res['status'] = '0';
	    $res['msg'] = "no image";
	}

	//纸条文字内容
	$paper_content = $paper_info['content_text'];
	//纸条图片内容
	$help_location = $paper_info['help_location'];
	$help_last_time= $paper_info['help_last_time'];

	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('w',$dbServs);

	global $tablePreStr;
    $t_papers = $tablePreStr."papers";

    //insert into isns_papers (user_id, content, picture, create_time) value (1, '纸条内容', '纸条路径', '2015-08-12 15:57:12');
    $sql = "insert into $t_papers (user_id, content, picture, create_time, help_location, help_last_time) 
    	value ($user_id, '$paper_content', '$fileSrcStr', now(), '$help_location', '$help_last_time')";
    
    if($help_last_time == '')
    {
    	$sql = "insert into $t_papers (user_id, content, picture, create_time, help_location) 
    	value ($user_id, '$paper_content', '$fileSrcStr', now(), '$help_location')";
    }
	
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

			//select * from isns_papers where isns_papers.user_id=2 order by isns_papers.paper_id desc limit 1;
			$sql = "select * from $t_papers where $t_papers.user_id=$user_id order by $t_papers.paper_id desc limit 1";
			dbplugin('r');
			$result = array();
			$result = $dbo->getRow($sql);
			$paper_id = $result['paper_id'];

			//执行成功返回到我发的纸条界面
			//action_return(1,'','modules.php?app=user_settings');
			$res['status'] = '0';
	        $res['msg'] = 'modules.php?app=paper_show_detail&paper_id='.$paper_id;

		}else
		{
			$res['status'] = '2';
	        $res['msg'] = '用户纸条添加失败';
			//action_return(0, 'error', '-3');
		}
	}

	echo json_encode($res);
?>


