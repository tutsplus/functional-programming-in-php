<?php

class AuthPlugin implements Authentication {

	private $permissions = array();
	private $appModule;
	private $permissionsFunction;

	function __construct(ApplicationModule $appModule) {
		$this->appModule = $appModule;
	}

	function authenticate($username, $password) {
		$this->verifyUser($username, $password);
		$this->permissions = $this->permissionsFunction($username);
	}

	private function verifyUser($username, $password) {
		// ... DO USER / PASS CHECKING
		// ... LOAD USER DETAILS, ETC.
	}

	public function setPermissions($permissionGrantingFunction) {
		$this->permissionsFunction = $permissionGrantingFunction;
	}

}

?>
