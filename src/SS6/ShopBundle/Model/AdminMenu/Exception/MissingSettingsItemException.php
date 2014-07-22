<?php

namespace SS6\ShopBundle\Model\AdminMenu\Exception;

use Exception;

class MissingSettingsItemException extends Exception implements MenuException {
	
	/**
	 * @param string $message
	 * @param \Exception $previous
	 */
	public function __construct($message, Exception $previous = null) {
		parent::__construct($message, 0, $previous);
	}
	
}