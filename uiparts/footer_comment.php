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

.comment_div {float: left;width: 100%;}     
.comment_form_div {width: 100%;background-color: #fff;color:#aaa;margin-top:1em;border-top: 1px solid #aaa;border-bottom: 1px solid #aaa;} 
.comment_form_div .postBtn {width: 20%; height: 3em;color: #808080; cursor: pointer;border: 0;float:right;}     
.comment_form_div .postBtn:hover {color: #333;background-color: #efefef;}


#comment_textarea { 
    display:inline;
    float:left;
    overflow: hidden; 
    width: 80%; 
    font-size: 0.8em;
    height: 1.5em;
    line-height: 1.5em;
    padding:1.5em 0; 
    border: 0;
    text-indent: 1em;
}


</style>

</style>

<script type="text/javascript">
    /**
 * 文本框根据输入内容自适应高度
 * @param                {HTMLElement}        输入框元素
 * @param                {Number}                设置光标与输入框保持的距离(默认0)
 * @param                {Number}                设置最大高度(可选)
 */
var autoTextarea = function (elem, extra, maxHeight) {
        extra = extra || 0;
        var isFirefox = !!document.getBoxObjectFor || 'mozInnerScreenX' in window,
        isOpera = !!window.opera && !!window.opera.toString().indexOf('Opera'),
                addEvent = function (type, callback) {
                        elem.addEventListener ?
                                elem.addEventListener(type, callback, false) :
                                elem.attachEvent('on' + type, callback);
                },
                getStyle = elem.currentStyle ? function (name) {
                        var val = elem.currentStyle[name];
 
                        if (name === 'height' && val.search(/px/i) !== 1) {
                                var rect = elem.getBoundingClientRect();
                                return rect.bottom - rect.top -
                                        parseFloat(getStyle('paddingTop')) -
                                        parseFloat(getStyle('paddingBottom')) + 'px';        
                        };
 
                        return val;
                } : function (name) {
                                return getComputedStyle(elem, null)[name];
                },
                minHeight = parseFloat(getStyle('height'));
 
        elem.style.resize = 'none';
        var elem_submit = document.getElementById("comment_submit");

        var change = function () {
                var scrollTop, height,
                        padding = 0,
                        style = elem.style;
 
                if (elem._length === elem.value.length) return;
                elem._length = elem.value.length;
 
                if (!isFirefox && !isOpera) {
                        padding = parseInt(getStyle('paddingTop')) + parseInt(getStyle('paddingBottom'));
                };
                scrollTop = document.body.scrollTop || document.documentElement.scrollTop;
                elem.style.height = minHeight + 'px';

                elem_submit.style.height = minHeight + 'px';
                if (elem.scrollHeight > minHeight) {
                        if (maxHeight && elem.scrollHeight > maxHeight) {
                                height = maxHeight - padding;
                                style.overflowY = 'auto';
                        } else {
                                height = elem.scrollHeight - padding;
                                style.overflowY = 'hidden';
                        };
                        style.height = height + extra + 'px';
                        scrollTop += parseInt(style.height) - elem.currHeight;
                        document.body.scrollTop = scrollTop;
                        document.documentElement.scrollTop = scrollTop;
                        elem.currHeight = parseInt(style.height);
                        
                        elem_submit.style.height = elem.offsetHeight + 'px';
                };
        };
 
        addEvent('propertychange', change);
        addEvent('input', change);
        addEvent('focus', change);
        change();
};
</script>

<div class="foot">
    
    <div class="comment_div">     
       <div class="comment_form_div"><form method="post" action="#"><textarea id="comment_textarea" placeholder="回复内容"></textarea><input type="submit" value="发表" id="comment_submit" class="postBtn" /></form></div> 
    </div>


    <script> 
        var text = document.getElementById("comment_textarea");
        autoTextarea(text);// 调用
    </script>
</div>