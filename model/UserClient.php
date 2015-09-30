<?php

class UserClient {
	private $remoteAddress;
	private $userAgent;

	public function __construct($remoteAddress, $userAgent) {
		$this->remoteAddress = $remoteAddress;
		$this->userAgent = $userAgent;
	}

	public function isSame(UserClient $other) {
		return  $other->remoteAddress == $this->remoteAddress &&
				$other->userAgent == $this->userAgent;
	}
}