<?php
    $playSong = empty($_GET['song']) ? "" : $_GET['song'];
    
    if(empty($_GET['list'])){
        $playList = json_decode(file_get_contents('player.json'), true);
    } else {
        $playList[0] = $_GET['list'];
    }
    
    $site = '//' . $_SERVER['SERVER_NAME'];
    $path = '/';
    $uri = explode("/", $_SERVER["REQUEST_URI"]);
    foreach ($uri as $v) {
        if(!empty($v) && count(explode("?", $v)) == 1){
            $path .= $v . '/';
        }
    }
    $url = $site . $path . 'play';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
	<link rel="icon" href="/favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon"/>
    <title>Our God Madoka - Kaname Madoka 鹿目まどか 鹿目圆香 魔法少女まどか☆マギカ</title>
    <script src="//cdn.staticfile.org/jquery/3.2.1/jquery.min.js"></script>
	
	<!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="play/materialize/css/materialize.min.css"  media="screen,projection"/>
	
    <link rel="stylesheet" href="<?php echo $url;?>/player.css?2"/>
	
	<script type="text/javascript" src="play/materialize/js/materialize.min.js"></script>
	
	<!--Import js template-->
	<script type="text/javascript" src="play/template-web.js"></script>
	<script id="message-template" type="text/html">
	{{each comment}}
	<div class="card-content card_message_content" >
	<div class="row">
	<div class="col s6 left-align message_name">{{$value.name}}</div>
	<div class="col s6 right-align message_email">{{$value.email}}</div>
	<div class="col s12 message_content">{{$value.content}}</div>
	</div>
	</div>
	{{/each}}
	</script>
</head>
<body class="load">
<div class="wrapper">
    <div id="bg" class="bg" style="background-image: url('<?php echo $url;?>/static/song.png');"></div>
    <div id="play">
        <div class="play-board">
            <div class="header cover title">
                <img class="disk-cover" src="<?php echo $url;?>/static/song_first.png"/>
                
                <div id="tools">
                    <div class="share"></div>
                    <div class="down"></div>
                </div>
                
                <div class="title">
				
                    <div class="song" id="songName"></div>
                    <div class="artist lyric" id="artist"></div>
                </div>
            </div>
            
            <div class="footer">
                <audio id="player"></audio>
                <div class="process" id="process">
                    <span id="currentTime">00:00</span>
                    <div class="process-bar">
                        <div class="rdy"></div>
                        <div class="cur">
                            <span id="processBtn" class="process-btn c-btn"></span>
                        </div>
                    </div>
                    <span id="totalTime">00:00</span>
                </div>
                
                <div class="control" id="controls">
                    <span class="c-btn loop-btn" onclick="ctx.loop()"></span>
                    <span class="pre c-btn" onclick="ctx.prev()"></span>
                    <span class="play c-btn" onclick="ctx.play()"></span>
                    <span class="pause c-btn" onclick="ctx.pause()" style="display: none"></span>
                    <span class="next c-btn" onclick="ctx.next()"></span>
                    <span class="c-btn list-btn" onclick="ctx.showPlayList()"></span>
                </div>
            </div>
        </div>
        <div class="play-list" id="playList">
            <div class="list-title">PlayList [<span id="playListCount">0</span>]</div>
            <ul class="list-content" id="listContent">
            </ul>
        </div>
    </div>
</div>
<footer class="page-footer" id="footer">
    <div class="footer-copyright">
        <div class="container">
            <a class="valign-wrapper grey-text text-lighten-5 right" href="#" onclick="show_hide()">Click here view Message Board</a>
        </div>
    </div>
</footer>
<!--留言板测试-->
<div class="row" id="message">
    <div class="col s12 m12 l4 offset-l8">
        <div class="card" id="card_message">
		
			<div id="liuyan">
			
			</div>
			
			<div class="card-content card_message_content">
				<div class="row">
				<a class="waves-effect waves-red btn-flat card_message_button page_num" id="page_left_all">|<</a>
				<a class="waves-effect waves-red btn-flat card_message_button page_num" id="page_left"><</a>
				<a class="waves-effect waves-red btn-flat card_message_button page_num" id="page_1">1</a>
				<a class="waves-effect waves-red btn-flat card_message_button page_num" id="page_2">2</a>
				<a class="waves-effect waves-red btn-flat card_message_button page_num" id="page_3">3</a>
				<a class="waves-effect waves-red btn-flat card_message_button page_num" id="page_4">4</a>
				<a class="waves-effect waves-red btn-flat card_message_button page_num" id="page_right">></a>
				<a class="waves-effect waves-red btn-flat card_message_button page_num" id="page_right_all">>|</a>
				</div>
            </div>
			
			<div class="card-action">
				<span class="card-title activator grey-text text-darken-4" id="reply_action">发表留言<i class="material-icons right">more_vert</i></span>
			</div>
			
			<div class="card-reveal">
				<span class="card-title grey-text text-darken-4" id="reply_reveal">发表留言<i class="material-icons right">close</i></span>
					<div class="row">
						<form>
							<div class="input-field col s6">
								<input id="name" type="text" class="validate" value="Your name">
								<label for="name" class="active">姓名</label>
							</div>
							<div class="input-field col s6">
								<input id="email" type="email" class="validate" value="Youremail@mail.com">
								<label for="email" class="active">邮箱</label>
							</div>
							<div class="input-field col s12">
								<textarea id="content" class="materialize-textarea"></textarea>
								<label for="content">留言</label>
							</div>
							
							 <a class="btn-floating btn-small waves-effect waves-light red right" href="#" onclick="huifu()"><i class="material-icons">add</i></a>
						</form>
					</div>	
			</div>
			
        </div>
    </div>
