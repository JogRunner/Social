<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/paper/show_paper_item.html
 * 如果您的模型要进行修改，请修改 models/modules/paper/show_paper_item.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
	require("foundation/asession.php");
	require("configuration.php");
	require("includes.php");
	require("foundation/module_users.php");
	require("foundation/fcontent_format.php");
	require("foundation/fplugin.php");
	require("api/base_support.php");

	//引入语言包
	$pu_langpackage=new publiclp;

	$cur_user = 1;
	$paper_id = get_argg('paper_id');

	$content = array();
	$content = api_proxy('paper_get_content', $paper_id);
	$comment = api_proxy('paper_get_comments',$paper_id);

	if($content['user_id'] == $cur_user)
		$main_key = "show_user_send_paper_item";
	else 
		$main_key = "show_user_comment_item";
	
	$content['comments'] = $comment;

	$data = array($content);
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

</head>
<body>
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

	</style>
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
	
	<div class="clearboth"></div>

	<?php require('modules/paper/common_paper_show.php');?>

	<!--消息面板-->
	<div class="msg-panel">
	</div>

</body>
</html>