<?php
    //引入语言包
    $pu_langpackage=new publiclp;
    
    //引入公共模块
    require("foundation/module_users.php");
    require("foundation/fcontent_format.php");
    require("foundation/fpages_bar.php");
    require("api/base_support.php");
    
    //用户id
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

    //变量取得
    $paper_id= intval(get_argg('paper_id'));

    //标签
    $title_type = intval(get_argg('title_type'));
    if(1 == $title_type)
    {
        $title_label = '我抢的理由';
    }else{
        $title_label = '回复';
    }
    
?>