<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>添加新类别</title>	
	</head>
	<body>
		<form method="post" action="doAdd">
			<label for="cname">类别名称：</label>
			<input type="text" id="cname" name="cname" /> <br/><br/>
			<label for="parent">父类别：</label>
			<select id="parent" name="parent_id">
			  <option value="0">顶级类别</option>
			  <?php foreach ($data['ctgrList'] as $c): ?>
			  <option value="<?php echo $c['cid']; ?>"><?php echo $c['cname']; ?></option>
			  <?php endforeach; ?>
			</select> <br/><br/>
			<label for="description">描述：</label>
			<input type="text" id="description" name="description" /> <br/><br/>
			<input type="submit" />
		</form>
	</body>
</html>