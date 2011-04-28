<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Product photo model
 *
 * @package openzula/kohana-oz-ecommerce
 * @author Alex Cartwright <alex@openzula.org>
 * @copyright (C) 2011 OpenZula
 */

class Model_Oz_Product_Photo extends ORM {

	protected $_belongs_to = array(
		'product' => array()
	);

	public function rules()
	{
		return array(
			'product_id' => array(array('not_empty'), array('digit')),
			'filename'   => array(array('not_empty'), array('is_file')),
		);
	}

	/**
	 * Overload the delete method to remove the file
	 *
	 * @return mixed
	 */
	public function delete()
	{
		$filename = $this->filename;
		$foo = parent::delete();
		unlink($filename);
		return $foo;
	}

}
