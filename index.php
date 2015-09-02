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
		exit;
	}
	require("foundation/asession.php");
	require("configuration.php");
	require("includes.php");
	require("foundation/module_users.php");
	require("foundation/fcontent_format.php");
	require("foundation/fplugin.php");
	require("api/base_support.php");
	
	$code = get_argg('code');
	if(!empty($code))
		save_weixin_session($code);

	$user_id  = get_sess_userid();

	if(empty($user_id) && $local_debug)
	{
		set_sess_username("FanJian");
		set_sess_userid("2");
	}
	
	$user_id = get_sess_userid();
	$user_name = get_sess_username();
	$user_ico = get_sess_userico();

	if(empty($user_id))
	{
		header("location:error.php");
		exit;
	}
	
	$pu_langpackage=new publiclp;
	//获取所有纸条信息
	$data=api_proxy('paper_get_top_papers');

	$main_key = "show_all_papers";

	$cur_menu = "1";
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Language" content="zh-cn">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<script type="text/javascript" src="srcfiles/jquery-2.1.1.min.js"></script>

<style type="text/css">
	body,html,*{margin:0;padding:0; font-family: Arial,Helvetica,Arial,sans-serif;}
	a{text-decoration: none;color: black;-webkit-tap-highlight-color:rgba(0,0,0,0); }
	a:focus{outline: none;}

	.clearboth{
			clear: both;
		}
	.top-title{width:100%; font-size:1.2em; font-weight:600; position: relative; background-color: #0ca6f2; color:#fff;}
	.return-btn{position:absolute; top:0; left:0; color: #fff; padding: 0.5em 1.5em;  background-color: #ff9500; }
	.return-btn:hover{cursor: pointer; background-color: #ff6200;}
	.center-title{width: 100%; text-align: center;  padding: 0.5em 0;}
	.nondisplay{display: none;}
	.msg-panel{margin:8em 0; text-align: center; font-size: 1.2em; color:gray;}
	#paper_bottom{margin-bottom: 4em; text-align: center;margin-top: 1em;}
	#paper_bottom:hover{cursor: pointer;}
</style>

</head>
<body>
	<script type="text/javascript">
        $(document).ready(function(){
            $('.return-btn').click(function(){
                window.history.go(-1);
            });


			var least_paper_id = <?php echo (empty($data)? 0: $data[count($data) - 1]['paper_id']);?>;
			$("#load_more").click(function(){
				$.ajax({
	            type: "get",
	            dataType:"json",
	            url: "do.php?act=load_more_papers&least_paper_id="+least_paper_id,
	            success: function (result) {
	            	if(result.length == 0)
	            	{
	            		$("#load_more").text("已经没有纸条了哦~");
	            	}else{
	            		$.each(result, function(){
							if(this['paper_id'] < least_paper_id)
							{
								least_paper_id = this['paper_id'];
							}

							var infoPage = $('.info-page');
							var new_html_elem = infoPage.find('.info-item:first').clone();
							new_html_elem.find('.author-img>img').attr('src', (this['user_ico']?this['user_ico']: 'pictures/signup_avatar.png'));
							new_html_elem.find('.author-detail-name>span').text(this['user_name']?this['user_name']:'匿名');
							new_html_elem.find('.author_distance span').text(this['distance_to_me']);
							new_html_elem.find('.info-content').parent().attr('href','modules.php?app=paper_show_detail&paper_id='+this['paper_id']);

							var imgContent = new_html_elem.find('.info-img');
							if(!this['picture'])
							{
								if(imgContent != null)imgContent.remove();
							}
							else{
								if(imgContent == null)
								{
									var imgContentHtml = $('<div class="info-img"><img src=""/></div>');
									imgContentHtml.prepend(new_html_elem.find('.info_content-wrap'));
								}

								var img = new_html_elem.find('.info-img>img');
								img.attr('src',this['picture']);
							}

							new_html_elem.find('.info-text>p').text(this['content']);
							new_html_elem.find('.left-interchange-record span').text(this['view_count']);
							new_html_elem.find('.right-pick-paper-record span').text(this['pick_count']);
							new_html_elem.find('.right-interchange-record').text(this['public_count']);

							infoPage.append(new_html_elem);
	            		});

					}
	            }

	        });
			});

			<?php if(empty($data) || count($data) == 0) { ?>
				$('#paper_bottom').addClass('nondisplay');
				$('.msg-panel').removeClass('nondisplay');
			<?php }else {?>
				$('#paper_bottom').removeClass('nondisplay');
				$('.msg-panel').addClass('nondisplay');
			<?php } ?>
        });
	</script>

	<div class="top-title">
		<div class="center-title">
				纸条墙
		</div>
		<div class="return-btn">
				 返回 
		</div>
	</div>

	<?php require("modules/paper/common_paper_show.php"); ?>
	<div id="paper_bottom" class="nondisplay"><span id="load_more">加载更多...</span></div>
		<!--消息面板-->
	<div class="msg-panel nondisplay">
		还没有纸条哟，赶紧去发纸条吧!
	</div>

	<?php require("uiparts/footor.php");?>
</body>
</html>