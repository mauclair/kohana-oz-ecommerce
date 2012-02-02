<?php defined('SYSPATH') or die('No direct script access.');

return array(
	'name' => array(
		'not_empty' => 'Variation name is required',
	),
	'price' => array(
		'not_empty' => 'Please provide a numeric variation price',
		'numeric'   => 'Please provide a numeric variation price',
		'gte'       => 'Variation price must be greater than or equal to :param2',
	),
	'sale_price' => array(
		'numeric' => 'Please provide a numeric variation sale price',
		'gte'     => 'Variation sale price must be greater than or equal to :param2',
		'lt'	  => 'Variation sale price must be less than the original price',
	),
	'quantity' => array(
		'not_empty' => 'Variation quantity must be a digit',
		'digit'     => 'Variation quantity must be a digit',
	),
);
