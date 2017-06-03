<?php
return array (
		array (
				'actions' => array (
						'cart/index',
				),
				'filter' => 'Filter\\AuthenticateFilter' 
		),
		array (
				'actions' => array(
						'cart/add'
				),
				'filter' => 'Filter\\AuthenticateAjaxFilter'
		),
		array(
				'actions' => array(
						'goods/add',
						'category/getList',
						'category/add',
						'category/doAdd',
						'category/delete'
				),
				'filter' => 'Filter\\RoleAuthFilter'
		)
);