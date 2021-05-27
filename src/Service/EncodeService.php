<?php

namespace App\Service;

class EncodeService
{
	private $cipher_algo;
	private $passphrase;
	private $iv;

	public function __construct()
	{
		$this->cipher_algo = 'aes-256-ctr';
		$this->passphrase = '6hU*]f=QV!5i;$tJ(r7.?gZ#^L/_e%Up';
		$this->iv = 'jK9!&,9^rQ)*cD@6';
	}

	public function Encode($data)
	{
		return openssl_encrypt($data, $this->cipher_algo, $this->passphrase, 0, $this->iv);
	}

	public function Decode($data)
	{
		return openssl_decrypt($data, $this->cipher_algo, $this->passphrase, 0, $this->iv);
	}
}