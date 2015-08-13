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
    $paper_comments_rs  = api_proxy("paper_get_comments", $paper_id);

    //用户id
    $user_id = get_session('user_id');

    //临时设置默认用户id为1
    $user_id = 1;

    //是否为本人发的帖子
    $is_user_paper = ((null==$user_id) || ($user_id!=$paper_detail_rs['user_id'])) ? 0 : 1;

?>