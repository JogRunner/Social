<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/paper/send_help_paper.html
 * 如果您的模型要进行修改，请修改 models/modules/paper/send_help_paper.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
	header("content-type:text/html;charset=utf-8");

	require("foundation/asession.php");
	require("configuration.php");
	require("includes.php");
	require("foundation/module_users.php");
	require("foundation/fcontent_format.php");
	require("foundation/fplugin.php");
	require("api/base_support.php");

	$user_id = get_sess_userid();

	if(empty($user_id))
	{
		$code = get_argg('code');
		if(!empty($code))
			save_weixin_session($code);
	}
	if($local_debug)
	{
		set_sess_username("FanJian");
		set_sess_userid("1");
	}
	
	$user_id = get_sess_userid();
	$user_name = get_sess_username();
	
	if(empty($user_id))
	{
		header("location:error.php");
		exit;
	}

	//引入语言包
    $pu_langpackage=new publiclp;

	$user_id = get_sess_userid();
	//如果user_id为null判断为用户未登录，这时候需要跳转到登录界面
	$title_label = '写纸条';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<link href="plugins/calendar/lhgcalendar.css" rel="stylesheet"/>
<script src="srcfiles/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="plugins/calendar/lhgcore.min.js"></script>
<script type="text/javascript" src="plugins/calendar/lhgcalendar.min.js"></script>

<script type="text/javascript">
	
	function limitImg(){    

        var img=document.getElementById(arguments[0]);//显示图片的对象    
        var maxSize=arguments[1];//  最大值  
        var allowGIF=arguments[2]||false;  //是否允许GIF  
        var maxWidth=arguments[3]||0;  //  
        var maxHeight=arguments[4]||0;    
        var postfix=getPostfix(img.src);    
        var str=".jpg";    
        if(allowGIF){str+=".gif"}    
        if(str.indexOf(postfix.toLowerCase())==-1){    
            if(allowGIF){return "图片格式不对，只能上传jpg或gif图像";}else{return "图片格式不对，只能上传jpg图像";}    
        }else if(img.fileSize>maxSize*1024){    
            return "图片大小超过限制,请限制在"+maxSize+"K以内";    
        }else{    
            if(img.fileSize==-1){    
                return "图片格式错误，可能是已经损坏或者更改扩展名导致，请重新选择一张图片";    
            }else{    
                if(maxWidth>0){    
                    if(img.width>maxWidth){    
                        return "图片宽度超过限制，请保持在"+maxWidth+"像素内";    
                    }else{    
                        if(img.height>maxHeight){    
                            return "图片高度超过限制，请保持在"+maxHeight+"像素内";    
                        }else{    
                            return "";    
                        }    
                    }    
                }else{    
                    return "";    
                }    
            }    
        }    
	}    
    //根据路径获取文件扩展名    
    function getPostfix(path){    
        return path.substring(path.lastIndexOf("."), path.length);    
    }

    $(document).ready(function(){
		$('.content-area').css('height',$(window).height() - $('.write-item').outerHeight(true) * 3 - $('.adjust-height').outerHeight(true));
		
		$('.position-btn').click(function(){
			$schoolList = $('.school-list');
			if($schoolList.hasClass('nondisplay'))
			{
				$schoolList.removeClass('nondisplay');
				$schoolList.width( 2 * $(this).width());
			}
			else
			{
				$schoolList.addClass('nondisplay');
			}
			return false;

		});
		
			$('.school-list .known-value').click(function(){
				$('#school-value').text($(this).text());
				$('#hidden-school-value').text($(this).text());
				$('.school-list').addClass('nondisplay');
			});
			$('#school-input-area').change(function(event){
				var v = $(this).val().substring(0,15);
				$('#school-value').text(v);
				$('#hidden-school-value').val(v);
				$('.school-list').addClass('nondisplay');
				 return false;
			});
			$('#school-input-area').click(function(){
				return false;
			});

			$('.img-path-value').change(function(){
				var path = $(this).val();
				$('.left-img-info>img').css('src', path);
				var index = path.lastIndexOf('/');
				if(index == -1) index = path.lastIndexOf('\\');
				if(index != -1)
					path = path.substring(index+1, path.length);
					$('#img-path').text(path);
			});
			$('.right-button').click(function(){
				if($('textarea').val() == ""){
					$('textarea').focus();
					return false;
				}
				$('#paper-form').submit();
			});
			$(window).keypress(function(e){
				if(e.keyCode == 13)
				{
					if(!$('.school-list').hasClass('nondisplay'))
						$('#school-input-area').change();
					e.preventDefault();
				}
			})
			$(window).click(function(){
				if(!$('.school-list').hasClass('nondisplay'))
					$('.school-list').addClass('nondisplay');
			});

			$(window).resize(function(){
				$('.content-area').css('height',$(window).height() - $('.write-item').outerHeight(true) * 3 - $('.adjust-height').outerHeight(true));
			});

			J(function(){
				J('#datetime-btn').calendar({id:'dg',btnBar:false,minDate:'%y-%M-%d'});
			});	
			$('#datetime-btn').click(function(){
					if(!$('.school-list').hasClass('nondisplay'))
					$('.school-list').addClass('nondisplay');
			});
			$('#dg').focus(function(){
				$(this).blur();
			});
	});


    //验证表单
	function validate_form(thisform)
	{
	}

