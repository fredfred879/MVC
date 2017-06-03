<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- 移动先行 -->
	<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1.0,user-scalable=no">
	<title>login</title>

	<!--Bootstrap-->
	<link rel="stylesheet" type="text/css" href="../../public/css/from.css">
	<link rel="stylesheet" type="text/css" href="../../public/css/bootstrap.css">
	<!--一下两个插件是在IE8支持HTML5元素和媒体查询的，如果不用可移除-->
	<!--[if it IE9]>

	<script type="text/javascript" src="oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https:// oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>

	<![end if]-->
	<!--<style>
		#user {
			border: 1px solid red;
		}
	</style>-->
	<style type="text/css">
		.aa {
			padding-left: 175px;
		}
		.bb {
			text-align: center;
		}
		#sub {
				height: 30px;
				width: 300px;
			}
			.sub1 {
				border: 1px solid #31B0D5;
				background: #31B0D5;
				color: #FFFFFF;
				height: 30px;
				width: 255px;
			}
			.sub1:hover {
				border: 1px solid #42C1E6;
				background: #42C1E6;
				color: #FFFFFF;
			}
	</style>
</head>
<body>
	<!--<h2>免费注册</h2>-->
<!-- 按钮触发模态框 -->
<!--<button class="btn btn-primary btn-lg" data-toggle="modal" 
   data-target="#myModal">
   免费注册
</button>-->
<button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
开始演示模态框
</button>
<!-- 模态框（Modal） -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title bb" id="myModalLabel">
               用户登录
            </h4>
         </div>
         <div class="modal-body aa">
            <form method="post" action="cleck?a=<?php echo $data['a'] ?>">
			<label for="username">username:</label>
			<input type="text" name="username" id="username" placeholder="username" /><br /><br />
			<label for="password">password:</label>
			<input type="text" name="password" id="password" placeholder="password" /><br /><br />
			<label for="vcode">&nbsp;vcode:</label>
			<input type="text" name="vcode" id="vcode" placeholder="请输入验证码" />
			<img src="getVCodeImage" id="img_vcode" style="height: 23px;width: 70px; position:absolute;+margin-top:1px" /><br />
			<div id="sub">
				<input type="submit" class="sub1" value="登录" />
			</div>
			</form><br />
			<a href="shopping.php"><input type="submit" class="sub1" value="关闭" /></a>
         </div>
      </div><!-- /.modal-content -->
</div><!-- /.modal -->
</div>


	<!-- <h1>Hello,world</h1>
	<div>
			<p class="lead">而天然电影天堂热阿斯顿飞</p>
	</div>	
	<abbr title="实现BS的缩略词">实现BS的缩略词</abbr>
	<abbr title="html书籍" class="initialism">html书籍</abbr>

	<blockquote class="pull-right">
		<p>不愤不启，不悱不发。举一隅，不以三隅反，则吾不复也。</p>
		<small>出自<cite title="论语·述尔">论语</cite></small>
	</blockquote> -->
</body>
	<!-- 如果要使用Bootstrap的JS插件，则必须引入jQuery -->
    
    
    <script src="../../public/js/jquery-1.11.0.js"></script>

    <!-- Bootstrap的JS插件 -->
	<script type="text/javascript">
		document.getElementById('img_vcode').onclick = function(){
			this.src = 'getVCodeImage';
		}
	</script>
    <script src="../../public/js/from.js"></script>
    <script src="../../public/js/bootstrap.js"></script>
</html>
