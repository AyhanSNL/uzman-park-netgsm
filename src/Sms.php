<?php

namespace Noxarc\Netgsm;

use GuzzleHttp\Client;
use Exception;

class Sms
{
	protected $api_url = "https://api.netgsm.com.tr";

	public function send(array $phones, string $text)
	{
		$client = new Client(['base_uri' => $this->api_url]);

		$response = $client->request('GET', 'sms/send/get', [
			'query' => [
				'msgheader' => config('netgsm.header'),
				'usercode'  => config('netgsm.usercode'),
				'password'  => config('netgsm.password'),
				'message'	=> config('netgsm.first_text').' '.$text.' '.config('netgsm.last_text'),
				'gsmno'     => implode(',', $phones),
				'dil'	    => config('netgsm.language')
			]
		]);

		$result = $response->getBody()->getContents();

		$code = substr($result, 0, 2);

		switch ($code) {
			case '00':
				return $result;
				break;
			case '20':
				throw new Exception("Netgsm error code : $code exceeded maximum number of characters");
				break;
			case '30':
				throw new Exception("Netgsm error code : $code invalid usercode, password or user");
				break;
			case '40':
				throw new Exception("Netgsm error code : $code invalid msgheader");
				break;
			case '70':
				throw new Exception("Netgsm error code : $code invalid paramters");
				break;
			default:
				throw new Exception("Netgsm error code : $code");
				break;
		}
	}
}