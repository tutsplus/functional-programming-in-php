<?php

interface Authentication {

	function setPermissions($permissionGrantingFunction);

	function authenticate();

}

?>
