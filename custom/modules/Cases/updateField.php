<?php
class FieldUpdate {
	function updateCase(&$bean, $event, $arguments) {
		echo "<pre>";
		$bean->description = $bean->description . "The time is " . date("h:i:sa");
		print_r ($bean);
	}
}
