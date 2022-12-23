<?php
use Spipu\Html2Pdf\Html2Pdf;

if(request() == 'POST')
{
    $conn = conn();
    $db   = new Database($conn);

    $slug = $_GET['slug'];
    $event = $db->single('events',['slug'=>$slug]);

    $path = $event->thumbnail;
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data_logo = file_get_contents($path);
    $thumbnail = 'data:image/' . $type . ';base64,' . base64_encode($data_logo);

    if(empty($event))
    {
        header("location:".routeTo('errors/404'));
        die();
    }

    if(!empty($_FILES))
    {
        foreach($_FILES as $key => $value)
        {
            if(!empty($value['name']))
            {
                $file_upload = do_upload($value, 'uploads');
                $_POST[$key] = $file_upload;
            }
        }
    }

    $insert = $db->insert('submissions',[
        'event_id' => $event->id,
        'fills'    => json_encode($_POST)
    ]);

    $wa = $db->single('forms',['event_id'=>$event->id,'type'=>'wa']);
    if($wa)
    {
        // generate report first with html2pdf
        $foto = $db->single('forms',['event_id'=>$event->id,'type'=>'foto']);
        if($foto)
        {
            $path = $_POST[$foto->name];
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data_logo = file_get_contents($path);
            $foto = 'data:image/' . $type . ';base64,' . base64_encode($data_logo);
        }
        else
        {
            $foto = false;
        }

        $nama = $db->single('forms',['event_id'=>$event->id,'type'=>'nama']);

        $forms = $db->all('forms',['event_id'=>$event->id]);

        ob_start();
        require '_pdf.php';
        $html = ob_get_contents(); 
        ob_end_clean();
        
        $html2pdf = new Html2Pdf();
        $html2pdf->writeHTML($html);
        // $html2pdf->output(); 
        if(!is_dir('pdf'))
        {
            mkdir('pdf',777);
        }
        
        $name = $nama ? $nama->name : strtotime('now');
        $filepdf = 'pdf/'.$name.'.pdf';
        $html2pdf->output(__DIR__ . "/../public/". $filepdf,'F');

        send_wa(json_decode('{
            "device": "'.config('WA_API_DEVICE').'",
            "receiver": "'.$_POST[$wa->name].'",
            "type": "chat",
            "message": "Hai '.$name.', silahkan download PDF Buku Tamu Digital Anda",
            "simulate_typing": 1
        }'));

        send_wa(json_decode('{
            "device": "'.config('WA_API_DEVICE').'",
            "receiver": "'.$_POST[$wa->name].'",
            "type": "file",
            "message": "'.$name.'.pdf",
            "file_url": "'.routeTo($filepdf).'",
            "simulate_typing": 1
        }'));
    }

    header('location:'.routeTo('thank-you'));
    die();
}