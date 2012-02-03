<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Product model
 *
 * @package    openzula/kohana-oz-ecommerce
 * @author     Alex Cartwright <alex@openzula.org>
 * @copyright  Copyright (c) 2011, 2012 OpenZula
 * @license    http://openzula.org/license-bsd-3c BSD 3-Clause License
 */
abstract class Model_Oz_Product extends ORM {

	protected $_has_many = array(
		'categories' => array(
			'model'   => 'product_category',
			'through' => 'product_categories_products',
		),
		'photos' => array(
			'model' => 'product_photo',
		),
		'reviews' => array(
			'model' => 'product_review',
		),
		'specifications' => array(
			'model' => 'product_specification',
		),
		'variations' => array(
			'model' => 'product_variation',
		),
	);

	protected $_table_columns = array(
		'id'                => array('type' => 'int'),
		'name'              => array('type' => 'string'),
		'description'       => array('type' => 'string'),
		'primary_photo_id'  => array('type' => 'int'),
		'avg_review_rating' => array('type' => 'float'),
		'visible'           => array('type' => 'int'),
	);

	public function rules()
	{
		return array(
			'name' => array(
				array('not_empty'),
			),
			'description' => array(
				array('not_empty'),
			),
			'primary_photo_id' => array(
				array('digit'),
				array('gt', array(':value', 0)),
			),
			'visible' => array(
				array('digit'),
			),
		);
	}

	public function filters()
	{
		return array(
			'name' => array(array('trim')),
		);
	}

	/**
	 * Finds all uncategorised products
	 *
	 * @return  Model_Oz_Product
	 */
	public function uncategorised()
	{
		return $this->join(array('product_categories_products', 'pivot'), 'LEFT')
			->on($this->object_name().'.id', '=', 'pivot.product_id')
			->where('pivot.id', 'IS', NULL);
	}

	/**
	 * Return the primary product photo
	 *
	 * @return  Model_Oz_Product_Photo
	 */
	public function primary_photo()
	{
		return ORM::factory('product_photo', $this->primary_photo_id);
	}

	/**
	 * Return the sum of the "quantity" property of all variations this product
	 * has.
	 *
	 * @return  int
	 */
	public function available_quantity()
	{
		if ( ! $this->loaded())
			return 0;

		return (int) DB::select(array('SUM("quantity")', 'quantity_sum'))
			->from('product_variations')
			->where('product_id', '=', $this->pk())
			->execute()
			->get('quantity_sum');
	}

	/**
	 * Overload the delete method to remove all photos first. This is
	 * so the physical files (and any resulting dangling directories)
	 * get removed as well, using the code present in
	 * Model_Product_Photo::delete()
	 *
	 * @return  mixed
	 */
	public function delete()
	{
		foreach ($this->photos->find_all() as $photo)
		{
			$photo->delete();
		}

		return parent::delete();
	}

}
