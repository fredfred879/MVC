<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title></title>
		<link rel="stylesheet" type="text/css" href="../../public/css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="../../public/css/list_goods.css">
		<link rel="stylesheet" type="text/css" href="../../public/css/from.css">
		<style type="text/css">
				#cart-list {
				width:260px;
				min-height: 90px;
				background: #ffffff;
				border:1px solid #cccccc;
				position: absolute;
				top:45px;
				right:0px;
				z-index: 3;
				color:#393939;
				font-size: 12px;
 				display: none; 
			}
			#cart-list img {
				width:30px;
				height: 30px;
			}
			#cart-list table {
				width:100%;
			}
			#cart-list table tr {
				height:30px;
			}
			#cart-list a {
				
				color:#df3536;
				font-weight: bolder;
				text-decoration: none;
			}
			#cart-list a:hover {
				color:#ff5758;
			}
			.header ul li .write:hover {
				background: #000000;
			}
			.main .header .header-left li .btn {
				border:1px solid #000000;
			}
		</style>
	</head>
	<body>
		<div class="main">
			<div class="header">
				<div class="header-left">LOGO</div>
				<ul>
					<li style="display: <?php if(!empty($_SESSION['curr_user'])) echo 'none'; else echo 'block';?>;">
						<a href="javascript:void(0);" style="color: #FFFFFF;padding: 0px;border:0.1px solid #545555;" class="btn" data-toggle="modal" data-target="#myModal">登录</a>
					</li>
					<li style="display: <?php if(!empty($_SESSION['curr_user'])) echo 'block'; else echo 'none';?>;">
						<a href="javascript:void(0);" style="color: #FFFFFF;padding: 0px;"><?php echo $_SESSION['curr_user']['uname'] ?></a>
					</li>
					<li style="display: <?php if(!empty($_SESSION['curr_user'])) echo 'block'; else echo 'none';?>;">
					<a id = "logout" href="javascript:void(0);" style="color: #FFFFFF;">退出</a>
					</li>
					<li><a class="addGoods" href="add" style="color: #FFFFFF;">添加商品</a></li>
					<li>
						<a class="headerCart" href="../cart/index" style="color: #FFFFFF;">购物车</a>
					</li>
					<li>
						<a class="write" style="color: #FFFFFF;"></a>
					</li>
				</ul>
				<div id="cart-list">
				<table></table>
				<div style="text-align: center; margin-top: 8px;"><a href="../cart/index">去购物车结算</a></div>
				</div>
			</div>
			<div class = "category">
			<?php if(empty($data['c'])): ?>
				<span>当前类别：所有分类</span>
				<ul class = "category_ul">
				<?php foreach($data['ctgr'] as $k): ?>
				<li><a href = "getList?cid=<?php echo $k['cid']; ?>&path=<?php echo $k['path']; ?>&parent=<?php echo $k['cid']; ?>" class = "category_a"><?php echo $k['cname']; ?></a></li>
				<?php endforeach; ?>
			<?php else:?>
				<span>当前类别：<?php echo $data['ctgr'][0]['cname']; ?></span>
				<ul class = "category_ul">
				<?php foreach($data['ctgr'] as $k): ?>
				<?php if($k['parent_id'] != 0):?>
				<li><a href = "getList?cid=<?php echo $k['cid']; ?>&path=<?php echo $k['path']; ?>&parent=<?php echo $k['cid']; ?>" class = "category_a"><?php echo $k['cname']; ?></a></li>
				<?php endif;?>
				<?php endforeach; ?>
			<?php endif;?>
			
			</ul>
			</div>
			<div class="content">
				<div class="content-list">
					<?php foreach($data['goodsList'] as $k): ?>
						<div class="good">
							
							<img src="../../<?=$k["art_path"] ?>" />
							
							<ul class="a">
								<li><p class="gname"><?=$k["gname"] ?></p></li>
								<li><span style="text-decoration:line-through;">原价：$<?=$k["price"] ?></span><p>现价：$<span class="price"><?=$k["price"]*$k["discount"] ?></span></p></li>
								<li><p>数量：<?=$k["store_num"] ?></p></li>
								<li><a href="javascript:void(0);" class="btn_cart">加入购物车</a></li>
							</ul>
							<input type="hidden" class="gid" value="<?=$k["gid"] ?>" />
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<!-- 模态框（Modal） -->
		<div id="closeAll">
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
			         	
			            <form method="post" id="login_form">
						<label for="username">username:</label>
						<input type="text" name="username" id="username" placeholder="username" /><br />
						
						<div id="uname_info" class="info" style="visibility: hidden;">请输入合法的用户名或邮箱</div>
						<label for="password">password:</label>
						<input type="text" name="password" id="password" placeholder="password" /><br />
						<div id="pwd_info" class="info" style="visibility: hidden;">密码长度必须大于等于6位</div>
						
						<label for="vcode">&nbsp;vcode:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input type="text" name="vcode" id="vcode" placeholder="请您输入验证码" />
						<img src="../User/getVCodeImage" id="img_vcode" style="height: 23px;width: 70px; position:absolute;+margin-top:1px" /><br />
						
						<div id="sub">
							<input type="button" class="sub1" value="登录" />
						</div>
						<div id="vcode_info" class="info" style="visibility: hidden;></div>
						</form>
			         </div>
			      </div><!-- /.modal-content -->
				</div><!-- /.modal -->
			</div>
		</div>
		<script src="../../public/js/jquery-1.11.0.js"></script>
		<script type="text/javascript">
		document.getElementById('img_vcode').onclick = function(){
			this.src = '../User/getVCodeImage';
		}
		</script>
		<script src="../../public/js/jquery-1.11.0.js"></script>
    	<script src="../../public/js/from.js"></script>
    	<script src="../../public/js/list_goods.js"></script>
    	<script src="../../public/js/cart.js"></script>
   	 	<script src="../../public/js/bootstrap.js"></script>
	</body>
</html>
