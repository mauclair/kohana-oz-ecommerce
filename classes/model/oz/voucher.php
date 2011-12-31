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
				array('max_length', array(':value', 16)),
				array(array($this, 'code_available'), array(':validation', ':field')),
			),
			'start_date' => array(
				array('date'),
			),
			'end_date' => array(
				array('date'),
			),
			'percentage' => array(
				array('digit'),
				array('range', array(':value', 1, 99)),
			)
		);
	}

	/**
	 * Triggers a validation error if the given code is not unique.
	 * Validation callback.
	 *
	 * @param	Validation	Validation object
	 * @param	string		Field name
	 * @return	void
	 */
	public function code_available(Validation $validation, $field)
	{
		$exists = (bool) DB::select(array('COUNT("*")', 'total_count'))
			->from($this->_table_name)
			->where('code', '=', $validation[$field])
			->where($this->_primary_key, '!=', $this->pk())
			->execute()
			->get('total_count');

		if ($exists)
		{
			$validation->error($field, 'code_available', array($validation[$field]));
		}
	}

	/**
	 * Returns bool TRUE if the voucher is currently valid
	 *
	 * @return	bool
	 */
	public function is_valid()
	{
		if (strtotime($this->start_date) > time() OR strtotime($this->end_date) < time())
			return FALSE;

		return TRUE;
	}

	/**
	 * Override the save() method to convert the dates to MySQL DATETIME format
	 *
	 * @return mixed
	 */
	public function save(Validation $validation = NULL)
	{
		try
		{
			$this->start_date = Date::formatted_time($this->start_date, 'Y-m-d H:i:s');
		}
		catch(Exception $e) {}

		try
		{
			$this->end_date = Date::formatted_time($this->end_date, 'Y-m-d H:i:s');
		}
		catch(Exception $e) {}

		return parent::save($validation);
	}

}
