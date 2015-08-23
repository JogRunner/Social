<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/paper/paper_show_detail.html
 * 如果您的模型要进行修改，请修改 models/modules/paper/paper_show_detail.php
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
    
    //变量取得
    $paper_id= intval(get_argg('paper_id'));
    //从数据库中取出纸条信息
    $paper_detail_rs    = api_proxy("paper_get_content", $paper_id);
    $paper_comments_rs  = api_proxy("paper_get_comments", $paper_id);

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
    $is_user_paper = ($user_id==$paper_detail_rs['user_id']) ? 1 : 0;
    //判断纸条是否被当前用户抢到
    $is_user_picked = 0;
    if(0 == $is_user_paper)
    {
        $is_user_picked = api_proxy('paper_get_is_user_picked', $paper_id, $user_id);
    }
    
    //标签
    $title_label = '我的纸条';

    //评论类型
    $comment_type = 0;


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Language" content="zh-cn">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="Description" content="<?php echo $metaDesc;?>" />
<meta name="Keywords" content="<?php echo $metaKeys;?>" />
<meta name="author" content="<?php echo $metaAuthor;?>" />
<meta name="robots" content="all" />
<meta name="viewport" content="width=device-width" />
<style type="text/css">
body{
    font-size: 0.8em;
}
.paper{
    width:100%;
    margin:0 auto;
    margin-bottom: 1em;
}
.head{
    width:3em;
    height:3em;
    float:left;
    margin-right:2em;
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

.info-interchange{padding:0.5em 1em;background-color: #cdf;line-height: 1.5em;height: 1.5em;border-bottom: 0.1em dashed black;}
.info-interchange img{width:1.2em;height: 1.2em; position: relative; top:0.15em;}
.left-interchange-record{float:left;padding: 0 0.5em;}
.right-interchange-record{float:right;padding: 0 0.5em;}

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

.info-comment{}
.comment-item{border-bottom: 0.1em solid gray;background-color: #F5E8CF; margin-bottom: 0.5em;}
.comment-item .comment-content{padding: 0.5em 1em;  }
.comment-item .comment-user-info{border-top: 0.1em dotted #ddd; color:gray; }
.comment-item .comment-user-info .left-user>span{color:gray;}
.comment-item .comment-user-info .left-user{margin-left: 0.5em; float : left;}
.comment-item .comment-user-info .right-user{margin-right: 0.5em; float: right;}
.comment-item-span{height: 0.5em; width: 100%; background-color: #F5E8CF;}
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
    </div>
    <div class="clear"></div>
    <div class="paper_content">
        <div class="img_content">
            <img class="paper_img" src="<?php echo $paper_detail_rs['picture'];?>" />
        </div>
        <div class="text_content"><?php echo $paper_detail_rs['content']?></div>

        <div class="info-interchange">
            <div class="info-interchange-wrap">
                <div class="left-interchange-record">
                    <img src="skin/social/imgs/all/note_pt_feiji.png">
                    <span> <?php echo $paper_detail_rs['view_count']?$paper_detail_rs['view_count']:0;?></span>
                </div>

                <?php if(1 == $is_user_paper || 1 == $is_user_picked){?>
                <div class="right-pick-paper-record">
                    <a href="modules.php?app=pick_paper_detail&paper_id=<?php echo $paper_id; ?>">
                    <img src="skin/social/imgs/all/pick_paper_button.png"/>
                    <span><?php echo $paper_detail_rs['pick_count']?$paper_detail_rs['pick_count']:0;?> </span>
                    </a>
                </div>
                <?php } ?>

                <div class="right-interchange-record">
                    <img src="skin/social/imgs/all/note_btn_pinglun_unpress.png"/>
                    <span> <?php echo $paper_detail_rs['public_count']?$paper_detail_rs['public_count']:0;?> </span>
                </div>
                
                <div class="clearboth"></div>
            </div>
        </div>

    </div>
    <div class="clear"></div>
    
    <div class="info-comment">
        <div class="info-comment-wrap">

        <?php foreach($paper_comments_rs as $comment) { ?>
            <div class="comment-item">
                <div class="comment-content">
                    <p> <?php echo $comment['comment_content'] ?> </p>
                </div>
                <div class="comment-user-info">
                    <div class="left-user">
                        <img src="">
                        <span> <?php echo $comment['user_name'] ?> </span>
                    </div>
                    <div class="right-user">
                        <!-- <span> SYSU</span> -->
                        <!-- <span> Distance</span> -->
                        <span><?php echo $comment['comment_time']?></span>
                    </div>
                </div>
                <div class="clear"></div>
                <div class="comment-item-span"></div>
            </div>
        <?php } ?>
        </div>

    </div>

    <div class="clear"></div>
    </div>

    <div id="paper_bottom"><a href="#">加载更多...</a></div>
    <?php require("uiparts/footor_comment.php");?>
</body>
</html>