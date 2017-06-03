/**
 * 
 */

$(function(){
	$('.btn_cart').click(function(e){
		var parent = $(e.target).parent().parent();
		var gid = parent.parent().find('.gid').val();
		var gname = $.trim(parent.find('.gname').text());
		var price = $.trim(parent.find('.price').text());
		var artPath = parent.parent().find('img').prop('src');
		$.ajax({
			type:"POST",
			url:"../cart/add",
			data:{
				gid:gid,
				gname:gname,
				price:price,
				art_path:artPath
			},
			async:true,
			
			success:function(resp){
				if(resp.status == -2){
					alert(resp.msg);
				}else if(resp.status == 1){
					alert(resp.msg);
				}else{
					alert(resp.msg);
				}
			}
		});
	});
	
	//鼠标滑过购物车显示信息

	$('.addGoods').on('mouseover', function() {
		$('#cart-list').hide();
	});
	$('.write').on('mouseover', function() {
		$('#cart-list').hide();
	});
	$('.headerCart').on('mouseover', function() {
		$('#cart-list').show();
		loadCartList();
	});
	$('#cart-list').on('mouseleave', function() {
		$(this).hide();
	});
	
	function loadCartList() {
		$.ajax({
			type:"get",
			url:"../cart/cartList",
			async:true,
			success:function(resp){
				if(resp.status == 1){
					var cart = resp.data;
					renderCartList(cart);
				}else{
					$('#cart-list').find('table').remove();
					$('#cart-list').find('p').remove();
					var p = $('<p>').text('购物车空空的哦~').prop('style','text-align: center;');
					$('#cart-list').prepend(p);
				}
				
			}
		});
	}
	function renderCartList(cart) {
		$('#cart-list').find('table').remove();
		var table = $('<table>');
		for(var i = 0; i < cart.length; i++) {
			var tr = $('<tr>');
			var tdArt = $('<td>').append($('<img>').prop('src',cart[i].art_path));
			
			var gname = cart[i].gname.length > 6 ? cart[i].gname.substring(0,6) + '...' : cart[i].gname;
			var tdName = $('<td>').text(gname);
			var tdPrice = $('<td>').text('¥' + cart[i].price);
			var tdRemove = $('<td>').append($('<input>').prop('type','hidden').val(cart[i].gid)).append($('<a>').text('删除').prop('href','javascript:void(0);').click(removeCartItem));
			
			tr.append(tdArt);
			tr.append(tdName);
			tr.append(tdPrice);
			tr.append(tdRemove);
			table.append(tr);
		}
		$('#cart-list').prepend(table);
	}
	function removeCartItem(e) {
		var gid = $(e.target).parent().find(':hidden').val();
		$.ajax({
			type:"get",
			url:"../cart/delete",
			data: {gid:gid},
			async:true,
			success: function(resp) {
				if (resp.status == 1) {
					$(e.target).parents('tr').remove();
				}
			}
		});
	}
	//减数量
//	$('.decr').click(function(e){
//		var gid = $(e.target).parent().find('.gid').val();
//		var uid = $(e.target).parent().find('.uid').val();
//		var num = $.trim($(e.target).parent().find('.num').text());
//		
//		$.ajax({
//			type:"POST",
//			url:"../cart/decr",
//			data:{
//				gid:gid,
//				uid:uid,
//				num:num
//			},
//			async:true,
//			
//			success:function(resp){
//				num = num - 1;
//				switch(resp.status) {
//					case 1:
//					if(num > 0){
//						$(e.target).parent().find('.num').text(num);
//					}else{
//						$(e.target).parent().find('.num').text(0);
//					}
//					break;
//				}
//			}
//		});
//	});
//	//加数量
//	$('.incr').click(function(e){
//		var gid = $(e.target).parent().find('.gid').val();
//		var uid = $(e.target).parent().find('.uid').val();
//		var num = $.trim($(e.target).parent().find('.num').text());
//		
//		$.ajax({
//			type:"POST",
//			url:"../cart/incr",
//			data:{
//				gid:gid,
//				uid:uid
//			},
//			async:true,
//			
//			success:function(resp){
//				++num;
//				switch(resp.status) {
//					case 1:
//					$(e.target).parent().find('.num').text(num);
//					break;
//				}
//			}
//		});
//	});
	
	//未登录
	$('.btn_cart').click(function(){
		$.ajax({
			type:"get",
			url:"../cart/index",
			async:true,
			success:function(resp){
				if(resp.status == -2){
					alert('未登录');
				}
			}
		});
	});
	
	
	// 购物车信息页面，更改数量按钮
	$('.decr').click(updateNum);
	$('.incr').click(updateNum);
	
	function updateNum(e) {
		var op = 0;
		if ($(e.target).prop('class') == 'incr') {
			op = 1;
		}
		var numSpan = $(e.target).parent().find('span'); 
		var num = parseInt(numSpan.text());
		if (op == 0 && num == 1) {
			op = -1;
		}
		
		var gid = $(e.target).parent().find('.gid').val();
		var uid = $(e.target).parent().find('.uid').val();
		$.ajax({
			type:"POST",
			url:"updateNum",
			async:true,
			data: {
				gid: gid,
				uid: uid,
				op: op
			},
			success: function(resp) {
				if (resp.status == 1) {
					if (op == 0) {
						num--;
					} else if (op == 1) {
						num++;
					} else {
						// 删除
						$(e.target).parents('tr').remove();
					}
					numSpan.text(num);
				}
			}
		});
	}
});