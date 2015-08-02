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
	$cur_sel = "1";

	require("foundation/asession.php");
	require("configuration.php");
	require("includes.php");
	require("foundation/module_users.php");
	require("foundation/fcontent_format.php");
	require("foundation/fplugin.php");
	require("api/base_support.php");

	//引入语言包
	$pu_langpackage=new publiclp;
	
	//用户发出的纸条
	$user_papers=api_proxy('paper_get_user_send',"1");

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
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

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

		 /*菜单样式区域 */
		.setting-menu{width:100%;font-size:1.2em;float:left;letter-spacing: 0.1em;font-weight: bold;}
		.setting-menu-item{width: 33.3%;float:left;padding: 0.5em 0 0.3em;text-align: center;}
		.selected{position: relative;bottom: -0.3em;border-bottom: 2px solid yellow;}

		.info-page{background-color: #ccc;padding:0.5em 2em;}
		.info-item{border-radius: 0.4em;margin: 1em 0;}

		/*信息状态样式*/
		.info-status{padding: 0.5em;font-weight: 800;border-bottom: 0.1em solid gray;background-color: green;}
		.status-detail{float:left;margin-left: 2em;font-size: 1.2em;}
		.status-time{float:right;margin-right: 2em;}
		
		/*纸条作者样式*/
		.info-author{padding: 1em;border-bottom: 0.1em dotted red;background-color: #f0bbff;}
		.author-img>img{width: 2em;height: 2.2em;float: left;}
		.author-detail{margin-left: 1em; float:left;}
		.author-name{font-size: 1em; margin-bottom: 0.1em;}
		.author-distance>img{width:0.8em; height: 1em;}
		.author-distance{font-size: 0.8em; color: yellow;}

		/*纸条内容样式*/
		.info-content{background-color: #cdf; border-bottom: 0.1em dotted #fa3;}
		.info-img>img{width:100%;}
		.info-img{padding: 0.3em; background-color: white;}
		.info-text{color:#fa3; padding: 0.5em 1em; font-size: 0.8em;line-height: 1.5em;}

		/*纸条交互样式*/
		.info-interchange{padding:0.5em 1em;background-color: #cdf;line-height: 1.5em;height: 1.5em;border-bottom: 0.1em dotted #fa3;}
		.info-interchange img{width:1.2em;height: 1.2em; position: relative; top:0.15em;}
		.left-interchange-record{float:left;}
		.right-interchange-record{float: right;}
		.left-interchange-record span{margin-left: 0.2em;}

		/*我参与的纸条评论样式*/
		.current-user-comment{background-color: #cdf; padding: 0.5em 1em;}
		.my-comment-status .author-me-label{font-size: 1em; font-weight: 800px; color:#b50; float :left;line-height: 2em; line-height: 2em;}
		.my-comment-status .author-me-status{float:right; line-height: 2em;height: 2em;background-color: #0bf; border-radius: 1em;padding: 0em 1em;}
		.my-comment-status{padding-bottom: 0.5em; }
		.my-comment-content{font-size: 0.8em; line-height: 1.2em;}

		 /*用户评论表样式*/
		.info-comment{}
		.comment-item{border-bottom: 0.1em solid gray;background-color: #bfe; margin-bottom: 0.5em;}
		.comment-item .comment-content{padding: 0.5em 1em;	}
		.comment-item .comment-user-info{border-top: 0.1em dotted #ddd; color:gray; }
		.comment-item .comment-user-info .left-user>span{
			color:gray;
		}
		.comment-item .comment-user-info .left-user{margin-left: 0.5em; float : left;}
		.comment-item .comment-user-info .right-user{margin-right: 0.5em;
			float: right;}
		.comment-item-span{height: 0.5em; width: 100%; background-color: #bfe;}

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
	
	<!-- 设置菜单显示 -->
	<div class="setting-menu">
		<div class="setting-menu-item">
			<a href="modules.php?app=user-write">
				<span> 我写的 </span>
			</a>
			<div class="selected">
			</div>
		</div>
		<div class="setting-menu-item">
			<a href="modules.php?app=user-help">
				<span> 我参与的 </span>
			</a>
			<div class="selected">
			</div>
		</div>
		<div class="setting-menu-item">
			<a href="modules.php?app=user-money">
				<span> 我的红包 </span>
			</a>
			<div class="selected">
			</div>
		</div>
	</div>
	<div class="clearboth"></div>

	<!--信息显示页面 -->
	<div class="info-page">

		<?php foreach($user_papers as $key => $value){?>
		<div class="info-item">
			
			<?php if(isset($cur_sel) && ($cur_sel == '1')){?>
			<div class="info-status">
				<div class="info-status-wrap">
					<div class="status-detail">
						<span> <?php echo  get_status($value['paper_status']);?></span>
					</div>
					<div class="status-time">
						<span> <?php echo  $value['create_time'];?> </span>
					</div>
					<div class="clearboth"></div>
				</div>
			</div>

			<div class="clearboth"></div>
			<?php }?>

			<?php if(isset($cur_sel) && ($cur_sel == '2')){?>
			<div class="info-author">
				<div class="info-author-wrap">
					<div class="author-img">
						<img src="skin/social/imgs/all/signup_avatar.png"/>
					</div>
					<div class="author-detail">
						<div class="author-name">
							<span>龚谦</span>
						</div>
						<div class="author-distance">
							<img id="map-icon" src="skin/social/imgs/all/note_pt_location.png">
							<span> 12.3km </span>
						</div>
					</div>
					<div class="clearboth"></div>
				</div>
			</div>
			<div class="clearboth"></div>
			<?php }?>

			<a href="#">
				<div class="info-content">
					<div class="info_content-wrap">
						<div class="info-img">
							<img src="pictures/<?php echo $value['picture'];?>"/>
						</div>
						<div class="info-text">
							<p><?php echo $value['content'];?></p>
						</div>
					</div>
				</div>
			

				<div class="info-interchange">
					<div class="info-interchange-wrap">
						<div class="left-interchange-record">
							<img src="skin/social/imgs/all/note_pt_feiji.png">
							<span> <?php echo $value['view_count']?$value['view_count']:0;?></span>
						</div>
						<div class="right-interchange-record">
							<img src="skin/social/imgs/all/note_btn_pinglun_unpress.png"/>
							<span> <?php echo $value['comment_count']?$value['comment_count']:0;?> </span>
						</div>
						<div class="clearboth"></div>
					</div>
				</div>
			</a>
			<div class="clearboth"></div>

			<?php if(isset($cur_sel) && ($cur_sel == '2')){?>
			<div class="current-user-comment">
				<div class="current-user-comment-wrap">
					<div class="my-comment-status">
						<span class="author-me-label"> 我的回复：</span>
						<span class="author-me-status">
							接受
						</span>
						<div class="clearboth"></div>
					</div>
					<div class="my-comment-content">
						<p>我的内容我的内容我的内容我的内容我的内容我的内容我的内容我的内容我的内容,jfkd我的内容我的内容</p>
					</div>

				</div>
			</div>
			<div class="clearboth"></div>
			<?php }?>

			<?php if(isset($cur_sel) && ($cur_sel == '13')){?>
			<div class="info-comment">
				<div class="info-comment-wrap">

<!-- 评论的一项 -->
					<div class="comment-item">
						<div class="comment-content">
							<p> kwg kwwg 佛挡杀佛 需要需要需要地佛挡杀佛  需要复方丹参 佛挡杀佛在佛挡杀佛 </p>
						</div>
						<div class="comment-user-info">
							<div class="left-user">
								<img src="">
								<span> Name </span>
							</div>
							<div class="right-user">
								<!-- <span> SYSU</span> -->
								<!-- <span> Distance</span> -->
								<span> time</span>
							</div>
						</div>
						<div class="clearboth"></div>
						<div class="comment-item-span"></div>
					</div>

<!-- 评论的一项 -->
					<div class="comment-item">
						<div class="comment-content">
							<p> kwg kwwg 佛挡杀佛 需要需要需要地佛挡杀佛  需要复方丹参 佛挡杀佛在佛挡杀佛 </p>
						</div>
						<div class="comment-user-info">
							<div class="left-user">
								<img src="">
								<span> Name </span>
							</div>
							<div class="right-user">
								<!-- <span> SYSU</span> -->
								<!-- <span> Distance</span> -->
								<span> time</span>
							</div>
						</div>
						<div class="clearboth"></div>
					</div>
				</div>
			</div>
			<?php }?>

		</div>
		<?php }?>
	</div>

	<!--消息面板-->
	<div class="msg-panel">
	</div>

<?php require("uiparts/footor.php");?>
</body>
</html>