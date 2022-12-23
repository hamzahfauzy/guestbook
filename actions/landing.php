<?php
$conn  = conn();
$db    = new Database($conn);

$slug = get_route();
$event = $db->single('events',['slug'=>$slug]);

if(empty($event))
{
    header("location:".routeTo('errors/404'));
    die();
}

$forms = $db->all('forms',['event_id'=>$event->id]);
$fields = [];
foreach($forms as $form)
{
    $fields[$form->name] = [
        'label' => $form->label,
        'type'  => $form->type.$form->type_param
    ];
}

return compact('event','fields');