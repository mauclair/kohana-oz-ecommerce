<?php defined('SYSPATH') or die('No direct script access.');

return array(
	'email' => array(
		'not_empty' => 'Please provide a valid email address',
		'email'     => 'Please provide a valid email address',
	),
	'billing_name' => array(
		'not_empty' => 'Billing contact name is required',
		'full_name' => 'Billing contact name must include your surname',
	),
	'billing_telephone' => array(
		'not_empty' => 'Billing telephone number is required',
		'phone'     => 'Billing telephone value must be valid',
	),
	'billing_addr1' => array(
		'not_empty' => 'Billing address 1 is required',
	),
	'billing_addr3' => array(
		'not_empty' => 'Billing address 2 is required',
	),
	'billing_postal_code' => array(
		'not_empty'      => 'Billing postal code is required',
		'postal_code_uk' => 'Billing postal code must be a valid UK postcode',
	),
	'shipping_name' => array(
		'not_empty' => 'Shipping contact name is required',
		'full_name' => 'Shipping contact name must include your surname',
	),
	'shipping_telephone' => array(
		'not_empty' => 'Shipping telephone number is required',
		'phone'     => 'Shipping telephone value must be valid',
	),
	'shipping_addr1' => array(
		'not_empty' => 'Shipping address 1 is required',
	),
	'shipping_addr2' => array(
		'not_empty' => 'Shipping address 2 is required',
	),
	'shipping_postal_code' => array(
		'not_empty'      => 'Shipping postal code is required',
		'postal_code_uk' => 'Shipping postal code must be a valid UK postcode',
	),
);
