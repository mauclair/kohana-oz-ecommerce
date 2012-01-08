<?php defined('SYSPATH') or die('No direct script access.');

return array(
	'name' => array(
		'not_empty' => 'Variation name is required',
	),
	'quantity' => array(
		'not_empty' => 'Variation quantity must be a digit',
		'digit'     => 'Variation quantity must be a digit',
	),
);
