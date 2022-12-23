<?php

if(isset($_FILES['thumbnail']) && !empty($_FILES['thumbnail']['name']))
{
    $file_upload = do_upload($_FILES['thumbnail'], 'uploads');
    $_POST['events']['thumbnail'] = $file_upload;
}