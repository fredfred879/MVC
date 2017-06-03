<?php
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>购物车管理</title>

<style type="text/css">
body {
	margin: 0px;  
	font-family: "微软雅黑";
}

.table {
	border: 1px solid #cccccc;
	border-collapse: collapse;
	width: 100%;
}

.table tr {
	height: 35px;
}

.table tr th, .table tr td {
	border: 1px solid #cccccc;
}

.table tr td {
	text-align: center;
}
</style>
</head>
<body>
    <?php if (isset($data['cart']) && $data['cart'] != null):?>
	<table class="table" cellpadding="0" cellspacing="0">
		<tr>
			<th>商品图片</th>
			<th>商品名称</th>
			<th>价格</th>
			<th>数量</th>
			<th>操作</th>
		</tr>
		<?php foreach ($data['cart'] as $row): ?>
		<tr>
			<td><img style="width:35px;height:35px;" src="<?=$row['art_path'] ?>" /></td>
			<td><?php echo $row['gname'];?></td>
			<td><?php echo $row['price'];?></td>
			<td>
				<input class="uid" type="hidden" value="<?php echo $row['uid'];?>" />
				<input class="gid" type="hidden" value="<?php echo $row['gid'];?>" />
				<button class="decr">-</button>
				<span class="num"><?php echo $row['num'];?></span>
				<button class="incr">+</button>
			</td>
			<td>
			  <a href="update.php?op=1&cid=<?php echo $row['id'];?>">修改</a> 
			  <a href="delete?path=<?php echo $row['path']; ?>">删除</a>
			</td>
		</tr>
		<?php endforeach; ?>
		</table>
	    <?php endif;?>	
	<div>
		<a href="add">结算</a>
	</div>
	<script src="../../public/js/jquery-1.11.0.js"></script>
	<script src="../../public/js/cart.js"></script>
</body>
</html>
