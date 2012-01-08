<?php defined('SYSPATH') or die('No direct script access.');

return array(
	'rating' => array(
		'not_empty' => 'Please provide a review rating',
		'range'     => 'Review rating must be between :param2 and :param3 (inclusive)',
	),
	'summary' => array(
		'not_empty' => 'Review summary is required',
	),
	'body' => array(
		'not_empty' => 'Review body is required',
	),
);
