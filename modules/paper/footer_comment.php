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
?>

<style>
.foot{position:fixed; left:0; right:0; bottom:0; width:100%; background: #fff;}

.comment_div {float: left;width: 100%;height: 77.8px;}     
.comment_form_div {width: 100%;background-color: #fff;color:#aaa;margin-top:1em;border-top: 1px solid #aaa;border-bottom: 1px solid #aaa;}
.comment_form_div .inputContent{width: 80%;height: 3em; border: 0;color:#666;}    
.comment_form_div .postBtn {width: 20%; height: 3em;color: #808080;padding: 0.5em 1em;cursor: pointer;border: 0;}     
.postBtn:hover {color: #333;background-color: #efefef;}

</style>
<div class="foot">
	
    <div class="comment_div">     
       <div class="comment_form_div"><form method="post" action="#"><input type="text" class="inputContent" value="发表你的评论"/><input type="submit" value="发表" class="postBtn" /></form></div> 
    </div>
</div>