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
    //从数据库中取出纸条信息
    $paper_detail_rs    = api_proxy("paper_get_content", $paper_id);
    $paper_reasons_rs   = api_proxy("paper_get_pick_reason", $paper_id);

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
    
    //从数据库中取出纸条信息
    $paper_detail_rs    = api_proxy("paper_get_content", $paper_id);
    $paper_reasons_rs   = api_proxy("paper_get_pick_reasons", $paper_id, $user_id);

    //判断用户是否已经登录
    $is_user_logon = (null == $user_id) ? 0 : $user_id;
    //是否为本人发的帖子

    $is_user_paper = ($user_id!=$paper_detail_rs['user_id']) ? 0 : 1;

    $is_user_picked = api_proxy('paper_get_is_user_picked', $paper_id, $user_id);

    //纸条状态
    $paper_status = $paper_detail_rs['paper_status'];

    $comment_type = 1;
    $user_point = 0;

    //私信回复类型
    if(1 == $is_user_paper){
        $comment_type = 2;
        $user_point = api_proxy('user_get_user_point', $user_id);
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

    .head_info{float:left;}
    .paper_head{float:left;background: #F5E8CF;width: 100%;border-top: 1px dashed black;border-bottom: 1px dashed black;}
    
    .paper_status{float: right;height: 5.7em;line-height: 5.7em;margin-right:1em;}
    .paper_content{width: 100%;}
    .img_content{background: #F5E8CF;text-align: center;padding: 0.5em;}
    .paper_img{width:100%;}

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
    .picker_info{float:left;height:100%;}
    .picker_head_div{font-size: 0.6em;float:left;background: #F5E8CF;width: 100%;
        border-top: 1px dashed black;border-bottom: 1px dashed black;}

    .bless_div{float: right; height: 3.8em;line-height: 3.8em;padding: 0 1em;}


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

    <div class="paper">
        <div class="paper_head">
            <img src="pictures/<?php echo $paper_detail_rs['user_ico'] ?>" class="head"/>
            <div class="head_info">
                <h4 class="user_name"><?php echo $paper_detail_rs['user_name']; ?></h4>
                <h5 class="paper_distance">距离:5000m</h5>
            </div>
            <div class="paper_status"><span>
                <b><?php if(0 == $paper_status){echo '未解决';}else if(1 == $paper_status){echo '已解决';}else{echo '已过期';}?>
                </b>&nbsp;</span>
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

    <?php $count=0;?>
    <?php foreach ($paper_reasons_rs as $commenter_id => $paper_pick_reason) { ?>
        <?php $commenter_info = $paper_pick_reason[0]?>
        <div class="pick_reason_div">
            <div class="picker_head_div">
                <img src="pictures/<?php echo $commenter_info['user_ico']; ?>" class="picker_head"/>
                <div class="picker_info">
                    <p class="picker_name"><?php echo $commenter_info['user_name']; ?></p>
                    <p class="picker_distance">距离:500m</p>
                </div>

                <form action="do.php?act=bless_submit" method="post">
                <div class="bless_div">
                    <?php if((0 == $paper_status) && 1 == $is_user_paper){?>
                    <span><?php echo ++$count;?>楼, 问题解决送祝福，总送ta积分数:
                        <select name='give_point' style='width:5em'>
                            <option value='1' selected>1</option>
                            <option value='2'>2</option>
                            <option value='3'>3</option>
                            <option value='4'>4</option>
                            <option value='5'>5</option> 
                        </select>
                    </span>
                    <input type="submit" value="送祝福" />
                    <?php }else if(1 == $paper_status && $commenter_info['user_id'] == $paper_detail_rs['receiver_id']){ ?>
                        <span><?php echo ++$count;?>楼, 已接受</span>
                    <?php }else{ ?>
                        <span><?php echo ++$count;?>楼</span>
                    <?php }?>

                    <input name="paper_id" value="<?php echo $paper_detail_rs['paper_id']; ?>" type="hidden"/>
                    <input name="point_receiver_id" value="<?php echo $commenter_info['user_id'];?>" type="hidden"/>
                    <input name="user_id" value="<?php echo $user_id;?>" type="hidden"/>
                </div>
                </form>
            </div>
            <div class="clear"></div>

            <?php foreach ($paper_pick_reason as $index => $pick_reason) { ?>
                <div class="pick_reply_div">

                    <?php if(($pick_reason['user_id'] == $user_id && $pick_reason['comment_type'] == 1) 
                                || ($pick_reason['user_id'] != $user_id && $pick_reason['comment_type'] == 2)){?>
                        <div class="pick_reply_reason_div">
                            <span>我:</span><?php echo $pick_reason['comment_content'];?>
                            <div class="replytime"><?php echo $pick_reason['comment_time'];?></div>
                            <?php echo $pick_reason['user_id']."+".$user_id."+".$pick_reason['comment_type']; ?>
                        </div>
                    <?php }else{?>
                        <span>
                            <?php if($pick_reason['comment_type'] == 1){echo $pick_reason['user_name'];}else{echo $paper_detail_rs['user_name'];} ?>:
                        </span><?php echo $pick_reason['comment_content'];?>
                        <div class="replytime"><?php echo $pick_reason['comment_time'];?>
                            <a onclick="show_comment(<?php echo $commenter_info['user_id'];?>, '<?php echo $commenter_info['user_name'];?>');" class="reply_a">
                                <img src="pictures/reply.png" class="reply_img"/>
                            </a>
                        </div>
                    <?php }?>
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