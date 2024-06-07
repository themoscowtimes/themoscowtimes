<?php

namespace Account;

use Defuse\Crypto\Key;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\KeyProtectedByPassword;

use openssl_encrypt;
use openssl_decrypt;

class Encrypt
{

	protected $config = [
		'path' => '',
		'method' => '',
		'key' => '',
		'iv' => ''
	];

	protected $key = null;

	public function __construct($config)
	{
		$this->config = array_merge($this->config, $config);
	}


	public function encrypt($message, $key = null)
	{
		if($message) {
			return Crypto::encrypt($message, $this->key($key));
		}
	}


	public function decrypt($message, $key = null)
	{
		if($message) {
			return Crypto::decrypt($message, $this->key($key));
		}
	}


	public function obfusciate($message)
	{
		if($message) {
			return bin2hex(openssl_encrypt(
				$message,
				$this->config['method'],
				$this->config['key'],
				OPENSSL_RAW_DATA,
				$this->config['iv']
			));
		}
	}


	public function clarify($message)
	{
		if($message) {
			return openssl_decrypt(
				hex2bin($message),
				$this->config['method'],
				$this->config['key'],
				OPENSSL_RAW_DATA,
				$this->config['iv']
			);
		}
	}


	public function pbk($passwordOrKey, $password = null)
	{
		if($password === null) {
			// generate locked key
			return KeyProtectedByPassword::createRandomPasswordProtectedKey($passwordOrKey)
			->saveToAsciiSafeString();
		} else {
			// unlock key
			return KeyProtectedByPassword::loadFromAsciiSafeString($passwordOrKey)
			->unlockKey($password)
			->saveToAsciiSafeString();
		}
	}


	protected function key($key = null)
	{
		if($key === null) {
			if($this->key === null) {
				$this->key = Key::loadFromAsciiSafeString(file_get_contents($this->config['path']));
			}
			return $this->key;
		} else {
			return Key::loadFromAsciiSafeString($key);
		}
	}

}