<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/uiparts/footor.html
 * 如果您的模型要进行修改，请修改 models/uiparts/footor.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
require("foundation/module_lang.php");
?><!--<script type="text/javascript">
	function set_cookie_lp(lp_str){
		document.cookie = "lp_name=" + escape(lp_str);
		window.location.reload();
	}
</script>
<div class="foot">
	<a href="about/about.html"><?php echo $pu_langpackage->pu_paper_lib;?></a>
	<a href="about/privacy.html"><?php echo $pu_langpackage->pu_send_paper;?></a>
	<a href="http://tech.jooyea.com/bbs/"><?php echo $pu_langpackage->pu_bbs;?></a>
<div style="display: none;" class="emBg" id="face_list_menu"></div>
<div id="append_parent"></div>-->

<style>
.foot{position:fixed; left:0; right:0; bottom:0; width:100%;background-color: white;}
.menu-item{width:33.3%; float:left; text-align:center; }
a{color: #00f; text-decoration:none; font-size:1.2em;}
.menu-ico img{width:3em; height:3em;}
.menu-item span{position:relative; bottom:2px;}
</style>
<div class="foot">
	<hr style="width:100%;height:0.5px;background-color:gray;"/>
	<div class="menu-item">
		<a href="modules.php?app=list_all_paper">
			<div class="menu-ico">
				<img src="./skin/social/imgs/menu/<?php echo (!isset($cur_menu) || $cur_menu=='1')?'main_btn_notebox_press.png':'main_btn_notebox_unpress.png';?>"/>
			</div>
			<span><?php echo $pu_langpackage->pu_paper_lib;?></span>
		</a>
	</div>
	<div class="menu-item">
		<a href="modules.php?app=send_help_paper">
			<div class="menu-ico">
				<img src="./skin/social/imgs/menu/main_btn_message_<?php echo  (isset($cur_menu) && $cur_menu=='2')?'':'un';?>press.png"/>
			</div>
			<span><?php echo $pu_langpackage->pu_send_paper;?></span>
		</a>
	</div>
	<div class="menu-item">
		<a href="modules.php?app=user_settings">
			<div class="menu-ico">
				<img src="./skin/social/imgs/menu/main_btn_me_<?php echo  (isset($cur_menu) && $cur_menu=='3')?'':'un';?>press.png"/>
			</div>
			<span><?php echo $pu_langpackage->pu_user_setting;?></span>
		</a>
	</div>
</div>