<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Vouchers model
 *
 * @package openzula/kohana-oz-ecommerce
 * @author Alex Cartwright <alex@openzula.org>
 * @copyright Copyright (c) 2011, OpenZula
 * @license http://openzula.org/license-bsd-3c BSD 3-Clause License
 */

class Model_Oz_Voucher extends ORM {

	public function rules()
	{
		return array(
			'code' => array(
				array('max_length', array(':value', 16))
			),
			'start_date' => array(array('date')),
			'end_date'   => array(array('date')),
			'percentage' => array(
				array('digit'),
				array('range', array(':value', 1, 99))
			)
		);
	}

	/**
	 * Returns bool TRUE if the voucher is currently valid
	 *
	 * @return bool
	 */
	public function is_valid()
	{
		if (strtotime($this->start_date) > time() OR strtotime($this->end_date) < time())
			return FALSE;

		return TRUE;
	}

}
