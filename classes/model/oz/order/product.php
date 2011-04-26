<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Order product model
 *
 * @package openzula/kohana-oz-ecommerce
 * @author Alex Cartwright <alex@openzula.org>
 * @copyright (C) 2011 OpenZula
 */

class Model_Oz_Order_Product extends ORM {

	protected $_belongs_to = array(
		'order' => array()
	);

	public function rules()
	{
		return array(
			'order_id'   => array(array('not_empty'), array('digit')),
			'product_id' => array(array('not_empty'), array('digit')),
			'quantity'   => array(array('not_empty'), array('digit')),
			'price'      => array(array('not_empty'), array('numeric')),
		);
	}

}
