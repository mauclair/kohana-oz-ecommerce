<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Orders model
 *
 * @package openzula/kohana-oz-ecommerce
 * @author Alex Cartwright <alex@openzula.org>
 * @copyright (C) 2011 OpenZula
 */

class Model_Oz_Order extends ORM {

	protected $_has_many = array(
		'products' => array(
			'model' => 'order_product'
		),
	);

	public function rules()
	{
		return array(
			'shipping_price'       => array(
				array('numeric'),
				array('gte', array(':value', 0))
			),
			'vat_rate'             => array(
				array('numeric'),
				array('gte', array(':value', 0))
			),
			'billing_name'         => array(array('not_empty')),
			'billing_addr1'        => array(array('not_empty')),
			'billing_postal_code'  => array(
				array('not_empty'),
				array('postal_code_uk'),
			),
			'shipping_name'        => array(array('not_empty')),
			'shipping_addr1'       => array(array('not_empty')),
			'shipping_postal_code' => array(
				array('not_empty'),
				array('postal_code_uk'),
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
	 * Calculates the total price (excluding VAT) of all products within the
	 * order and the shipping cost, rounded to 2 decimal places.
	 *
	 * @return float
	 */
	public function amount()
	{
		$amount = $this->shipping_price;
		foreach ($this->products->find_all() as $product)
		{
			$amount += $product->quantity * $product->price;
		}
		return round($amount, 2);
	}

	/**
	 * Allows you to update the "status" of an existing order
	 *
	 * @param string $status
	 * @return mixed
	 */
	public function update_status($status)
	{
		$this->status = $status;
		return parent::save();
	}

	/**
	 * Override the save() method to provide some default value for columns
	 *
	 * @return mixed
	 */
	public function save(Validation $validation = NULL)
	{
		if ($this->loaded())
			throw new Kohana_Exception('existing orders can not be modified');

		$this->date = Db::expr('UTC_TIMESTAMP()');
		$this->vat_rate = (float) Kohana::config('oz-ecommerce')->vat_rate;
		return parent::save($validation);
	}

	/**
	 * Override the delete() method to prevent existing orders being deleted
	 *
	 * @return mixed
	 */
	public function delete()
	{
		if ($this->loaded())
			throw new Kohana_Exception('existing orders can not be deleted');

		return parent::delete();
	}

}
