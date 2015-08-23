<?php
    //引入语言包
    $pu_langpackage=new publiclp;
    
    //引入公共模块
    require("foundation/module_users.php");
    require("foundation/fcontent_format.php");
    require("foundation/fpages_bar.php");
    require("api/base_support.php");
    
    //变量取得
    $paper_id= intval(get_argg('paper_id'));

    /*if(empty(get_sess_userid()))
    {
        $code = get_argg('code');
        if(!empty($code))
            save_weixin_session($code);
    }

    if(empty(get_sess_userid()))
    {
        header("location:error.php");
        exit;
    }*/

    //用户id
    $user_id = get_session('user_id');

    /*//用户未登录，暂时设定为固定id
    if(null == $user_id)
    {
        $user_id = 1;
        set_session('user_id', $user_id);
    }*/
    
    //判断用户是否已经登录
    $is_user_logon = (null == $user_id) ? 0 : $user_id;
    //是否为本人发的帖子
    $is_user_paper = ($user_id==$paper_detail_rs['user_id']) ? 0 : 1;
    //标签
    $title_label = '我抢私信列表';

    //从数据库中取出纸条信息
    $paper_detail_rs    = api_proxy("paper_get_content", $paper_id);
    $paper_reasons_rs   = api_proxy("paper_get_pick_reasons", $paper_id, $user_id);

    $is_user_picked = api_proxy('paper_get_is_user_picked', $paper_id, $user_id);

    //私信回复类型
    if(0 == $is_user_paper)
    {
        $comment_type = 1;
    }else{
        $comment_type = 2;
    }

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width" />
<script type="text/javascript">
    function show_comment( commenter_id, commenter_name )
    {
        var comment_footer_elem = document.getElementById('comment_footer');
        comment_footer.style.visibility = "visible";

        var footer_comment_textarea = document.getElementById('comment_textarea');
        footer_comment_textarea.focus();
        footer_comment_textarea.placeholder = "私信回复" + commenter_name;

        //修改私信回复用户id
        var commenter_id_input = document.getElementById('commenter_id_input');
        commenter_id_input.value = commenter_id;
    }

</script>

