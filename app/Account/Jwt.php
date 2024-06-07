<?php

namespace Account;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Exception;

class Jwt {

	protected $config = [
		'domain' => '',
		'key' => ''
	];


	public function __construct(array $config = [])
	{
		$this->config = array_merge($this->config, $config);
	}


	public function header($header)
	{
		// get token from header
		$token = null;
		if(is_string($header)) {
			$parts = preg_split('#\s+#', $header);
			if(count($parts) == 2 && strtolower($parts[0]) == 'bearer') {
				$token = $parts[1];
			}
		}
		return $token;
	}


	public function claim($token, $claim = 'uid')
	{
		try {
			$token = (new Parser())->parse($token);
			$data = new ValidationData();
			$data->setIssuer($this->config['domain']);
			$data->setAudience($this->config['domain']);
			$signer = new Sha256();
			$key = new Key($this->config['key']);
			if($token->validate($data) && $token->verify($signer, $key)) {
				return $token->getClaim($claim);
			}
		} catch( Exception $e) {}

		return false;
	}


	public function create($claims = [], $expires = 86400)
	{
		$builder = (new Builder())
		->issuedBy($this->config['domain'])
		->permittedFor($this->config['domain'])
		->setId(substr(str_replace(['/', '+', '='], '', base64_encode(openssl_random_pseudo_bytes(128))), 0, 64))
		->issuedAt(time())
		->canOnlyBeUsedAfter(time())
		->expiresAt(time() + $expires);

		foreach($claims as $key => $value) {
			$builder = $builder->withClaim($key, $value);
		}
		$signer = new Sha256();
		$key = new Key($this->config['key']);
		return $builder->getToken($signer, $key)->__toString();
	}
}