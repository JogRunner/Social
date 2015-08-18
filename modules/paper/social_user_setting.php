<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/paper/social_user_setting.html
 * 如果您的模型要进行修改，请修改 models/modules/paper/social_user_setting.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php

	$cur_menu = '3';
	$main_key = get_argg('main_key');
	if(!$main_key) $main_key = "show_user_send_papers";

	require("foundation/asession.php");
	require("configuration.php");
	require("includes.php");
	require("foundation/module_users.php");
	require("foundation/fcontent_format.php");
	require("foundation/fplugin.php");
	require("api/base_support.php");

	//引入语言包
	$pu_langpackage=new publiclp;

	$user_id = 1;
	set_session('user_id', $user_id);
	
	if($main_key == "show_user_send_papers")
	{
		//用户发出的纸条
		$data=api_proxy('paper_get_user_send', $user_id);
	}
	elseif($main_key == "show_user_comments")
	{
		$data=api_proxy('paper_related_get_user_comments', $user_id);
	}elseif($main_key == "user_money")
	{
		$data=api_proxy('money_get_user_money', $user_id);

		$user_point=api_proxy('money_get_user_point', $user_id);
		set_session('user_point',$user_point);
	}elseif ($main_key == 'exchange_money') 
	{
		$sub_key = get_argg('sub_key');
		$user_point=get_session('user_point');
		if($sub_key == 'exchange_result')
		{
			$exchange_money = get_argg('exchange_money');	
		}
	}

	//标题栏文字信息
	$title_label = '我的纸条库';
	
	function get_status($status_code)
	{
		if($status_code)
		{
			switch ($status_code) {
				case '0': return '已完成';
					# code...
					break;
				case '1': return '等待接受';
					break;
				case '2': return '已接受，等待确认';
					break;
				case '3': return '已确认帮助者';
					break; 
				default:  return '未知状态';
					# code...
					break;
			}
		}
		return '未知状态';
	}

	function get_reply_str($status_code)
	{
		if($status_code)
		{
			switch ($status_code) {
				case '0': return '接受';
					# code...
					break;
				
				default: return '未接受';
					# code...
					break;
			}
		}
	}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width" />

<style>
		.clearboth{
			clear: both;
		}
		.nondisplay{display: none;}
</style>
<style>
    /*全局样式 */
	body,html,*{margin:0;padding:0;}
	a{text-decoration: none;color: black;}

	/*用户显示区域*/
	.user-info{background: url('skin/social/imgs/all/user_setting_background.png');
		filter:"progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod='scale')";-moz-background-size:100% 100%;background-size: 100% 100%;padding-top:1em;padding-bottom: 1em;}
	 .user-info-wrap{}
	 .user-head-img{text-align: center;width:100%;}
	 .user-head-img img{width:15%;height: 15%;}

	 .user-text-info{text-align: center; font-size: 1.4em; color:white;font-weight: 900;letter-spacing: 0.2em;margin-top: 0.5em;margin-bottom: 1em;}

	 /*菜单样式区域 */
	.setting-menu{width:100%;font-size:1.2em;float:left;letter-spacing: 0.1em;font-weight: bold;}
	.setting-menu-item{width: 33.3%;float:left;padding: 0.5em 0 0.3em;text-align: center;}
	.selected{position: relative;bottom: -0.3em;border-bottom: 2px solid yellow;}
	.unselected{position: relative;bottom: -0.3em; border-bottom: 2px solid white;}

	.title{
		width: 100%;
		display: block;
		padding:1.3em 0em;
		text-align: center;
		background: #FC9;
		position: fixed;
		top: 0;
		left: 0;
	}

	.title_back{
		background: #FF6600;
		position: fixed;
		top:0.9em;
		left: 1em;
		padding: 0.3em 0.3em;

		/*padding:10px; width:300px; height:50px;*/
	    border: 0.2em solid #dedede;
	    -moz-border-radius: 1em;      /* Gecko browsers */
	    -webkit-border-radius: 1em;   /* Webkit browsers */
	    border-radius:1em;            /* W3C syntax */
	}

	.title_pick{
		margin: 0 auto;
		display: inline-block;
	}

	.gap{
		height: 4em;
	}
</style>
</head>
<body>
	
	<span class="title">
		<a href="javascript:history.go(-1);" class="title_back">返回</a>
		<div class="title_pick"><?php echo $title_label; ?></div>
	</span>
	<div class="gap"></div>
	<!-- 用户信息显示区域-->
	<div class="user-info">
		<div class="user-info-wrap">
			<div class="user-head-img">
				<img src="skin/social/imgs/all/signup_avatar.png" />
			</div>
			<div class="user-text-info">
				<div class="user-name-area">
					<!--<span> 昵称:&nbsp;&nbsp; </span>-->
					<span id = "user-name">龚谦</span>
				</div>
				<!--
				<div class="user-school-area">
					<span>所在学校: </span>
					<span id="user-school">中山大学</span>
				</div>
				-->
			</div>
			<div class="user-info-setting">
			</div>
		</div>
	</div>
	
	<!-- 设置菜单显示 -->
	<div class="setting-menu">
		<div class="setting-menu-item">
			<a href="modules.php?app=user_settings&main_key=show_user_send_papers">
				<span> 我写的 </span>
			</a>
			<div class="<?php echo ($main_key == 'show_user_send_papers')?'':'un';?>selected">
			</div>
		</div>
		<div class="setting-menu-item">
			<a href="modules.php?app=user_settings&main_key=show_user_comments">
				<span> 我参与的 </span>
			</a>
			<div class="<?php echo ($main_key == 'show_user_comments')?'':'un';?>selected">
			</div>
		</div>
		<div class="setting-menu-item">
			<a href="modules.php?app=user_settings&main_key=user_money">
				<span> 我的红包 </span>
			</a>
			<div class="<?php echo ($main_key == 'user_money' || $main_key == 'exchange_money')?'':'un';?>selected">
			</div>
		</div>
	</div>
	<div class="clearboth"></div>
	<?php if(in_array($main_key, array('show_user_send_papers', 'show_user_comments'))){ ?>
		<?php require('modules/paper/common_paper_show.php');?>
	<?php } elseif($main_key == 'user_money'){ ?>
		<?php require('modules/money/user_money.php');?>
	<?php } elseif($main_key == 'exchange_money'){ ?>
		<?php require('modules/money/exchange_money.php');?>
	<?php } ?>

	<!--消息面板-->
	<div class="msg-panel">
	</div>

</body>
</html>