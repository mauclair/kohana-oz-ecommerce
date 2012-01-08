<?php defined('SYSPATH') or die('No direct script access.');

return array(
	'name' => array(
		'not_empty' => 'Category name is required',
	),
	'order' => array(
		'not_empty' => 'Order must be a valid digit',
		'digit'     => 'Order must be a valid digit',
	),
);
