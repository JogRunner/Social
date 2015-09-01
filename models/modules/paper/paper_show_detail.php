<?php
    //引入语言包
    $pu_langpackage=new publiclp;
    
    //引入公共模块
    require("foundation/module_users.php");
    require("foundation/fcontent_format.php");
    require("foundation/fpages_bar.php");
    require("api/base_support.php");
    
    $user_id = get_sess_userid();

    if(empty($user_id))
    {
        header("location:error.php");
        exit;
    }

        //变量取得
    $paper_id= intval(get_argg('paper_id'));
    //从数据库中取出纸条信息
    $paper_detail_rs    = api_proxy("paper_get_content", $paper_id);
    $paper_comments_rs  = api_proxy("paper_get_comments", $paper_id);

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

    //纸条状态
    $paper_status = $paper_detail_rs['paper_status'];
    
    //评论类型
    $comment_type = 0;
?>