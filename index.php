<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/index.html
 * 如果您的模型要进行修改，请修改 models/index.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
	header("content-type:text/html;charset=utf-8");
	if(!file_exists('docs/install.lock')){
		header("location:install/index.php");
	}
	require("foundation/asession.php");
	require("configuration.php");
	require("includes.php");
	require("foundation/module_users.php");
	require("foundation/fcontent_format.php");
	require("foundation/fplugin.php");
	require("api/base_support.php");
	
	$pu_langpackage=new publiclp;
	//获取所有纸条信息
	$all_papers=api_proxy('paper_get_all_papers');
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Language" content="zh-cn">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="Description" content="<?php echo $metaDesc;?>" />
<meta name="Keywords" content="<?php echo $metaKeys;?>" />
<meta name="author" content="<?php echo $metaAuthor;?>" />
<meta name="robots" content="all" />
<style type="text/css">
body{
	padding:0 3em;
}
.paper{
	width:100%;
	margin:0 auto;
	margin-bottom: 1em;
}
.head{
	width:3em;
	height:3em;
	float:left;
	margin-right:2em;
	padding:1em 1em;
}
.user_name{
	margin:1em auto;
	color: #666;
}
.paper_distance{
	margin:1em auto;
	color: #aaa;
}
.head_info{
	float:left;
}
.paper_head{
	float:left;
	background: #F5E8CF;
	width: 100%;
	border-top: 1px dashed black;
	border-bottom: 1px dashed black;
}
.paper_content{
	width: 100%;
}
.img_content{
	background: #F5E8CF;
	text-align: center;
	padding: 0.5em;
	
}
.paper_img{
	width:100%;
}
.text_content{
	text-align: left;
	padding:1em;
	background: #F5E8CF;
	border-top: 1px dashed black;
	border-bottom: 1px dashed black;
	font-size: 0.6em;
}
.clear{
	clear:both;
}
.paper_buttons{
	padding:1em;
	border-bottom: 1px dashed black;
	background: #F5E8CF;
}
.buttons_menu{
	width:85%;
	height: 3em;
	margin: 0 auto;
}

.div_button1{
	float: left;
	height: 2.9em;
	line-height: 2.9em;
}

.div_button2{
	float: right;
	height: 2.9em;
	line-height: 2.9em;
}

#button_menu1{
	float: left;
	margin-right: 0.5em;
}
#button_menu2{
	float: left;
	margin-right: 0.5em;
}

#paper_bottom{
	height:6em;
	text-align:center;
}
</style>
</head>
<body>
	<?php 
	foreach ($all_papers as $paper) {?>
		 <div class="paper">
			<div class="paper_head">
				<img src="pictures/<?php echo $paper[5] ?>" class="head"/>
				<div class="head_info">
					<h4 class="user_name"><?php echo $paper[4]?></h4>
					<h5 class="paper_distance">距离:5000m</h5>
				</div>
			</div>
			<div class="clear"></div>
			<div class="paper_content">
				<?php if("" != $paper[3]){?>
					<div class="img_content">
						<img class="paper_img" src="pictures/<?php echo $paper[3];?>" />
					</div>
				<?php } ?>
				<div class="text_content"><?php echo $paper[2];?></div>
			</div>
			<div class="clear"></div>
			<div class="paper_buttons">
				<div class="buttons_menu">
					<div class="div_button1"><a href="#"><img id="button_menu1" src="skin/social/imgs/menu/note_btn_gengduo_unpress.png"/></a><span><?php echo "123	" ?> </span></div>
					<div class="div_button2"><a href="modules.php?app=paper_show_detail&paper_id=<?php echo $paper[0];?>"><img id="button_menu2" src="skin/social/imgs/menu/note_btn_pinglun_unpress.png"/></a><span><?php echo $paper[7]?> </span></div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	<?php }?>
	<div id="paper_bottom"><a href="#">加载更多...</a></div>
	<?php require("uiparts/footor.php");?>
</body>
</html>