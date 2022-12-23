<?php

$table = 'forms';
$conn = conn();
$db   = new Database($conn);

$param = [
    'id' => $_GET['id']
];

$data = $db->single($table,$param);
$db->delete($table,$param);

set_flash_msg(['success'=>_ucwords(__($table)).' berhasil dihapus']);
header('location:'.routeTo('forms/index',['event_id'=>$data->event_id]));
die();
