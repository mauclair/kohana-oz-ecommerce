<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Product model
 *
 * @package openzula/kohana-oz-ecommerce
 * @author Alex Cartwright <alex@openzula.org>
 * @copyright (C) 2011 OpenZula
 */

class Model_Oz_Product extends ORM {

	protected $_has_many = array(
		'photos'     => array(
			'model' => 'product_photo'
		),
		'categories' => array(
			'model'   => 'product_category',
			'through' => 'product_categories_products'
		),
	);

	public function rules()
	{
		return array(
			'name'             => array(array('not_empty')),
			'description'      => array(array('not_empty')),
			'price'            => array(array('not_empty'), array('numeric')),
			'sale_price'       => array(array('numeric')),
			'quantity'         => array(array('not_empty'), array('digit')),
			'primary_photo_id' => array(array('digit')),
		);
	}

	public function filters()
	{
		return array(
			'name' => array(array('trim')),
		);
	}

	/**
	 * Return the primary product photo
	 *
	 * @return Model_Oz_Product_Photo
	 */
	public function primary_photo()
	{
		return ORM::factory('product_photo', $this->primary_photo_id);
	}

}
