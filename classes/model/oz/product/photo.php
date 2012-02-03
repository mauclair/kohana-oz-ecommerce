<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Product photo model
 *
 * @package    openzula/kohana-oz-ecommerce
 * @author     Alex Cartwright <alex@openzula.org>
 * @copyright  Copyright (c) 2011, 2012 OpenZula
 * @license    http://openzula.org/license-bsd-3c BSD 3-Clause License
 */
abstract class Model_Oz_Product_Photo extends ORM {

	protected $_belongs_to = array(
		'product' => array(),
	);

	protected $_table_columns = array(
		'id'             => array('type' => 'int'),
		'product_id'     => array('type' => 'int'),
		'path_fullsize'  => array('type' => 'string'),
		'path_thumbnail' => array('type' => 'string'),
	);

	public function rules()
	{
		return array(
			'product_id' => array(
				array('not_empty'),
				array('digit'),
				array('gt', array(':value', 0)),
			),
			'path_fullsize' => array(
				array('not_empty'),
				array('is_file'),
			),
			'path_thumbnail' => array(
				array('not_empty'),
				array('is_file'),
			),
		);
	}

	/**
	 * Overload the delete method to remove the photo files first
	 *
	 * @return  mixed
	 */
	public function delete()
	{
		$files = array($this->path_fullsize, $this->path_thumbnail);
		$foo = parent::delete();

		foreach ($files as $file)
		{
			if ( ! $file)
				continue;

			try
			{
				unlink($file);

				// Remove the parent directory if it's empty
				$dir = dirname($file);
				if (count(scandir($dir)) == 2)
				{
					rmdir($dir);
				}
			}
			catch (Exception $e)
			{
				Kohana::$log->add(Log::WARNING, $e->getMessage());
			}
		}

		return $foo;
	}

}
