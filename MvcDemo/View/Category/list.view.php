<?php
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>商品类别管理</title>

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
    <?php if (isset($data['ctgrList']) && $data['ctgrList'] != null):?>
	<table class="table" cellpadding="0" cellspacing="0">
		<tr>
			<th>序号</th>
			<th>类别名称</th>
			<th>父类别</th>
			<th>描述</th>
		</tr>
		<?php foreach ($data['ctgrList'] as $row): ?>
		<tr>
			<td><?php echo $row['cid'];?></td>
			<td><?php echo $row['cname'];?></td>
			<td><?php echo $row['path'];?></td>
			<td><?php echo $row['description'];?></td>
			<td>
			  <a href="update.php?op=1&cid=<?php echo $row['id'];?>">修改</a> 
			  <a href="delete?path=<?php echo $row['path']; ?>">删除</a>
			</td>
		</tr>
		<?php endforeach; ?>
		</table>
	    <?php endif;?>	
	<div>
		<a href="add">添加新类别</a>
	</div>
</body>
</html>
