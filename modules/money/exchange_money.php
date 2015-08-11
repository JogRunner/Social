<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/money/exchange_money.html
 * 如果您的模型要进行修改，请修改 models/modules/money/exchange_money.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><!-- CSS goes in the document HEAD or added to your external stylesheet -->
<style type="text/css">
div.exchange_money_div{
	font-family: verdana,arial,sans-serif;
	font-size:0.8em;
	width: 80%;
	margin: 5em auto;
}

div.exchange_money_div .exchange_money_title_div{
	height: 1.5em;
	line-height: 1.5em;
	padding-left: 1em;
	padding-right: 1em;
}
div.exchange_money_div .exchange_money_title_div .title_left{
	float: left;
	margin: 0;
}
div.exchange_money_div .exchange_money_title_div .title_right{
	float: right;
	margin: 0;
}

div.exchange_money_div .exchange_money_title_div .user_point{
	font-weight: bold;
	margin: 0 0.5em;
}

div.exchange_money_div .exchange_money_hr{
	margin: 0.5em 0;
}

.noexchangable p, .exchangable, .exchange_result, .exchangable a{
	text-align: center;
}

.exchange_result_back{
	margin: 1.5em auto;
	text-align: center;
}

</style>

<!-- Table goes in the document BODY -->
<div class="exchange_money_div">
	<div class="exchange_money_title_div">
		<p class="title_left">红包兑换，试试运气咯</p><p class="title_right">剩余积分:<span class="user_point"><?php echo $user_point;?></span></span></p>
	</div>
	<div class="clearboth"></div>
	<hr class="exchange_money_hr"/>
	<!-- 积分不足不可兑换 -->
	<?php if(!$sub_key || $sub_key != 'exchange_result'){
			if($user_point < 10){ ?>
			<div class="noexchangable"><p>积分不足，不可兑换红包哦~去抢纸条<a href="#">赚积分</a>吧。<p></div>
			<div class="exchange_result_back"><a href="modules.php?app=user_settings&main_key=user_money">返回，查看我的红包记录</a></div>
		<?php }else{?>
		<!-- 判断成功后就可以兑换 -->
			<div class="exchangable">
				<a href="do.php?act=exchange_money">打开红包</a>
			</div>
		<?php }?>
	<?php }else{?>
			<div class="exchange_result"><p>恭喜您，获得<?php echo $exchange_money; ?>元红包</p></div>
			<div class="exchange_result_back"><a href="modules.php?app=user_settings&main_key=user_money">返回，查看我的红包记录</a></div>
		<?php }?>
</div>