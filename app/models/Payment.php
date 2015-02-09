<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Payment extends Eloquent {

	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'payments';
	
	protected $dates = ['deleted_at'];

	public function currency() {
		return $this->belongsTo('Currency');
	}

}
