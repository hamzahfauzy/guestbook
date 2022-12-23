<?php

$db->query = "SELECT * FROM $table $where ORDER BY ".$columns[$order[0]['column']]." ".$order[0]['dir']." LIMIT $start,$length";
$data  = $db->exec('all');

$data  = array_map(function($d){
    $url = routeTo($d->slug);
    $d->link = "<a href='$url'>$url</a>";

    return $d;
}, $data);

$total = $db->exists($table,$where,[
    $columns[$order[0]['column']] => $order[0]['dir']
]);

return compact('data','total');