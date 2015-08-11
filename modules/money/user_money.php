<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/money/user_money.html
 * 如果您的模型要进行修改，请修改 models/modules/money/user_money.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><script type="text/javascript">
function altRows(id){
	if(document.getElementsByTagName){  
		
		var table = document.getElementById(id);  
		var rows = table.getElementsByTagName("tr"); 
		 
		for(i = 0; i < rows.length; i++){          
			if(i % 2 == 0){
				rows[i].className = "evenrowcolor";
			}else{
				rows[i].className = "oddrowcolor";
			}      
		}
	}
}

window.onload=function(){
	altRows('user_money_table');
}
</script>


<!-- CSS goes in the document HEAD or added to your external stylesheet -->
<style type="text/css">
div.user_money_div{
	font-family: verdana,arial,sans-serif;
	font-size:0.8em;
	width: 80%;
	margin: 5em auto;

}

div.user_money_div .user_money_title_div{
	height: 1.5em;
	line-height: 1.5em;
	padding-left: 1em;
	padding-right: 1em;
}
div.user_money_div .user_money_title_div .title_left{
	float: left;
	margin: 0;
}
div.user_money_div .user_money_title_div .title_right{
	float: right;
	margin: 0;
}

div.user_money_div .user_money_title_div .user_point{
	font-weight: bold;
	margin: 0 0.5em;
}

div.user_money_div .user_money_hr{
	margin: 0.5em 0;
}

table.user_money_table {
	color:#333333;
	border-width: 1px;
	border-color: #a9c6c9;
	border-collapse: collapse;
	width: 100%;
	margin: 0 auto;
}
table.user_money_table th {
	border-width: 1px;
	padding: 0.5em;
	border-style: solid;
	border-color: #a9c6c9;
	background: #999;
}
table.user_money_table td {
	border-width: 1px;
	padding: 0.5em;
	border-style: solid;
	border-color: #a9c6c9;
}
.oddrowcolor{
	background-color:#d4e3e5;
}
.evenrowcolor{
	background-color:#c3dde0;
}
.clearboth{
	float: both;
}
.exchange_a{
	color: blue;
}
</style>

<div class="user_money_div">
	<div class="user_money_title_div"><p class="title_left">我的红包兑换记录</p><p class="title_right">剩余积分:<span class="user_point"><?php echo $user_point; ?></span> <a href="modules.php?app=user_settings&main_key=exchange_money" class="exchange_a">兑换</a></p></div>
	<div class="clearboth"></div>
	<hr class="user_money_hr"/>
	<table class="user_money_table" id="user_money_table">
		<tr>
			<th>兑换日期</th><th>消耗积分数</th><th>兑换红包</th>
		</tr>
		<?php $total_consume_point=0; $total_exchange_money=0; ?>
		<?php foreach ($data as $money_record) { ?>
			<tr>
			<td><?php echo $money_record['exchange_datetime']; ?></td>
			<td><?php echo $money_record['consume_point']; ?></td>
			<td><?php echo $money_record['exchange_money']; ?></td>
		</tr>
		<?php 
			$total_consume_point += $money_record['consume_point'];
			$total_exchange_money += $money_record['exchange_money'];
		?>
		<?php } ?>

		<tr>
			<td>总计</td><td><?php echo $total_consume_point; ?></td><td><?php echo $total_exchange_money; ?></td>
		</tr>
	</table>
</div>