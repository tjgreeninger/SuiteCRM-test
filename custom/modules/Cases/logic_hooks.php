<?php
$hook_version = 1;
$hook_array = Array();
$hook_array['before_save'][] = Array(1, 'assignUser', 'custom/modules/Cases/updateField.php', 'FieldUpdate', 'updateCase');
