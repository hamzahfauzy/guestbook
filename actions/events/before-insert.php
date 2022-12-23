<?php
$file_upload = do_upload($_FILES['thumbnail'], 'uploads');

$_POST['events']['thumbnail'] = $file_upload;
$_POST['events']['slug'] = slug($_POST['events']['name']);