<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Orders model
 *
 * @package    openzula/kohana-oz-ecommerce
 * @author     Alex Cartwright <alex@openzula.org>
 * @copyright  Copyright (c) 2011, 2012 OpenZula
 * @license    http://openzula.org/license-bsd-3c BSD 3-Clause License
 */
abstract class Model_Oz_Order extends ORM {

	protected $_has_many = array(
		'products' => array(
			'model' => 'order_product',
		),
	);

	protected $_table_columns = array(
		'id'                   => array('type' => 'int'),
		'user_id'              => array('type' => 'int'),
		'date'                 => array('type' => 'date'),
		'status'               => array('type' => 'string'),
		'payment_method'       => array('type' => 'string'),
		'shipping_price'       => array('type' => 'float'),
		'vat_rate'             => array('type' => 'float'),
		'discount'             => array('type' => 'float'),
		'email'                => array('type' => 'string'),
		'billing_name'         => array('type' => 'string'),
		'billing_telephone'    => array('type' => 'string'),
		'billing_addr1'        => array('type' => 'string'),
		'billing_addr2'        => array('type' => 'string'),
		'billing_addr3'        => array('type' => 'string'),
		'billing_postal_code'  => array('type' => 'string'),
		'billing_country'      => array('type' => 'string'),
		'shipping_name'        => array('type' => 'string'),
		'shipping_telephone'   => array('type' => 'string'),
		'shipping_addr1'       => array('type' => 'string'),
		'shipping_addr2'       => array('type' => 'string'),
		'shipping_addr3'       => array('type' => 'string'),
		'shipping_postal_code' => array('type' => 'string'),
		'shipping_country'     => array('type' => 'string'),
		'notes'                => array('type' => 'string'),
	);

	public function rules()
	{
		return array(
			'shipping_price' => array(
				array('numeric'),
				array('gte', array(':value', 0)),
			),
			'vat_rate' => array(
				array('numeric'),
				array('gte', array(':value', 0)),
			),
			'email' => array(
				array('not_empty'),
				array('email'),
			),
			'discount' => array(
				array('numeric'),
				array('gte', array(':value', 0)),
			),
			'billing_name' => array(
				array('not_empty'),
				array(array($this, 'full_name')),
			),
			'billing_telephone' => array(
				array('not_empty'),
				array('phone'),
			),
			'billing_addr1' => array(
				array('not_empty'),
			),
			'billing_addr3' => array(
				array('not_empty'),
			),
			'billing_postal_code' => array(
				array('not_empty'),
			),
			'billing_country' => array(
				array('not_empty'),
				array('alpha'),
				array('exact_length', array(':value', 2)),
			),
			'shipping_name' => array(
				array('not_empty'),
				array(array($this, 'full_name')),
			),
			'shipping_telephone' => array(
				array('not_empty'),
				array('phone'),
			),
			'shipping_addr1' => array(
				array('not_empty'),
			),
			'shipping_addr3' => array(
				array('not_empty'),
			),
			'shipping_postal_code' => array(
				array('not_empty'),
			),
			'shipping_country' => array(
				array('not_empty'),
				array('alpha'),
				array('exact_length', array(':value', 2)),
			),
		);
	}

	public function filters()
	{
		return array(
			'billing_name'         => array(array('trim')),
			'billing_addr1'        => array(array('trim')),
			'billing_addr2'        => array(array('trim')),
			'billing_addr3'        => array(array('trim')),
			'billing_postal_code'  => array(array('trim')),
			'shipping_name'        => array(array('trim')),
			'shipping_addr1'       => array(array('trim')),
			'shipping_addr2'       => array(array('trim')),
			'shipping_addr3'       => array(array('trim')),
			'shipping_postal_code' => array(array('trim')),
		);
	}

	/**
	 * Validation callback to ensure shipping/billing value has a first
	 * name and surname.
	 *
	 * @param   string  $value
	 * @return  bool
	 */
	public function full_name($value)
	{
		return strpos(trim($value), ' ') !== FALSE;
	}

	/**
	 * Calculates the total price (excluding VAT) of all products within the
	 * order and the shipping cost, rounded to 2 decimal places.
	 *
	 * If $apply_discount is true, then the value of the 'discount' property
	 * shall be deducted from the above result.
	 *
	 * @param   bool  $apply_discount
	 * @return  float
	 */
	public function amount($apply_discount = TRUE)
	{
		$amount = $this->shipping_price;
		foreach ($this->products->find_all() as $product)
		{
			$amount += $product->quantity * $product->price;
		}

		if ($apply_discount)
		{
			$amount -= $this->discount;
		}

		return round(max(0, $amount), 2);
	}

	/**
	 * Allows you to update the "status" of an existing order
	 *
	 * @param   string  $status
	 * @return  mixed
	 */
	public function update_status($status)
	{
		$this->status = $status;
		return parent::save();
	}

	/**
	 * Allows you to update the "payment_method" of an existing order
	 *
	 * @param   string  $method
	 * @return  mixed
	 */
	public function update_payment_method($method)
	{
		$this->payment_method = $method;
		return parent::save();
	}

	/**
	 * Allows you to update the "notes" of an existing order
	 *
	 * @param   string  $notes
	 * @return  mixed
	 */
	public function update_notes($notes)
	{
		$this->notes = $notes;
		return parent::save();
	}

	/**
	 * Override the save() method to provide some default value for columns
	 *
	 * @return  mixed
	 */
	public function save(Validation $validation = NULL)
	{
		if ($this->loaded())
			throw new Kohana_Exception('existing orders can not be modified');

		$this->date = Db::expr('UTC_TIMESTAMP()');
		$this->vat_rate = (float) Kohana::$config->load('oz-ecommerce')->vat_rate;
		return parent::save($validation);
	}

	/**
	 * Override the delete() method to prevent existing orders being deleted
	 *
	 * @return  mixed
	 */
	public function delete()
	{
		if ($this->loaded())
			throw new Kohana_Exception('existing orders can not be deleted');

		return parent::delete();
	}

}
