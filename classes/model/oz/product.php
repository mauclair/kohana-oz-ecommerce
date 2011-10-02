<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Product model
 *
 * @package openzula/kohana-oz-ecommerce
 * @author Alex Cartwright <alex@openzula.org>
 * @copyright Copyright (c) 2011, OpenZula
 * @license http://openzula.org/license-bsd-3c BSD 3-Clause License
 */

class Model_Oz_Product extends ORM {

	protected $_has_many = array(
		'categories'     => array(
			'model'   => 'product_category',
			'through' => 'product_categories_products'
		),
		'photos'         => array(
			'model' => 'product_photo'
		),
		'reviews'        => array(
			'model' => 'product_review'
		),
		'specifications' => array(
			'model' => 'product_specification'
		),
		'variations'     => array(
			'model' => 'product_variation'
		),
	);

	public function rules()
	{
		return array(
			'name'             => array(array('not_empty')),
			'description'      => array(array('not_empty')),
			'price'            => array(
				array('not_empty'),
				array('numeric'),
				array('gte', array(':value', 0))
			),
			'sale_price'       => array(
				array('numeric'),
				array('lt', array(':value', $this->price)),
				array('gte', array(':value', 0))
			),
			'quantity'         => array(
				array('digit'),
			),
			'primary_photo_id' => array(
				array('digit'),
				array('gt', array(':value', 0))
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
	 * @return Model_Oz_Product
	 */
	public function uncategorised()
	{
		return $this->join(array('product_categories_products', 'pivot'), 'LEFT')
			->on('products.id', '=', 'pivot.product_id')
			->where('pivot.id', 'IS', NULL);
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

	/**
	 * If this product has 1 or more variations, then the sum of the 'quantity'
	 * property shall be returned for those variations. When no variations are
	 * present, then simply return the 'quantity' property of this object.
	 *
	 * @return int
	 */
	public function available_quantity()
	{
		if ($this->loaded())
		{
			$quantity = DB::select(array('SUM("quantity")', 'quantity_sum'))
				->from('product_variations')
				->where('product_id', '=', $this->pk())
				->execute()
				->get('quantity_sum');

			if (NULL != $quantity)
				return $quantity;
		}

		return $this->quantity;
	}

	/**
	 * Overload the save method to set the sale_price to NULL if an empty
	 * or 0.00 value was given
	 *
	 * @return mixed
	 */
	public function save(Validation $validation=NULL)
	{
		if ( ! $this->sale_price)
		{
			$this->sale_price = NULL;
		}
		return parent::save($validation);
	}

	/**
	 * Overload the delete method to remove all photo file & directory
	 *
	 * @return mixed
	 */
	public function delete()
	{
		$photo_dir = 'assets/photos/'.$this->id;
		$foo = parent::delete();

		if (is_dir($photo_dir))
		{
			foreach (new DirectoryIterator($photo_dir) as $file)
			{
				if ($file->isFile())
				{
					unlink($file->getPathName());
				}
			}
			rmdir($photo_dir);
		}

		return $foo;
	}

}
