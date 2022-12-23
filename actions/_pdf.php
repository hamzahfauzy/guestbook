<div style="width:100%;text-align:center">
    <img src="<?=$thumbnail?>" height="250" alt="<?=$event->name?>" style="object-fit:contain;">
    <h2><?=$event->name?></h2>
</div>
<br><br>
<table border="1" cellpadding="5" cellspacing="0" style="width:100%;" align="center">
  <?php $n = 1; foreach($forms as $form): if(startWith($_POST[$form->name], 'uploads/')) continue; ?>
  <tr>
    <td style="padding:10px"><?=$form->label?></td>
    <td style="padding:10px">:</td>
    <td style="padding:10px"><?=$_POST[$form->name]?></td>
    <?php if($n == 1 && $foto): ?>
    <td style="padding:10px" rowspan="<?=count($forms)-1?>"><img src="<?=$foto?>" height="200"></td>
    <?php endif ?>
  </tr>
  <?php $n++; endforeach ?>
</table>