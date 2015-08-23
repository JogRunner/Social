<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/uiparts/title.html
 * 如果您的模型要进行修改，请修改 models/uiparts/title.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?>
<style type="text/css">
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
	top:0.5em;
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

<span class="title">
		<a href="#" class="title_back">返回</a>
		<div class="title_pick"><?php echo $title_label; ?></div>
</span>
<div class="gap"></div>