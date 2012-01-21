<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Product category model
 *
 * @package    openzula/kohana-oz-ecommerce
 * @author     Alex Cartwright <alex@openzula.org>
 * @copyright  Copyright (c) 2011, OpenZula
 * @license    http://openzula.org/license-bsd-3c BSD 3-Clause License
 */
abstract class Model_Oz_Product_Category extends ORM {

	protected $_has_many = array(
		'products' => array(
			'through'     => 'product_categories_products',
			'foreign_key' => 'category_id',
		)
	);

	protected $_table_columns = array(
		'id'          => array('type' => 'int'),
		'name'        => array('type' => 'string'),
		'description' => array('type' => 'string'),
		'order'       => array('type' => 'int'),
		'parent_id'   => array('type' => 'int'),
	);

	public function rules()
	{
		return array(
			'name' => array(
				array('not_empty'),
			),
			'order' => array(
				array('not_empty'),
				array('digit'),
			),
			'parent_id' => array(
				array('digit'),
				array('gt', array(':value', 0)),
			),
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
	 * @param   int   $start
	 * @param   int   $stop   do not return this category ID
	 * @return  array
	 */
	public function full_tree($start = NULL, $stop = NULL)
	{
		$tree = array();

		$product_categories = ORM::factory('product_category')
			->where('parent_id', '=', $start)
			->order_by('order', 'ASC')
			->find_all();

		foreach ($product_categories as $category)
		{
			if ($stop == $category->id)
				continue;

			$tree[] = $category->as_array() + array(
				'children' => $this->full_tree($category->id, $stop)
			);
		}
		return $tree;
	}

	/**
	 * Gets the reverse tree of categories, selecting the first parent. Useful
	 * when need to generate breadcrumb type feature
	 *
	 * @param   int   $start
	 * @return  array
	 */
	public function reverse_tree($start)
	{
		$tree = array();

		$category = ORM::factory('product_category', $start);
		$tree[] = $category;

		while ($category->parent_id)
		{
			$category = ORM::factory('product_category', $category->parent_id);
			$tree[] = $category;
		}

		return array_reverse($tree);
	}

	/**
	 * Find all of the cheapest products (sale_price takes preference over price
	 * in this case) within the category.
	 *
	 * @return  mixed
	 */
	public function cheapest_products()
	{
		if ( ! $this->loaded())
			return $this->products;

		$minprice = $this->products
			->select(array(Db::expr('LEAST(MIN(price), MIN(sale_price))'), 'minprice'))
			->find()
			->minprice;

		return $this->products
			->where('price', '=', $minprice)
			->or_where('sale_price', '=', $minprice);
	}

	/**
	 * Finds the most expensive (dearest) products within the category.
	 *
	 * @return  mixed
	 */
	public function dearest_products()
	{
		if ( ! $this->loaded())
			return $this->products;

		$maxprice = $this->products
			->select(array(Db::expr('MAX(price)'), 'maxprice'))
			->find()
			->maxprice;

		return $this->products
			->where('price', '=', $maxprice);
	}

}
