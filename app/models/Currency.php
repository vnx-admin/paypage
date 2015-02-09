<?php

class Currency extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'currencies';

	public function payments() {
		return $this->hasMany('Payment');
	}

}