<style>
    body{
        font-size: 0.8em;
    }
    .paper{
        width:100%;
        margin:0 auto;
        margin-bottom: 1em;
    }
    .head{
        width:3.85em;
        height:3.85em;
        float:left;
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
        background: #F5E8CF;
        text-align: center;
        padding: 0.5em;
        
    }
    .paper_img{
        width:100%;
    }
    .text_content{
        text-align: left;
        padding:1em;
        background: #F5E8CF;
        border-top: 1px dashed black;
        border-bottom: 1px dashed black;
    }

    .right-pick-paper-record{float:right;padding: 0 0.5em;}

    a{
        text-decoration: none;
    }

    .clear{
        clear:both;
    }
    .paper_comments{
        padding:1em;
        border-bottom: 1px dashed black;
        background: #F5E8CF;
    }


    #paper_bottom{
        height:2em;
        line-height: 2em;
        text-align:center;
        margin-bottom:8em;
    }

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


    .info-comment{}
    .comment-item{border-bottom: 0.1em solid gray;background-color: #F5E8CF; margin-bottom: 0.5em;}
    .comment-item .comment-content{padding: 0.5em 1em;  }
    .comment-item .comment-user-info{border-top: 0.1em dotted #ddd; color:gray; }
    .comment-item .comment-user-info .left-user>span{color:gray;}
    .comment-item .comment-user-info .left-user{margin-left: 0.5em; float : left;}
    .comment-item .comment-user-info .right-user{margin-right: 0.5em; float: right;}
    .comment-item-span{height: 0.5em; width: 100%; background-color: #F5E8CF;}


    /*私信回复区头部信息css*/
    .picker_head{width:2.8em;height:2.8em;float:left;padding:0.3em 0.3em;}
    .picker_name, .picker_distance{margin:0.5em auto;color: #666;}
    .picker_info{float:left;}
    .picker_head_div{font-size: 0.6em;float:left;background: #F5E8CF;width: 100%;
        border-top: 1px dashed black;border-bottom: 1px dashed black;}


    /*回复内容css*/
    .pick_reason_div{margin-bottom: 1em;}
    .pick_reply_div{background: #F5E8CF; padding:1em 1em; border-bottom: 1px dotted black;}
    .pick_reply_div .replytime{height: 1em;}
    .reply_a{margin-left:1em;}
    .reply_img{width: 1em; height: 1em;}

    .comment_footer{visibility:hidden;}

</style>
    
</head>
<body>
    <span class="title">
        <a href="javascript:history.go(-1);" class="title_back">返回</a>
        <div class="title_pick">私信回复列表</div>
    </span>
    <div class="gap"></div>

    <div class="paper">
        <div class="paper_head">
            <img src="pictures/<?php echo $paper_detail_rs['user_ico'] ?>" class="head"/>
            <div class="head_info">
                <h4 class="user_name"><?php echo $paper_detail_rs['user_name']; ?></h4>
                <h5 class="paper_distance">距离:5000m</h5>
            </div>
        </div>
        <div class="clear"></div>
        <div class="paper_content">
            <div class="img_content">
                <img class="paper_img" src="<?php echo $paper_detail_rs['picture'];?>" />
            </div>
            <div class="text_content"><?php echo $paper_detail_rs['content']?></div>
        </div>
    </div>

    <?php foreach ($paper_reasons_rs as $commenter_id => $paper_pick_reason) { ?>
        <?php $commenter_info = $paper_pick_reason[0]?>
        <div class="pick_reason_div">
            <div class="picker_head_div">
                <img src="pictures/<?php echo $commenter_info['user_ico']; ?>" class="picker_head"/>
                <div class="picker_info">
                    <p class="picker_name"><?php echo $commenter_info['user_name']; ?></p>
                    <p class="picker_distance">距离:500m</p>
                </div>
            </div>
            <div class="clear"></div>

            <?php foreach ($paper_pick_reason as $index => $pick_reason) { ?>
                <div class="pick_reply_div">
                    <?php if($pick_reason['comment_type'] == 1) {?>
                    <div class="pick_reply_reason_div">
                        <?php if($pick_reason['user_id'] != $commenter_info['user_id']){?>
                        <span>我:</span><?php echo $pick_reason['comment_content'];?>
                        <?php }else{ ?>
                        <span><?php echo $pick_reason['user_name']; ?>:</span><?php echo $pick_reason['comment_content'];?>
                        <div class="replytime"><?php echo $pick_reason['comment_time'];?>
                            <a onclick="show_comment(<?php echo $commenter_info['user_id'];?>, '<?php echo $commenter_info['user_name'];?>');" class="reply_a">
                            <img src="pictures/reply.png" class="reply_img"/>
                            </a>
                        </div>
                        <?php } ?>
                        
                    </div>

                    
                    <?php }else{ ?>
                    <div class="pick_reply_reason_div">
                        <?php if(1 == $is_user_paper){?>
                        <span>我:</span>
                        <?php }else{ ?>
                        <span><?php echo $paper_detail_rs['user_name']; ?>:</span>
                        <div class="replytime"><?php echo $pick_reason['comment_time'];?>
                            <a onclick="show_comment(<?php echo $commenter_info['user_id'];?>, '<?php echo $commenter_info['user_name'];?>');" class="reply_a">
                            <img src="pictures/reply.png" class="reply_img"/>
                            </a>
                        </div>
                        <?php } ?>
                        <?php echo $pick_reason['comment_content'];?></div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
    
    <div id="paper_bottom"><a href="#">加载更多...</a></div>

    <div id="comment_footer" class="comment_footer">
    <?php require("uiparts/footor_comment.php");?>
    </div>
</body>

</html>