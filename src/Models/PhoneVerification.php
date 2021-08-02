<?php

namespace Noxarc\Netgsm\Models;

use Illuminate\Database\Eloquent\Model;

class PhoneVerification extends Model
{
	protected $fillable = [
		'code', 'phone', 'expire_date'
	];

	protected $casts = [
		'revoked' => 'boolean'
	];

	public function verifiable()
	{
		return $this->morphTo();
	}
}