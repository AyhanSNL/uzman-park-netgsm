<?php

namespace Noxarc\Netgsm;

use Noxarc\Netgsm\Notifications\SendPhoneVerificationCode;
use Noxarc\Netgsm\Models\PhoneVerification;

trait HasPhone
{
	public function phoneVerificationCodes()
	{
		return $this->morphMany(PhoneVerification::class, 'verifiable');
	}

	public function hasActivePhoneVerificationCode() : bool
	{
		return $this->phoneVerificationCodes()->where('expire_date', '>', now())->where('revoked', false)->exists();
	}

	public function sendPhoneVerificationCode()
	{
		if (!$this->hasActivePhoneVerificationCode() && $this->hasValidPhoneNumber()) {

			$code = rand(12123, 98989);

			$this->phoneVerificationCodes()->create([
				'expire_date' => now()->addSeconds(config('netgsm.verification_code_expires')),
				'code' => $code,
				'phone' => $this->getPhone()
			]);

			$message = trans('netgsm::verification.message', ['code' => $code]);

			$this->notify(new SendPhoneVerificationCode($message));
		}
	}

	public function getPhone() : ?string
	{
		return $this->phone;
	}

	public function verifyPhone($code) : bool
	{
		return $this->phoneVerificationCodes()->where('code', $code)->where('phone', $this->getPhone())->where('expire_date', '>', now())->exists();
	}

	public function revokeAllPhoneVerificationCodes()
	{
		$this->phoneVerificationCodes()->where('revoked', false)->update(['revoked' => true]);
	}

	public function hasValidPhoneNumber() : bool
	{
		return !empty($this->getPhone());
	}
}