</script>

<style>
	.clearboth{clear: both;}
	.nondisplay{display: none;}
	*,html,body{margin:0;padding:0;border:0;}
	html,body{width:100%;}
	.write-item{border-bottom: 1px solid green;padding: 0.5em 1em;}
	.left-nav{float:left;}
	.left-nav>img{width:2em; height: 2em; float:left;}
	.left-nav .info-label{ margin-left: .5em; line-height:2em; height:2em;float:left;}
	.right-button{float:right; width:4em; height:2em;}
	.button, .button:visited {
		background: #222 repeat-x;
		display: inline-block;
		padding: 5px 10px 6px;
		color: #fff;
		text-decoration: none;
		-moz-border-radius: 6px;
		-webkit-border-radius: 6px;
		-moz-box-shadow: 0 1px 3px rgba(0,0,0,0.6);
		-webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.6);
		text-shadow: 0 -1px 1px rgba(0,0,0,0.25);
		border-bottom: 1px solid rgba(0,0,0,0.25);
		cursor: pointer;
	}
	.pink.button, .magenta.button:visited	{ background-color: #e22092; }
	.pink.button:hover					{ background-color: #c81e82; }
	.green.button, .green.button:visited	{ background-color: #91bd09; }
	.green.button:hover				        { background-color: #749a02; }
	.red.button, .red.button:visited		{ background-color: #e62727; }
	.red.button:hover					{ background-color: #cf2525; }
	.orange.button, .orange.button:visited	{ background-color: #ff5c00; }
	.orange.button:hover				{ background-color: #d45500; }
	.blue.button, .blue.button:visited   	        { background-color: #2981e4; }
	.blue.button:hover					{ background-color: #2575cf; }
	.yellow.button, .yellow.button:visited	{ background-color: #ffb515; }
	.yellow.button:hover				{ background-color: #fc9200; }
	textarea {
		width:96%;
		height:100%;
		color: #555;
		font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
		font-size: 1em;
		line-height: 1.4em;
		background-color:#fef;
		padding:0.5em 2%;
	}

		.adjust-height{margin-top: 1em;}
		.common-select{
		height:3em;
		width:100%;
		line-height: 3em;
		border-bottom: 0.1em solid #fec;
		vertical-align: middle;
		text-align: center;
		position: relative;
	}
	.common-select-wrap{
	}

	.leftwidth{width:30%; float:right; text-align: center; position: relative; display: inline-block; overflow: hidden;text-indent: 0;text-decoration: none;height: 100%;color: #1E88C7;}
	.file {
	    background: #D0EEFF;
	    border-left: 1px solid #99D3F5;
	}
	.file input {
	    position: absolute;
	    font-size: 2em;
	    right: 0;
	    top: 0;
	    opacity: 0;
	}
	.file:hover {
	    background: #AADFFD;
	    border-color: #78C3F3;
	    color: #004974;
	    text-decoration: none;
	}
	.position-btn{background: #9f9; border-left:1px solid #6f6; }
	.position-btn:hover{background: #6f6; border-color: #78C3F3; text-decoration: none; color:#004974;}

	#school-value{overflow: hidden;}
	.datetime-btn{background: #afe; border-left:1px solid #7fe;}
	.datetime-btn:hover{background: #7fe; border-color: #78C3F3; text-decoration: none; color:#004974;}
	
	#dg{height: 3em; line-height: 3em; width:50%;text-align: center; font-size: 1em;}
	.school-list li{list-style: none; border: 0.1em solid #abc;height: 3em;}
	.school-list {background-color: #eee; position: absolute; right: 0;bottom: 3em; z-index: 100; overflow: hidden;}
	.school-list li>input{width: 100%; height: 2.9em; font-size: 1em; text-align: center; }
	a:focus{outline: none;}
	a{-webkit-tap-highlight-color:rgba(0,0,0,0); }
</style>

</head>

<body>
	<!--
	<span class="title">
		<a href="javascript:history.go(-1);" class="title_back">返回</a>
		<div class="title_pick"><?php echo $title_label; ?></div>
	</span>
	<div class="gap"></div>-->
	<form method="post" action="do.php?act=help_paper_submit"  enctype="multipart/form-data"  id="paper-form" onsubmit="return validate_form(this);">
		<div class="write-item">
			<div class="left-nav">
				<img src="pictures/ic_launcher.png"/>
				<div class="info-label">
					<span> 写纸条 </span>
				</div>
			</div>
			<div class="right-button">
				<input type="submit" class="button pink" value="贴出"/>
			</div>
			<div class="clearboth">
			</div>
		</div>

		<div class="content-area">
			<textarea placeholder="小纸条文字不能超过120字"  name="comment_text" id="comment_text"></textarea>
			<div class="img-and-info-area">
				<div class="left-img-info">
					<img src=""/>
				</div>
				<div class="right-span-label">
				</div>
			</div>
		</div>

		<div class="common-select adjust-height">
			<div class="common-select-wrap">
				<span id="img-path" style="overflow:hidden;"></span>
				<a href="javascript:;" class="file leftwidth">添加图片
					<input class="img-path-value" type="file" id="upload_picture" 
					name="attach[]" onchange="limitImg('upload_picture', 20480);" />
				</a>
				<div class="clearboth">
			</div>
			</div>
		</div>

		<div class="common-select">
			<div class="common-select-wrap">
				<span id="school-value"> 中山大学</span>
				<input type="hidden" id="hidden-school-value" name="help-location" value="中山大学" length="15" style="overflow:hidden"/>
				<a href="javascript:;" class="position-btn leftwidth">
					求助位置
				</a>
				<div class="school-list nondisplay">
						<li class="school-item known-value">
							中山大学
						</li>
						<li class="school-item known-value">
							华南理工大学
						</li>
						<li class="school-item known-value">
							广州外语外贸大学
						</li>
						<li class="school-item">
							<input type="text" placeholder="您的学校" id="school-input-area">
					    </li>
					</div>
				<div class="clearboth"></div>
			</div>
		</div>

		<div class="common-select">
			<div class="common-select-wrap">
				<input type='text' id="dg" name="help-last-time"/>
				<a class="datetime-btn leftwidth" id ="datetime-btn" href="javascript:;">
					截止时间
				</a>
				<div class="clearboth"></div>
			</div>
		</div>

	</form>
</body>

</html>