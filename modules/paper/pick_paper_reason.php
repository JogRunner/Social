<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/paper/pick_paper_reason.html
 * 如果您的模型要进行修改，请修改 models/modules/paper/pick_paper_reason.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
    //引入语言包
    $pu_langpackage=new publiclp;
    
    //引入公共模块
    require("foundation/module_users.php");
    require("foundation/fcontent_format.php");
    require("foundation/fpages_bar.php");
    require("api/base_support.php");
    
    //用户id
    $user_id = get_session('user_id');
    if(null == $user_id)
    {
        echo "<script>alert('null == \$user_id');</script>";
    }
    //变量取得
    $paper_id= intval(get_argg('paper_id'));
    //从session中取出用户信息
    $user_name = get_session('user_name');
    if(null == $user_name)
    {
        $result_name = api_proxy('user_get_user_name', $user_id);
        if(null == $result_name['user_name'])
        {
            echo "<script>alert('null == \$result_name['user_name']');</script>";
        }
        else{
            $user_name = $result_name['user_name'];
            set_session('user_name', $user_name);
        }
        
    }
    $user_ico = get_session('user_ico');
    if(null == $user_ico)
    {
        $result_head = api_proxy('user_get_user_ico', $user_id);
        if(null == $result_head['user_ico'])
        {
            echo "<script>alert('null == \$result_head['user_ico']');</script>";
        }
        else{
            $user_ico = $result_head['user_ico'];
            set_session('user_ico', $user_ico);
        }
        
    }

    //标签
    $title_type = intval(get_argg('title_type'));
    if(1 == $title_type)
    {
        $title_label = '我抢的理由';
    }else{
        $title_label = '回复';
    }
    
?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width" />
<script src="srcfiles/jquery-2.1.1.min.js"></script>
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
		$('.content-area').css('height',$(window).height() - $('.write-item').outerHeight(true) * 4);
		
		$('.help-location-value').click(function(){
			$schoolList = $('.school-list');
			if($schoolList.hasClass('nondisplay'))
			{
				$schoolList.removeClass('nondisplay');
			}
			else
			{
				$schoolList.addClass('nondisplay');
			}
		});
		
			$('.school-list li').click(function(){
				$('.help-location-value').text($(this).text());
				$('.school-list').addClass('nondisplay');
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

	.write-item{border-bottom: 1px solid green;}
	.left-nav{float:left;}
	.left-nav>img{width:2em; height: 2em; float:left;}
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
		position: relative;
		left:-2em;
		top:1.8em;
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
		width:100%;
		height:200px;
		color: #555;
		font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
		padding:0.8em 1em;
		font-size: 1em;
		line-height: 1.4em;
		background-color:#fef;
	}

	.help-location{padding:0.5em 1em; background-color:#ffe; line-height:2em; height:2em; position:relative;}
	.help-location-value{float:right; position:relative; margin-right:2em;}
	.school-list{position:absolute; bottom:3em; right:0em;background-color:white; border:0.1em solid green;}
	.school-list li{list-style:none; padding:0.5em 2em; border-bottom:0.2em solid #aff;text-align:center;}

	.last-datetime{padding:0.5em 1em; line-height:2em; height:2em; background-color:#eef;}
	.last-datetime>span{float:left;}
	.last-datetime>.datetime-value{float:right;}
	.last-datetime>.datetime-value>input{float:right;margin-right:2em;margin: 0.5em}

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
		top:0.9em;
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


	.head{
		width:3em;
		height:3em;
		float:left;
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
</style>

</head>

<body>
	<span class="title">
		<a href="javascript:history.go(-1);" class="title_back">返回</a>
		<div class="title_pick"><?php echo $title_label; ?></div>
	</span>
	<div class="gap"></div>
	<form method="post" action="do.php?act=pick_paper_submit" onsubmit="return validate_form(this);">
		<div class="write-item">
			<div class="paper_head">
				<img src="pictures/<?php echo $user_ico; ?>" class="head"/>
				<div class="head_info">
					<h4 class="user_name"><?php echo $user_name; ?></h4>
					<h5 class="paper_distance">距离:5000m</h5>
				</div>
			
			
				<div class="right-button">
					<input type="submit" class="button pink" value="就你了"/>
				</div>
			</div>
		</div>
		<div class="clearboth"></div>
		<div class="content-area">
			<textarea placeholder="我抢理由不能超过120字"  name="pick_paper_reason"></textarea>
			<div class="img-and-info-area">
				<div class="left-img-info">
					<img src=""/>
				</div>
				<div class="right-span-label">
				</div>
			</div>
		</div>

		<div class="help-location">
			<span> 我所在位置: </span>
			<div class="help-location-value">
				<span> 中山大学</span>
			</div>
			<div class="school-list nondisplay">
				<ul>
					<li> 中山大学 </li>
					<li> 华南理工大学 </li>
				</ul>
			</div>
			<div class="clearboth"></div>
		</div>

		<div class="last-datetime">
			<span> 有效时间 </span>  
			<div class="datetime-value">
				<input type="text" name="paper_datetime" value="2015-8-20 12:35:06"/>
			</div>
		</div>
		<input name="paper_id" value="<?php echo $paper_id; ?>" type="hidden"/>
		<input name="comment_type" value="1" type="hidden"/>
	</form>

</body>

</html>