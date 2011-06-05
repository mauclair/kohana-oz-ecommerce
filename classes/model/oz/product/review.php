<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Product review model
 *
 * @package openzula/kohana-oz-ecommerce
 * @author Alex Cartwright <alex@openzula.org>
 * @copyright Copyright (c) 2011, OpenZula
 * @license http://openzula.org/license-bsd-3c BSD 3-Clause License
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
			'summary'    => array(
				array('not_empty')
			),
			'body'       => array(
				array('not_empty')
			),
		);
	}

	/**
	 * Override the save() method to provide some default value for columns
	 *
	 * @return mixed
	 */
	public function save(Validation $validation = NULL)
	{
		if ( ! $this->loaded())
		{
			$this->date = Db::expr('UTC_TIMESTAMP()');
		}

		return parent::save($validation);
	}

}
