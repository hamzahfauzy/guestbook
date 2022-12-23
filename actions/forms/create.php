<?php

$table = 'forms';
Page::set_title('Tambah '._ucwords(__($table)));
$error_msg = get_flash_msg('error');
$old = get_flash_msg('old');
$fields = config('fields')[$table];

if(request() == 'POST')
{
    $conn = conn();
    $db   = new Database($conn);

    $_POST[$table]['event_id'] = $_GET['event_id'];
    $insert = $db->insert($table,$_POST[$table]);

    set_flash_msg(['success'=>_ucwords(__($table)).' berhasil ditambahkan']);
    header('location:'.routeTo('forms/index',['event_id'=>$insert->event_id]));
}

return compact('table','error_msg','old','fields');
