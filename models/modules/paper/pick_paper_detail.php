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
    
    //从数据库中取出纸条信息
    $paper_detail_rs    = api_proxy("paper_get_content", $paper_id);
    $paper_reasons_rs   = api_proxy("paper_get_pick_reasons", $paper_id, $user_id);

    //判断用户是否已经登录
    $is_user_logon = (null == $user_id) ? 0 : $user_id;
    //是否为本人发的帖子
    $is_user_paper = ($user_id!=$paper_detail_rs['user_id']) ? 0 : 1;

    $is_user_picked = api_proxy('paper_get_is_user_picked', $paper_id, $user_id);

    if($is_user_picked === 1 || $is_user_paper === 1)
    {
        api_proxy('paper_related_update_paper_unread', $user_id, $paper_id, $is_user_paper);
    }

    //纸条状态
    $paper_status = $paper_detail_rs['paper_status'];

    $comment_type = 1;
    $user_point = 0;
    //私信回复类型
    if(1 == $is_user_paper){
        $comment_type = 2;
        $user_point = api_proxy('user_get_user_point', $user_id);
    }

?>