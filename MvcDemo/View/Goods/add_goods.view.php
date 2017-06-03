<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>添加商品</title>	
	</head>
	<body>
		<form method="post" enctype="multipart/form-data" action="doAdd">
			<label for="gname">商品名称：</label>
			<input type="text" id="gname" name="gname" /> <br/><br/>
			<label for="parent">父类别：</label>
			<select id="cid" name="cid">
			  <option value="0">顶级类别</option>
			  <?php foreach ($data['ctgrList'] as $c): ?>
			  <option value="<?php echo $c['cid']; ?>"><?php echo $c['cname']; ?></option>
			  <?php endforeach; ?>
			</select> <br/><br/>
			<label for="price">价格：</label>
			<input type="text" id="price" name="price" /> <br/><br/>
			<label for="discount">折扣：</label>
			<input type="text" id="discount" name="discount" /> <br/><br/>
			<label for="store_num">库存量：</label>
			<input type="text" id="store_num" name="store_num" /> <br/><br/>
			<label for="pic">&nbsp;图片:</label>
			<input type="file" name="art" id="pic" /><br /><br />
			<input type="submit" />
		</form>
	</body>
</html>
