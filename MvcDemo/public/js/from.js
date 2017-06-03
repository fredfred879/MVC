$(function(){
	$(".btn").click(function(){
		$(".modal-backdrop").css('visibility','visible');
		$("#closeAll").show();
		$('#username').val('');
		$('#password').val('');
		$('#vcode').val('');
	});
	
	var isValid = true;
	$("#username").focus(function(){
		$(this).keyup(function(){
			var user = $('#username').val();
			var regex = /^\w+@?\w*\.*\w+$/;
			if(regex.test(user)){
				var isValid = true;
				$('#uname_info').css('visibility','hidden');
			}else{
				isValid = false;
				$('#uname_info').css('visibility','visible').text('请输入合法的用户名或邮箱');
			}
		});
	});
	$("#password").focus(function(){
		$(this).keyup(function(){
			var pwd = $('#password').val();
			if(pwd.length >= 6){
				var isValid = true;
				$('#pwd_info').css('visibility','hidden');
			}else{
				isValid = false;
				$('#pwd_info').css('visibility','visible');
			}
		});
	});
	
	$(".sub1").click(function(e) {
		// e.preventDefault();
		if (isValid) {
			ajaxLogin();
		}
	});
	
	
	
	
	
	function ajaxLogin() {
		var vcode = document.getElementById('img_vcode');
		var xhr = new XMLHttpRequest();
		xhr.open('POST','../user/cleck',true);
		xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		// 打包表单字段，得到请求参数（n1=v1&n2=v2）
		var params = $('#login_form').serialize();
		xhr.send(params);
		xhr.onreadystatechange = function() {
			if (xhr.readyState == 4) {
				if (xhr.status == 200) {
					var resp = JSON.parse(xhr.responseText);
					$('#uname_info').css('visibility', 'hidden');
					$('#vcode_info').text("");
					switch(resp.status) {
					case 1:
						$(".modal-backdrop").css('visibility','hidden');
						$("#closeAll").hide();
						var li = $(".header ul li");
						li.eq(0).hide();
						li.eq(1).find('a').html(resp.data[0].uname);
						li.eq(1).show();
						li.eq(2).show();
						break;
					case 0:
						vcode.src = '../User/getVCodeImage';
						$('#uname_info').css('visibility', 'visible').text(resp.msg);
						break;
					case -1:
						vcode.src = '../User/getVCodeImage';
						$('#vcode_info').css('visibility', 'visible').text(resp.msg);
						break;
					}
				}
			}
		}
	}
	
	// 退出按钮单击
	$("#logout").click(function() {
		$.ajax({
			type: 'GET',
			url: '../user/logout',
			success: function(resp) {
				if(resp.status == 1) {
					var li = $(".header ul li");
					li.eq(0).show();
					li.eq(1).find('a').html('');
					li.eq(1).hide();
					li.eq(2).hide();
				}
			},
			error: function(err) {
				//
			}
		});
	});
	
});