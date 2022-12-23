<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title><?=$event->name?></title>
	<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
	<link rel="icon" href="assets/img/main-logo.png" type="image/x-icon"/>

	<!-- Fonts and icons -->
	<script src="assets/js/plugin/webfont/webfont.min.js"></script>
	<script>
		WebFont.load({
			google: {"families":["Lato:300,400,700,900"]},
			custom: {"families":["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: ['assets/css/fonts.min.css']},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script>

	<!-- CSS Files -->
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/atlantis.min.css">
</head>
<body style="min-height:auto;">
	<div class="container">
        <div class="row mt-4">
            <div class="col-sm-12 col-md-6 mx-auto">
                <div class="card full-height">
                    <div class="card-body">
                        <div class="card-title text-center"><?=$event->name?></div>
                        <p class="text-center"><?=$event->description?></p>
                        
                        <center>
                            <img src="<?=routeTo($event->thumbnail)?>" width="100%" height="auto" alt="<?=$event->name?>" style="object-fit:contain;">
                        </center>

                        <form action="<?=routeTo('submit-submission',['slug'=>$event->slug])?>" method="post" enctype="multipart/form-data">
                            <?php 
                            foreach($fields as $key => $field): 
                                $label = $field;
                                $type  = "text";
                                if(is_array($field))
                                {
                                    $field_data = $field;
                                    $field = $key;
                                    $label = $field_data['label'];
                                    if(isset($field_data['type']))
                                    $type  = $field_data['type'];
                                }
                                $label = _ucwords($label);
                            ?>
                            <div class="form-group">
                                <label for=""><?=$label?></label>
                                <?= Form::input($type, $field, ['class'=>"form-control","placeholder"=>$label,"value"=>$old[$field]??'']) ?>
                            </div>
                            <?php endforeach ?>
                            <div class="form-group">
                                <button class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>