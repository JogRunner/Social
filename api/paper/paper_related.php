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

		$sql = "select distinct $t_users.*, $t_papers.*,
(select count($t_comments.comment_id) from $t_comments where $t_comments.paper_id=$t_papers.paper_id and $t_comments.comment_type=0) as public_count,
(select count(distinct $t_comments.commenter_id) from $t_comments where $t_comments.paper_id=$t_papers.paper_id and $t_comments.comment_type=1) as pick_count
		from $t_users,$t_papers,$t_comments where 
		$t_users.user_id = $t_papers.user_id and $t_papers.paper_id = $t_comments.paper_id 
		and $t_comments.commenter_id = $user_id";

		/*select distinct isns_users.*, isns_papers.*,
(select count(isns_comments.comment_id) from isns_comments where isns_comments.paper_id=isns_papers.paper_id and isns_comments.comment_type=0) as public_count,
(select count(distinct isns_comments.commenter_id) from isns_comments where isns_comments.paper_id=isns_papers.paper_id and isns_comments.comment_type=1) as pick_count
		from isns_users,isns_papers,isns_comments where 
		isns_users.user_id = isns_papers.user_id and isns_papers.paper_id = isns_comments.paper_id 
		and isns_comments.commenter_id = 2*/

		$result_rs = $dbo->getALL($sql);
		return $result_rs;
	}

	function paper_related_get_private_comments($user_id)
	{
		global $tablePreStr;
		$t_papers=$tablePreStr."papers";
		$t_users = $tablePreStr."users";
		$t_comments = $tablePreStr."comments";
		$result_rs=array();
		$dbo=new dbex;
		dbplugin('r');

		$sql = "select $t_users.*,$t_papers.* from $t_users,$t_papers where $t_users.user_id = $t_papers.user_id and (paper_id in (select paper_id from $t_comments where $t_comments.commenter_id = $user_id and $t_comments.comment_type = 1))";
		$res = $dbo->getALL($sql);
		return $res;
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
            //$user_add_time = array_key_exists("subscribe_time", $weixin_userinfo) ? date("Y-m-d H:i:s", $weixin_userinfo['subscribe_time']) : 'NULL';

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
	            	."now(), '$user_name', '$user_openid',0,0,0)";

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
			}else{
				$sql = "update $t_users set user_ico='$user_head_imgurl' where user_id='$user_openid'";
				dbtarget('w',$dbServs);
				$dbo->exeUpdate($sql);
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

		$querySql = "select user_id,user_nickname,user_ico,position_x,position_y,user_school from $t_users where weixin_openid = '$user_openid'";

		$res = $dbo->getRow($querySql);

		file_put_contents($log, "\nSave User ".$user_openid." Session Sql Query: ".$querySql." Query Res: ".(empty($res)?"NULL":"Not NULL"), FILE_APPEND);
		if(!empty($res))
		{
			set_sess_userid($res['user_id']);
			set_sess_weixin_openid($user_openid);
			set_sess_username($res['user_nickname']);
			set_sess_userico($res['user_ico']);
			set_session('position_x', $res["position_x"]);
			set_session('position_y',$res["position_y"]);
			set_session('user_school', $res['user_school']);
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

	function paper_related_collect_user_position($user_openid, $user_lat, $user_long)
	{
		global $tablePreStr;
		global $dbServs;
		global $log;

		$dbo = new dbex;
		$t_users = $tablePreStr."users";
		dbtarget('w', $dbServs);

		$updateSql = "update $t_users set position_x = $user_long, position_y = $user_lat where weixin_openid = '$user_openid'";
		if($dbo->exeUpdate($updateSql))
			return true;
		return false;
	}

	function paper_related_get_user_info($user_id)
	{
		global $tablePreStr;
		global $dbServs;
		global $log;

		$dbo = new dbex;
		$t_users = $tablePreStr."users";
		dbtarget('r', $dbServs);

		$readSql = "select user_ico,user_name,user_nickname,user_school from $t_users where user_id = $user_id";

		$res = $dbo->getRow($readSql);

		return $res;
	}

	function paper_related_last_paper()
	{
		global $tablePreStr;
		$t_papers=$tablePreStr."papers";
		$result_rs=array();
		$dbo=new dbex;
	  	dbplugin('r');

	  	$sql = "select * from $t_papers where picture is not null and picture <> '' order by paper_id desc limit 5";
	  	$result_rs = $dbo->getALL($sql);
	  	
		return $result_rs;
	}

	function paper_related_get_user_unreaded_count($user_id)
	{
		global $tablePreStr;
		global $dbServs;

		$dbo = new dbex;
		$t_comments = $tablePreStr."comments";
		$t_papers = $tablePreStr."papers";
		dbtarget('r', $dbServs);

		$res = array();

		if(null == $user_id)
		{
			return 0;	
		}

		/*select count(isns_comments.comment_id) as unread_count from isns_comments 
		where ((isns_comments.paper_id in (select isns_papers.paper_id from isns_papers where isns_papers.user_id = 1) 
			 and isns_comments.comment_type=1) or (isns_comments.commenter_id=1 and isns_comments.comment_type=2)) and isns_comments.comment_status = 0;*/
		$readSql = "select count($t_comments.comment_id) as unread_count from $t_comments 
		where ($t_comments.paper_id in (select $t_papers.paper_id from $t_papers where $t_papers.user_id = $user_id) 
			 and $t_comments.comment_type=1) and $t_comments.comment_status = 0";

		$res = $dbo->getRow($readSql);

		return $res['unread_count'];
	}

	//用户查看私信回复后，更新私信状态为已读状态
	function paper_related_update_paper_unread($user_id, $paper_id, $is_user_paper)
	{
		global $tablePreStr;
		global $dbServs;

		$dbo = new dbex;
		$t_comments = $tablePreStr."comments";
		dbtarget('w', $dbServs);

		$updateSql = "";
		if(1 === $is_user_paper)
		{
			$updateSql = "update $t_comments set $t_comments.comment_status=1 
				where $t_comments.paper_id=$paper_id and $t_comments.comment_type=1";
		}else{
			$updateSql = "update $t_comments set $t_comments.comment_status=1 
				where $t_comments.paper_id=$paper_id and $t_comments.commenter_id = $user_id and $t_comments.comment_type=2";
		}
		
		if($dbo->exeUpdate($updateSql))
			return true;
		return false;
	}
?>