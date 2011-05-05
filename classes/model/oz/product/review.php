<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Product review model
 *
 * @package openzula/kohana-oz-ecommerce
 * @author Alex Cartwright <alex@openzula.org>
 * @copyright (C) 2011 OpenZula
 */

class Model_Oz_Product_Review extends ORM {

	protected $_belongs_to = array(
		'product' => array()
	);

	public function rules()
	{
		return array(
			'product_id' => array(
				array('not_empty'),
				array('digit'),
				array('gt', array(':value', 0))
			),
			'rating'     => array(
				array('not_empty'),
				array('range', array(':value', 0, 10))
			),
			'body'       => array(
				array('not_empty')
			),
		);
	}

}
