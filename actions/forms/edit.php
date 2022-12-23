<?php

$table = 'forms';
Page::set_title('Edit '._ucwords(__($table)));
$conn = conn();
$db   = new Database($conn);
$error_msg = get_flash_msg('error');
$old = get_flash_msg('old');
$fields = config('fields')[$table];

$data = $db->single($table,[
    'id' => $_GET['id']
]);

if(request() == 'POST')
{
    $edit = $db->update($table,$_POST[$table],[
        'id' => $_GET['id']
    ]);

    set_flash_msg(['success'=>_ucwords(__($table)).' berhasil diupdate']);
    header('location:'.routeTo('forms/index',['event_id'=>$edit->event_id]));
}

return [
    'data' => $data,
    'error_msg' => $error_msg,
    'old' => $old,
    'table' => $table,
    'fields' => $fields
];
