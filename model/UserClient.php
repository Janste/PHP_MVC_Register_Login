<?php

/**
 * Class UserClient
 * It contains information about the user's client, like ip address
 */

class UserClient {
	private $remoteAddress;
	private $userAgent;

    /**
     * Constructor. Creates new UserClient object
     * @param $remoteAddress
     * @param $userAgent
     */
	public function __construct($remoteAddress, $userAgent) {
		$this->remoteAddress = $remoteAddress;
		$this->userAgent = $userAgent;
	}

    /**
     * Checks if this object is the same as the user object
     * @param UserClient $other
     * @return bool
     */
	public function isSame(UserClient $other) {
		return  $other->remoteAddress == $this->remoteAddress &&
				$other->userAgent == $this->userAgent;
	}
}