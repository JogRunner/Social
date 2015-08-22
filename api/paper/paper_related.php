<?php
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

	//添加用户
	function paper_related_add_user($weixin_userinfo)
	{
		global $tablePreStr;
		global $dbServs;
		global $log;

		$t_users = $tablePreStr."users";
		
		$dbo=new dbex;
		
		if(!empty($weixin_userinfo))
		{
		    $user_name = $weixin_userinfo['nickname'];
            $user_sex = $weixin_userinfo['sex'];
            $user_city = $weixin_userinfo['city'];
            $user_province = $weixin_userinfo['province'];
            $user_country = $weixin_userinfo['country'];
            $user_head_imgurl = $weixin_userinfo['headimgurl'];
            $user_openid = $weixin_userinfo['openid'];
            $user_add_time =  date("Y-m-d H:i:s", $weixin_userinfo['subscribe_time']);

            //判断用户是否已经订阅
            $querySql = "select weixin_openid from $t_users where weixin_openid = '$user_openid'";
            dbtarget('r', $dbServs);
            $queryRes = $dbo->getRow($querySql);

            if(empty($queryRes))
            {
	            $sql = "insert into $t_users (user_name, user_sex, reside_province," 
	            	."reside_city, user_ico,user_add_time,user_nickname, weixin_openid, user_papercount,"
	            	."bless_count, user_point)" 
	                ." values ('$user_name', $user_sex, '$user_province', '$user_city', '$user_head_imgurl',"
	            	."'$user_add_time', '$user_name', '$user_openid',0,0,0)";

				dbtarget('w', $dbServs);
				if($dbo->exeUpdate($sql))
				{
					file_put_contents($log, "\nSuccess Insert User Info", FILE_APPEND);
					$user_id = mysql_insert_id();
					set_sess_userid($user_id);
					set_sess_weixin_openid($user_openid);
					set_sess_username($user_name);
					set_sess_userico($user_ico);
					set_sess_online('0');

					return true;
				}
				else{
				     file_put_contents($log, "\nFailed Insert User Info", FILE_APPEND);
				}
			}
		}
		return false;
	}

	function paper_related_save_user_session($user_openid)
	{
		global $tablePreStr;
		global $dbServs;
		global $log;

		$t_users = $tablePreStr."users";
		
		$dbo=new dbex;
		dbtarget('r', $dbServs);

		$querySql = "select user_id,user_nickname,user_ico from $t_users where weixin_openid = '$user_openid'";

		$res = $dbo->getRow($querySql);

		file_put_contents($log, "\nSave User ".$user_openid." Session Sql Query: ".$querySql." Query Res: ".(empty($res)?"NULL":"Not NULL"), FILE_APPEND);
		if(!empty($res))
		{
			set_sess_userid($res['user_id']);
			set_sess_weixin_openid($user_openid);
			set_sess_username($res['user_nickname']);
			set_sess_userico($res['user_ico']);
			set_sess_online('0');

			file_put_contents($log, "\n Save User Name: ". $res['user_nickname'], FILE_APPEND);
		}
	}

	function paper_related_del_user($user_openid)
	{
		global $tablePreStr;
		global $dbServs;
		global $log;

		$t_users = $tablePreStr."users";
		
		$dbo=new dbex;
		dbtarget('w', $dbServs);

		$querySql = "delete from $t_users where weixin_openid = '$user_openid'";

		if($dbo->exeUpdate($querySql))
		{
			file_put_contents($log, "\nDelete User ".$user_openid." Successs", FILE_APPEND);
		}else{
			file_put_contents($log, "\nDelete User ".$user_openid." Failed", FILE_APPEND);
		}
	}
?>