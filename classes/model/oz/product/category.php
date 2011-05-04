<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Product category model
 *
 * @package openzula/kohana-oz-ecommerce
 * @author Alex Cartwright <alex@openzula.org>
 * @copyright (C) 2011 OpenZula
 */

class Model_Oz_Product_Category extends ORM {

	protected $_has_many = array(
		'products' => array(
			'through'     => 'product_categories_products',
			'foreign_key' => 'category_id',
		)
	);

	public function rules()
	{
		return array(
			'name'      => array(array('not_empty')),
			'order'     => array(array('not_empty'), array('digit')),
			'parent_id' => array(array('digit')),
		);
	}

	public function filters()
	{
		return array(
			'name'        => array(array('trim')),
			'description' => array(array('trim')),
		);
	}

	/**
	 * Returns a full tree of nested product categories started at a category
	 *
	 * @param int $start
	 * @return array
	 */
	public function full_tree($start=NULL)
	{
		$tree = array();

		$product_categories = ORM::factory('product_category')
			->where('parent_id', '=', $start)
			->find_all();

		foreach ($product_categories as $category)
		{
			$tree[] = array(
				'id'          => $category->id,
				'name'        => $category->name,
				'description' => $category->description,
				'order'       => (int) $category->order,
				'parent_id'   => (int) $category->parent_id,
				'children'    => $this->full_tree($category->id),
			);
		}
		return $tree;
	}

}
