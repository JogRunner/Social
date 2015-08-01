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
	
	//语言包引入
	$pu_langpackage=new publiclp;
		
	if(get_sess_userid()){
		echo '<script type="text/javascript">location.href="main.php";</script>';
	}
	$tg=get_argg('tg');
	if($tg=='invite'){
		$index_ref="modules/invite.php";
	}elseif($tg=='search_pals_list'){
		$index_ref="modules/mypals/search_pals_list.php";
	}else{
		$index_ref="modules/default.php";
  }
  //数据表定义区
	$t_plugins=$tablePreStr."plugins";

	$rec_rs=array();
	$rec_rs0=array();
	$rec_rs1=array();

	//首页会员推荐
	$rec_rs=api_proxy("user_recommend_get");

	foreach ($rec_rs as $key=>$val){
		if ($val['rec_class']=='0'){
			$rec_rs0[$key]=$val;
		}
	}
	//首页幻灯片
	foreach ($rec_rs as $key=>$val){
		if ($val['rec_class']=='1'){
			$rec_rs1[$key]=$val;
		}
	}
  //最新会员列表
  $user_rs=api_proxy("user_self_by_new","user_id,user_name,user_ico,lastlogin_datetime",8);

	//会员总数
	$total_member=api_proxy('user_self_by_total');
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
	padding:0.8em 3em;
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
.paper_name{
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
	width:100%;
	background: #F5E8CF;
	text-align: center;
	
}
.paper_img{
	width:95%;
	margin:1.3em;
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
#button_menu1{
	float: left;
	height:3em;
	width:3em;
	background:url(skin/social/imgs/menu/note_btn_gengduo_unpress.png) no-repeat;

}
#button_menu1:hover{
	height:3em;
	width:3em;
	background:url(skin/social/imgs/menu/note_btn_gengduo_press.png) no-repeat;
}

#button_menu2{
	float: right;
	height:3em;
	width:3em;
	background:url(skin/social/imgs/menu/note_btn_pinglun_unpress.png) no-repeat;

}
#button_menu2:hover{
	height:3em;
	width:3em;
	background:url(skin/social/imgs/menu/note_btn_pinglun_press.png) no-repeat;
}
#paper_bottom{
	height:3.2em
}
</style>
</head>
<body>
	<div class="paper">
		<div class="paper_head">
			<img src="pictures/head.jpg" class="head"/>
			<div class="head_info">
				<h4 class="paper_name">公公的天下</h4>
				<h5 class="paper_distance">距离:5000m</h5>
			</div>
		</div>
		<div class="clear"></div>
		<div class="paper_content">
			<!--<div class="img_content">
				<img class="paper_img" src="pictures/news3.jpg"/>
			</div>-->
			<div class="text_content">7月31日，在“八一”建军节到来之际，驻粤的解放军、武警、公安边防、消防等现役部队的88名功勋军人携妻子在广东东莞凤岗镇龙凤山庄举行“情注军旅，缘定今生”集体婚礼，喜庆建军88周年。据介绍，这些军人大多长年坚守在边防海岛、船艇舰队、机动部队等艰苦岗位上，均为已领取结婚证但尚未举行婚礼的新人。他们全部荣立三等功以上功勋，其中42人次荣立二等功，2人荣立一等功，有39人参加过维和、抗震等重大任务。</div>
		</div>
		<div class="clear"></div>
		<div class="paper_buttons">
			<div class="buttons_menu">
				<a id="button_menu1" href="#"></a>
				<a id="button_menu2" href="#"></a>
			</div>
		</div>
		<div class="clear"></div>
	</div>

	<div class="paper">
		<div class="paper_head">
			<img src="pictures/head2.png" class="head"/>
			<div class="head_info">
				<h4 class="paper_name">公公的天下</h4>
				<h5 class="paper_distance">距离:5000m</h5>
			</div>
		</div>
		<div class="clear"></div>
		<div class="paper_content">
			<div class="img_content">
				<img class="paper_img" src="pictures/news.jpg" />
			</div>
			<div class="text_content">7月31日，在“八一”建军节到来之际，驻粤的解放军、武警、公安边防、消防等现役部队的88名功勋军人携妻子在广东东莞凤岗镇龙凤山庄举行“情注军旅，缘定今生”集体婚礼，喜庆建军88周年。据介绍，这些军人大多长年坚守在边防海岛、船艇舰队、机动部队等艰苦岗位上，均为已领取结婚证但尚未举行婚礼的新人。他们全部荣立三等功以上功勋，其中42人次荣立二等功，2人荣立一等功，有39人参加过维和、抗震等重大任务。</div>
		</div>
		<div class="clear"></div>
		<div class="paper_buttons">
			<div class="buttons_menu">
				<a id="button_menu1" href="#"></a>
				<a id="button_menu2" href="#"></a>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="paper">
		<div class="paper_head">
			<img src="pictures/head.jpg" class="head"/>
			<div class="head_info">
				<h4 class="paper_name">公公的天下</h4>
				<h5 class="paper_distance">距离:5000m</h5>
			</div>
		</div>
		<div class="clear"></div>
		<div class="paper_content">
			<div class="img_content">
				<img class="paper_img" src="pictures/news3.jpg" />
			</div>
			<div class="text_content">2015年7月31日，上海，ChinaJoy游戏展（中国国际数码互动娱乐展览会，简称CJ展）在严厉的模特着装规定下，商家炒作总有新手段，美女营销拼身材也拼创意，DeNA中国展台设置一水箱，不同装扮的美人鱼轮番遨游其中，画面充满美感，引众小伙伴围观，Showgirl不仅需要颜值，也需要游泳健将的运动好身材。</div>
		</div>
		<div class="clear"></div>
		<div class="paper_buttons">
			<div class="buttons_menu">
				<a id="button_menu1" href="#"></a>
				<a id="button_menu2" href="#"></a>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<div id="paper_bottom"></div>

	<?php require("uiparts/footor.php");?>
</body>
</html>