<?php defined('SYSPATH') or die('No direct script access.');

return array(
	'name' => array(
		'not_empty' => 'Product name is required',
	),
	'description' => array(
		'not_empty' => 'Description is required',
	),
	'price' => array(
		'not_empty' => 'Please provide a numeric price',
		'numeric'   => 'Please provide a numeric price',
		'gte'       => 'Price must be greater than or equal to :param2',
	),
	'sale_price' => array(
		'numeric' => 'Please provide a numeric sale price',
		'gte'     => 'Sale price must be greater than or equal to :param2',
		'lt'	  => 'Sale price must be less than the original price',
	),
	'quantity' => array(
		'digit' => 'Quantity must be a digit',
	),
);