</div>      

<div class="loading">
    <i></i>
    <i></i>
    <i></i>
    <i></i>
    <i></i>
</div>

<script>
	//footer高度调整
	$(".wrapper").height($(window).height()-50);
	//留言框函数
	function show_hide()
	{
		if($("#message").is(":hidden"))
		{
			$("#message").fadeIn("fast");
		}
		else
		{
			$("#message").fadeOut("fast");
		}
	}
	//留言框单击其他位置隐藏
	$('body').click(function(e) {
		var target = $(e.target);
		// 如果#overlay或者#btn下面还有子元素，可使用
		// !target.is('#btn *') && !target.is('#overlay *')
		if(!target.is('#footer *') && !target.is('#card_message *')) {
			if ( $('#message').is(':visible') ) {  
				$("#message").fadeOut("fast");                                                   
			}		
		}
	});
	
	//手机打开输入法时留言位置调整
	var oHeight = $(window).height(); //屏幕当前的高度
	var timer = 0;
	$(window).resize(function(){
		clearTimeout(timer);
		timer = setTimeout(function() {
			if($(window).height() < oHeight)
			{
				$("#message").css("top","5px");
			}
			else
			{
				$("#message").css("top","");
			} 
		}, 200);
    });
	
	//播放列表初始化
    var myPlay = "<?php echo $url;?>";
    var myList = "<?php echo $playList[0];?>";
    var mySong = "<?php echo rawurlencode($playSong);?>";
	var BackgroundCount = 0;
	
	//留言板选页函数
	var all_page = 0;
	var now_page = 0;
	function change_page(num){
		$.post("comment.php?method=get",{page:num},function(result)
		{
			if(result.msg==200)
			{
				all_page=result.data.count;
				now_page=num;
				$("#page_left_all,#page_left,#page_right,#page_right_all").removeClass("disable");
				$("#page_1,#page_2,#page_3,#page_4").removeClass("red lighten-3");
				$("#liuyan").html(template('message-template', result.data));
				if(num==1)
				{
					$("#page_left_all,#page_left").addClass("disabled");
				}
				if(num>=result.data.count)
				{
					$("#page_right,#page_right_all").addClass("disabled");
				}
				
				if (all_page<4)
				{
					var start_page=1;
					var end_page=all_page;
					$("#page_1,#page_2,#page_3,#page_4").hide();
					for(i=1;i<=end_page;i++)
					{
						$("#page_"+i).text(i);
						$("#page_"+i).show();
					}
					$("#page_"+(now_page-start_page+1)).addClass("red lighten-3");
				}
				else
				{
					var start_page=num-1;
					var end_page=num+2;
					if(start_page<1)
					{
						start_page=1;
						end_page=4;
					}
					else if(end_page>all_page)
					{
						start_page=all_page-3;
						end_page=all_page;
					}
					for(i=1;i<=4;i++)
					{
						
						$("#page_"+i).text(start_page+i-1);
					}
					$("#page_"+(now_page-start_page+1)).addClass("red lighten-3");
				}
			}
			else
			{
				alert('留言板加载失败');
			}
		},"json");
	};
	
		
	//留言板初始化
	change_page(1);
	//留言板页数选择函数
	$(".page_num").click(function(){
		var attribute = $(this).text();
		if(attribute=="|<")
		{
			change_page(1);
		}
		else if(attribute=="<")
		{
			change_page(now_page-1);
		}
		else if(attribute==">")
		{
			change_page(now_page+1);
		}
		else if(attribute==">|")
		{
			change_page(all_page);
		}
		else
		{
			change_page(parseInt(attribute));
		}
	})
	
	//回复框高度调整
	$("#reply_action").click(function(){
		if($(".card").height()<400)
		{
			$(".card").css("height","400px")
		}
	})
	$("#reply_reveal").click(function(){
		$(".card").css("height","")
	})
	
	//回复函数
	function huifu(){
		var error=0;
		if($("#name").val()=="")
		{
			Materialize.toast('姓名不能为空', 2000);
			error=1;
		}
		
		if($("#email").val()=="")
		{
			Materialize.toast('邮箱不能为空', 2000);
			error=1;
		}
		
		if($("#content").val()=="")
		{
			Materialize.toast('留言不能为空', 2000);
			error=1;
		}
		
		if($("#email").hasClass('invalid'))
		{
			Materialize.toast('邮箱格式不符合要求', 2000);
			error=1;
		}
		
		if(error==0)
		{
			$.post("comment.php?method=post",{name:$("#name").val(),email:$("#email").val(),content:$("#content").val()},function(result)
			{
				if(result.msg==200)
				{
					change_page(1);
					Materialize.toast('留言成功', 1000);
					$("#name").val("Your name");
					$("#email").val("Youremail@mail.com");
					$("#content").val("");
					Materialize.updateTextFields();
				}
			},"json");
			Materialize.toast('正在上传留言。。。。。。', 1000);
			$("#reply_reveal").click();
		}

	}
	
	
</script>
<script src="<?php echo $url;?>/player.js?2"></script>

</body>
</html>