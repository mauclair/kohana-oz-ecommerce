<?php defined('SYSPATH') or die('No direct script access.');

return array(
	'code' => array(
		'max_length'     => 'Code must be no longer than :param2 characters long',
		'code_available' => 'The provided code has already been taken',
	),
	'start_date' => array(
		'date' => 'Start date must be a valid date',
	),
	'end_date' => array(
		'date' => 'End date must be a valid date',
	),
	'percentage' => array(
		'range' => 'Percentage must be between 0 and 100',
	)
);
