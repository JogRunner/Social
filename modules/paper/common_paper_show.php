<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/paper/common_paper_show.html
 * 如果您的模型要进行修改，请修改 models/modules/paper/common_paper_show.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?> 
 <!--
	使用规则：
	$data //数据

	$main_key = "show_all_papers" //显示所有纸条
			  = "show_all_comments" //显示所有评论
			  = "show_user_send_papers" //显示用户所写的纸条
			  = "show_user_comments" //显示用户所有评论的纸条
			  = "show_user_send_paper_item" //显示用户所写纸条项
			  = "show_user_comment_item" //显示用户所评论项
			  = "send_user_paper" //用户发送纸条界面
	$cur_user //当前用户
	$data['comments'],
	$data[$cur_user],
	$data['comments'][key]['commenter']
 -->
 <style>

		/*信息状态样式*/
		.info-status{padding: 0.5em;font-weight: 800;border-bottom: 0.1em solid gray;background-color: green;}
		.status-detail{float:left;margin-left: 2em;font-size: 1.2em;}
		.status-time{float:right;margin-right: 2em;}
		
		/*纸条作者样式*/
		.info-author{padding: 1em;border-bottom: 0.1em dotted red;background-color: #f0bbff;}
		.author-img>img{width: 2em;height: 2.2em;float: left;}
		.author-detail{margin-left: 1em; float:left;}
		.author-name{font-size: 1em; margin-bottom: 0.1em;}
		.author-distance>img{width:0.8em; height: 1em;}
		.author-distance{font-size: 0.8em; color: yellow;}

		/*纸条内容样式*/
		.info-content{background-color: #cdf; border-bottom: 0.1em dotted #fa3;}
		.info-img>img{width:100%;}
		.info-img{padding: 0.3em; background-color: white;}
		.info-text{color:#fa3; padding: 0.5em 1em; font-size: 0.8em;line-height: 1.5em;}

		/*纸条交互样式*/
		.info-interchange{padding:0.5em 1em;background-color: #cdf;line-height: 1.5em;height: 1.5em;border-bottom: 0.1em dotted #fa3;}
		.info-interchange img{width:1.2em;height: 1.2em; position: relative; top:0.15em;}
		.left-interchange-record{float:left;}
		.right-interchange-record{float: right;}
		.left-interchange-record span{margin-left: 0.2em;}

		/*我参与的纸条评论样式*/
		.current-user-comment{background-color: #cdf; padding: 0.5em 1em;}
		.my-comment-status .author-me-label{font-size: 1em; font-weight: 800px; color:#b50; float :left;line-height: 2em; line-height: 2em;}
		.my-comment-status .author-me-status{float:right; line-height: 2em;height: 2em;background-color: #0bf; border-radius: 1em;padding: 0em 1em;}
		.my-comment-status{padding-bottom: 0.5em; }
		.my-comment-content{font-size: 0.8em; line-height: 1.2em;}

		 /*用户评论表样式*/
		.info-comment{}
		.comment-item{border-bottom: 0.1em solid gray;background-color: #bfe; margin-bottom: 0.5em;}
		.comment-item .comment-content{padding: 0.5em 1em;	}
		.comment-item .comment-user-info{border-top: 0.1em dotted #ddd; color:gray; }
		.comment-item .comment-user-info .left-user>span{
			color:gray;
		}
		.comment-item .comment-user-info .left-user{margin-left: 0.5em; float : left;}
		.comment-item .comment-user-info .right-user{margin-right: 0.5em;
			float: right;}
		.comment-item-span{height: 0.5em; width: 100%; background-color: #bfe;}

	</style>

	<!--信息显示页面 -->
	<div class="info-page">

		<?php foreach($data as $key => $value){?>
		<div class="info-item">
			
			<?php if(in_array($main_key,array("show_user_send_papers", "show_user_send_paper_item"))){?>
			<div class="info-status">
				<div class="info-status-wrap">
					<div class="status-detail">
						<span> <?php echo  get_status($value['paper_status']);?></span>
					</div>
					<div class="status-time">
						<span> <?php echo  $value['create_time'];?> </span>
					</div>
					<div class="clearboth"></div>
				</div>
			</div>

			<div class="clearboth"></div>
			<?php }?>

			<?php if(in_array($main_key, array("show_all_papers","show_all_comments","show_user_comments","show_user_comment_item"))){?>
			<div class="info-author">
				<div class="info-author-wrap">
					<div class="author-img">
						<img src="skin/social/imgs/all/<?php echo (array_key_exists('user_ico',$data) && !empty($data['user_ico']))?$data['user_ico']:'signup_avatar.png';?>"/>
					</div>
					<div class="author-detail">
						<div class="author-detail-name">
							<span><?php echo empty($data['user_nickname'])?'匿名':$data['user_nickname'];?></span>
						</div>
						<div class="author-distance">
							<img id="map-icon" src="skin/social/imgs/all/note_pt_location.png">
							<span> <?php echo  array_key_exists('distance_to_me',$data)?$data['distance_to_me']:0;?>km</span>
						</div>
					</div>
					<div class="clearboth"></div>
				</div>
			</div>
			<div class="clearboth"></div>
			<?php }?>

			<a href="<?php echo in_array($main_key,array('show_all_comment','show_user_comments','show_user_comment_item'))?'#':'modules.php?app=paper_item&paper_id='.$value['paper_id'];?>">
				<div class="info-content">
					<div class="info_content-wrap">
						<div class="info-img">
							<img src="pictures/<?php echo $value['picture'];?>"/>
						</div>
						<div class="info-text">
							<p><?php echo $value['content'];?></p>
						</div>
					</div>
				</div>
			

				<div class="info-interchange">
					<div class="info-interchange-wrap">
						<div class="left-interchange-record">
							<img src="skin/social/imgs/all/note_pt_feiji.png">
							<span> <?php echo $value['view_count']?$value['view_count']:0;?></span>
						</div>
						<div class="right-interchange-record">
							<img src="skin/social/imgs/all/note_btn_pinglun_unpress.png"/>
							<span> <?php echo $value['comment_count']?$value['comment_count']:0;?> </span>
						</div>
						<div class="clearboth"></div>
					</div>
				</div>
			</a>
			<div class="clearboth"></div>

			<?php if(in_array($main_key,array("show_user_comments", "show_user_comment_item","show_all_comments")) && isset($cur_user) && array_key_exists($cur_user,$data)){?>
			<div class="current-user-comment">
				<div class="current-user-comment-wrap">
					<div class="my-comment-status">
						<span class="author-me-label"> 我的回复：</span>
						<span class="author-me-status">
							<?php echo get_reply_str($data[$cur_user]['comment_status']);?>
						</span>
						<div class="clearboth"></div>
					</div>
					<div class="my-comment-content">
						<p><?php echo $data[$cur_user]['comment_content'];?></p>
					</div>

				</div>
			</div>
			<div class="clearboth"></div>
			<?php }?>

			<?php if(in_array($main_key,array("show_all_comments","show_user_comments","show_user_comment_item"))){?>
			<div class="info-comment">
				<div class="info-comment-wrap">

<!-- 评论的一项 -->
					<?php foreach($data['comments'] as $comment_key => $comment_val){?>
					<div class="comment-item">
						<div class="comment-content">
							<p> <?php echo $comment_val['comment_content'];?></p>
						</div>
						<div class="comment-user-info">
							<div class="left-user">
								<img src="">
								<span> <?php echo $comment_val['commenter']?$comment_val['commenter']:'Name';?> </span>
							</div>
							<div class="right-user">
								<!-- <span> SYSU</span> -->
								<!-- <span> Distance</span> -->
								<span> <?php echo $comment_val['comment_time'];?></span>
							</div>
						</div>
						<div class="clearboth"></div>
						<div class="comment-item-span"></div>
					</div>
					<?php }?>

				</div>
			</div>
			<?php }?>

		</div>
		<?php }?>
	</div>