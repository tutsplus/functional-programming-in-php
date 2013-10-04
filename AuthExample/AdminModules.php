<?php

class AdminModules {

	private $authPlugin;

	function __construct(Authentitcation $authPlugin) {
		$this->authPlugin = $authPlugin;
	}

	private function allowRead($username) {
		return "yes";
	}

	private function allowWrite($username) {
		return "no";
	}

	private function allowExecute($username) {
		return $username == "joe" ? "yes" : "no";
	}

	private function authenticate() {

		$this->authPlugin->setPermissions(
				function($username) {
					$permissions = array();
					$permissions[] = $this->allowRead($username);
					$permissions[] = $this->allowWrite($username);
					$permissions[] = $this->allowExecute($username);
					return $permissions;
				}
		);
		$this->authPlugin->authenticate();
	}

}

?>
