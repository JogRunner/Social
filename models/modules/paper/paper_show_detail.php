<?php
    //引入语言包
    $pu_langpackage=new publiclp;
    
    //引入公共模块
    require("foundation/module_users.php");
    require("foundation/fcontent_format.php");
    require("foundation/fpages_bar.php");
    require("api/base_support.php");
    
    //变量取得
    $url_pid= intval(get_argg('paper_id'));
    //从数据库中取出纸条信息
    $paper_detail_rs    = api_proxy("paper_get_content", $url_pid);
    $paper_comments_rs  = api_proxy("paper_get_comments", $url_pid);
?>