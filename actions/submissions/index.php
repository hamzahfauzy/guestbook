<?php

$table = 'submissions';
Page::set_title(_ucwords(__($table)));
$conn = conn();
$db   = new Database($conn);
$success_msg = get_flash_msg('success');
$event = $db->single('events',['id'=>$_GET['event_id']]);
$forms = $db->all('forms',['event_id'=>$event->id]);
$fields = [];
$fields['event_id'] = [
    'label' => 'Event',
    'type'  => 'options-obj:events,id,name'
]; 

foreach($forms as $form)
{
    $fields[$form->name] = [
        'label' => $form->label,
        'type'  => $form->type.$form->type_param
    ];
}

$fields['created_at'] = [
    'label' => 'Created At',
    'type'  => 'datetime'
]; 

if(isset($_GET['draw']))
{
    $draw    = $_GET['draw'];
    $start   = $_GET['start'];
    $length  = $_GET['length'];
    $search  = $_GET['search']['value'];
    $order   = $_GET['order'];
    
    $columns = [];
    $search_columns = [];
    foreach($fields as $key => $field)
    {
        $columns[] = is_array($field) ? $key : $field;
        if(is_array($field) && isset($field['search']) && !$field['search']) continue;
        $search_columns[] = is_array($field) ? $key : $field;
    }

    $where = "WHERE event_id = ".$_GET['event_id']." ";

    if(!empty($search))
    {
        $_where = [];
        foreach($search_columns as $col)
        {
            $_where[] = "$col LIKE '%$search%'";
        }

        $where = " AND (".implode(' OR ',$_where).")";
    }

    $db->query = "SELECT * FROM $table $where ORDER BY id ASC LIMIT $start,$length";
    $data  = $db->exec('all');
    $data  = array_map(function($d){
        $id = $d->id;
        $event_id = $d->event_id;
        $created_at = $d->created_at;
        $d = json_decode($d->fills);
        $d->id = $id;
        $d->event_id = $event_id;
        $d->created_at = $created_at;
        return $d;
    }, $data);

    $total = $db->exists($table,$where);

    $results = [];
    
    foreach($data as $key => $d)
    {
        $results[$key][] = $key+1;
        foreach($columns as $col)
        {
            $field = '';
            if(isset($fields[$col]))
            {
                $field = $fields[$col];
            }
            else
            {
                $field = $col;
            }
            $data_value = "";
            if(is_array($field))
            {
                $data_value = Form::getData($field['type'],$d->{$col},true);

                if($field['type'] == 'file')
                {
                    $data_value = '<a href="'.asset($data_value).'" target="_blank">Lihat File</a>';
                }
            }
            else
            {
                $data_value = $d->{$field};
            }

            $results[$key][] = $data_value;
        }

        $action = '';

        // if(is_allowed(get_route_path('forms/edit',[]),auth()->user->id)):
        //     $action .= ' <a href="'.routeTo('forms/edit',['id'=>$d->id]).'" class="btn btn-sm btn-warning"><i class="fas fa-pencil-alt"></i> Edit</a>';
        // endif;
        if(is_allowed(get_route_path('submissions/delete',[]),auth()->user->id)):
            $action .= ' <a href="'.routeTo('submissions/delete',['id'=>$d->id]).'" onclick="if(confirm(\'apakah anda yakin akan menghapus data ini ?\')){return true}else{return false}" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</a>';
        endif;
        $results[$key][] = $action;
    }

    echo json_encode([
        "draw" => $draw,
        "recordsTotal" => (int)$total,
        "recordsFiltered" => (int)$total,
        "data" => $results
    ]);

    die();
}

return [
    'table' => $table,
    'success_msg' => $success_msg,
    'fields' => $fields
];
