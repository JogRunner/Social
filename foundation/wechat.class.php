<?php
class wechat
{
    var $appid = "wx0791ceb758ff8a6c";
    var $appSecret = "4ecca970852321c2654cca865c87310c";

    public function valid()
    {
        $echoStr = $_GET["echostr"];
    
        //valid signature , option
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    public function responseMsg()
    {
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        $this->LogString("Response Msg: ".$postStr);
        
        //extract post data
        if (!empty($postStr)){
                /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
                   the best way is to check the validity of xml by yourself */
                libxml_disable_entity_loader(true);
                $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                
                $res = array("nothing", "0");

                //订阅
                if(!empty($postObj->MsgType) && !empty($postObj->Event) &&
                    $postObj->MsgType == "event")
                {
                    if($postObj->Event == "subscribe")
                        $res = $this->subscribe($postObj);
                    else if($postObj->Event == "unsubscribe")
                        $res = $this->unSubscribe($postObj);
                    else if($postObj->Event == "SCAN")
                        $res = $this->scanIcon($postObj);
                    else if($postObj->Event == "LOCATION")
                        $res = $this->collectUserPosition($postObj);
                    else if($postObj->Event == "VIEW")
                        $res = $this->clickMenu($postObj);
                }

        }else {
            echo "haha";
            exit;
        }
    }
    
    //订阅
    private function subscribe($postObj)
    {
        $user_openid = $postObj->FromUserName;
        //$user_add_time = $postObj->CreateTime;

        $this->LogString("\nFrom User: ".$user_openid);

        //获取基本信息
        $access_token = $this->fileGetWeixinToken();

        $this->LogString("\nAccess Token: ".$access_token['access_token']);

        $url = sprintf('https://api.weixin.qq.com/cgi-bin/user/info?access_token=%s&openid=%s&lang=zh_CN', $access_token['access_token'], $user_openid);
        $user_info = $this->curl_request($url);

        $this->LogString("Perpare Insert : ".$user_info);
        if(!empty($user_info))
        {
            $temp_user_info = json_decode($user_info, true);
            if(!array_key_exists('errcode', $temp_user_info) )
            {
                api_proxy('paper_related_add_user', $temp_user_info);
            }
        }
    }

    //取消订阅
    private function unSubscribe($postObj)
    {
        $user_openid = $postObj->FromUserName;
        if(!empty($user_openid))
            api_proxy('paper_related_del_user',$user_openid);
    }

    //用户关注后扫描二维码
    private function scanIcon($postObj)
    {

    }

    //收集用户地址
    private function collectUserPosition($postObj)
    {

    }

    //用户点击菜单
    private function clickMenu($postObj)
    {
        $user_openid = $postObj->FromUserName;
        if(!empty($user_openid))
        {
            //api_proxy('paper_related_save_user_session', $user_openid);
        }
    }

    public function createMenu()
    {
        $menu1 = urlencode('http://52.74.218.115/Social/index.php');
        $menu2 = urlencode('http://52.74.218.115/Social/modules.php?app=send_help_paper');
        $menu3 = urlencode('http://52.74.218.115/Social/modules.php?app=user_settings');

        $prefix_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid="
        .$this->appid."&redirect_uri=";
        $post_url = "&response_type=code&scope=snsapi_base&state=paperfromWeixin#wechat_redirect";

        $data = '{
                     "button":[
                     {  
                          "type":"view",
                          "name":"纸条库",
                          "url":"'.$prefix_url.$menu1.$post_url.'"
                      },
                     {  
                          "type":"view",
                          "name":"我帖",
                          "url":"'.$prefix_url.$menu2.$post_url'"
                      },
                     {  
                          "type":"view",
                          "name":"设置",
                          "url":"'.$prefix_url.$menu3.$post_url'"
                      }
                    ]
                }';
        echo $data;
        $access_token = $this->fileGetWeixinToken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token['access_token'];
        echo $url;
        echo $this->curl_request($url, $data);
    }

    function curl_request($url , $postFields = NULL){
       $ch = curl_init();
        curl_setopt( $ch , CURLOPT_TIMEOUT , 3 );
        curl_setopt( $ch , CURLOPT_URL , $url );
        curl_setopt( $ch , CURLOPT_FAILONERROR , FALSE );
        curl_setopt( $ch , CURLOPT_RETURNTRANSFER , TRUE );
        //https 请求
        if ( strlen( $url ) > 5 && strtolower( substr( $url , 0 , 5 ) ) == 'https' ){
            curl_setopt( $ch , CURLOPT_SSL_VERIFYPEER , FALSE );
            curl_setopt( $ch , CURLOPT_SSL_VERIFYHOST , FALSE );
        }

        if ( is_array( $postFields ) && 0 < count( $postFields ) ){
            $postBodyString = '';
            $postMultipart  = FALSE;
            foreach ( $postFields as $k => $v ) {
                if ( '@' != substr( $v , 0 , 1 ) ) //判断是不是文件上传
                {
                    $postBodyString .= "$k=" . urlencode( $v ) . "&";
                } else {
                    //文件上传用multipart/form-data，否则用www-form-urlencoded
                    $postMultipart = TRUE;
                }
            }
            $postFields = trim( $postBodyString , '&' );
            unset( $k , $v );
            curl_setopt( $ch , CURLOPT_POST , TRUE );
            if ( $postMultipart ){
                curl_setopt( $ch , CURLOPT_POSTFIELDS , $postFields );
            } else {
                curl_setopt( $ch , CURLOPT_POSTFIELDS , $postFields );
            }
        }else if(!empty($postFields))
        {
            curl_setopt( $ch , CURLOPT_POST , TRUE );
            curl_setopt( $ch , CURLOPT_POSTFIELDS , $postFields );    
        }

        $reponse = curl_exec( $ch );
        curl_close( $ch );
        return $reponse;
    }

    private function initToken()
    {
        $url = sprintf("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s",$this->appid, $this->appSecret);
    
        $tokenData = $this->curl_request($url);
        $tokenData = json_decode($tokenData);

        //print_r( $tokenData );
    //echo $this->tokenFile();

        $token = $tokenData->access_token;
        $token_array = array("access_token" => $token);
    echo $token;

        if(empty($token))
        {
            throw new Exception("Error: Token Expired");
        }else{
            $token_array['get_token_time'] = time();
            file_put_contents($this->tokenFile(), json_encode($token_array));
        }
        return $token_array;
    }

    //获取Token
    private function fileGetWeixinToken()
    {
        $now_time = time();
        $local_token = file_get_contents($this->tokenFile());
        $token_array = json_decode($local_token, true);
        if(!is_array($token_array) || !isset($token_array['get_token_time'])){
            $token_array = $this->initToken();
        }else {
            if($now_time - $token_array['get_token_time'] > 7000){
                $token_array = $this->initToken();
            }
        }
        return $token_array;
    }

    private function tokenFile()
    {
        global $webRoot;
        return $webRoot.'log/get_token.txt';
    }
    private function LogString($info)
    {
        global $webRoot;
        file_put_contents($webRoot.'log/test.txt', $info, FILE_APPEND); 
    }

    private function returnResult($postObj, $resContext, $resCode)
    {
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $sendTime = $postObj->CreateTime;
        $msgType = $postObj->MsgType;

        $keyword = trim($postObj->Content);
        $time = time();
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>%s</FuncFlag>
                    </xml>";             
        if(!empty( $keyword ))
        {
            $msgType = "text";
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $resContext, $resCode);
            echo $resultStr;
        }else{
            echo "nothing";
        }
    }

    private function checkSignature()
    {
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
                
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
}
?>
