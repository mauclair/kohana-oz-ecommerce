<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Order product model
 *
 * @package    openzula/kohana-oz-ecommerce
 * @author     Alex Cartwright <alex@openzula.org>
 * @copyright  Copyright (c) 2011, 2012 OpenZula
 * @license    http://openzula.org/license-bsd-3c BSD 3-Clause License
 */
abstract class Model_Oz_Order_Product extends ORM {

	protected $_belongs_to = array(
		'order' => array(),
	);

	protected $_table_columns = array(
		'id'           => array('type' => 'int'),
		'order_id'     => array('type' => 'int'),
		'product_id'   => array('type' => 'int'),
		'variation_id' => array('type' => 'int'),
		'quantity'     => array('type' => 'int'),
		'price'        => array('type' => 'float'),
	);

	public function rules()
	{
		return array(
			'order_id' => array(
				array('not_empty'),
				array('digit'),
				array('gt', array(':value', 0)),
			),
			'product_id' => array(
				array('not_empty'),
				array('digit'),
				array('gt', array(':value', 0)),
			),
			'variation_id' => array(
				array('digit'),
			),
			'quantity' => array(
				array('not_empty'),
				array('digit'),
				array('gt', array(':value', 0)),
			),
			'price' => array(
				array('not_empty'),
				array('numeric'),
				array('gte', array(':value', 0)),
			),
		);
	}

	/**
	 * Overload save() to stop existing entries being edited
	 *
	 * @param   Validation  $validation
	 * @return  mixed
	 */
	public function save(Validation $validation = NULL)
	{
		if ($this->loaded())
			throw new Kohana_Exception('existing order products can not be modified');

		return parent::save($validation);
	}

	/**
	 * Overload delete() to stop existing entries being deleted
	 *
	 * @return  mixed
	 */
	public function delete()
	{
		if ($this->loaded())
			throw new Kohana_Exception('existing order products can not be deleted');

		return parent::delete();
	}

}
