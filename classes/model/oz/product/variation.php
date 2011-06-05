<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Product variation model
 *
 * @package openzula/kohana-oz-ecommerce
 * @author Alex Cartwright <alex@openzula.org>
 * @copyright Copyright (c) 2011, OpenZula
 * @license http://openzula.org/license-bsd-3c BSD 3-Clause License
 */

class Model_Oz_Product_Variation extends ORM {

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
			'name'       => array(array('not_empty')),
		);
	}

}
