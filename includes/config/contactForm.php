<?php
ob_start(  );
use PHPMailer\PHPMailer\PHPMailer;

$errorMsg="";

if($_SERVER['REQUEST_METHOD']=='POST')
{

    if (isset($_POST['g-recaptcha-response']) && (!empty($_POST['g-recaptcha-response']))) {
        $verifyRes  =   file_get_contents( 'https://www.google.com/recaptcha/api/siteverify?secret=' . $_ENV['CAPTCHA_KEY'] . '&response=' . $_POST['g-recaptcha-response'] );
        $resData    =   json_decode( $verifyRes );        
        if ($resData->success) {
            try {
                $mail = new PHPMailer( true );
                $mail->isSMTP( );
                $mail->SMTPDebug = 2;
                $mail->Host = $_ENV['ML_HOST'];
                $mail->SMTPAuth = true;
                $mail->Username = $_ENV['ML_USER'];
                $mail->Password = $_ENV['ML_PASS'];
                $mail->SMTPSecure = 'ssl';
                $mail->Port = $_ENV['ML_PORT'];

                $mail->setFrom('admin@abraham-montes.com');
                $mail->addAddress('admin@abraham-montes.com', 'Abraham-Montes.com');
                $mail->Subject = 'New contact request from abraham-montes.com';
                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8';

                $contenido = '<html> <h2> New contact request at abraham-montes.com </h2><br>';
                $contenido .= '<strong>From: </strong><p>' . filter_var( $_POST['name'],FILTER_SANITIZE_SPECIAL_CHARS ) . '</p><br>';
                $contenido .= '<strong>Email: </strong><p>' . filter_var( $_POST['email'],FILTER_SANITIZE_EMAIL ) . '</p><br>';
                $contenido .= '<strong>Affiliation: </strong><p>' . filter_var( $_POST['affiliation'],FILTER_SANITIZE_SPECIAL_CHARS ) . '</p><br>';
                $contenido .= '<strong>Message: </strong><p>' . filter_var($_POST['mensaje'],FILTER_SANITIZE_SPECIAL_CHARS) . '</p><br>';
                $contenido .= '</html>';
                $mail->Body = $contenido;

                $contenidoAlt = 'New contact request at abraham-montes.com';
                $contenidoAlt .= ' From: ' . filter_var( $_POST['name'],FILTER_SANITIZE_SPECIAL_CHARS );
                $contenidoAlt .= ' Email: ' . filter_var( $_POST['email'],FILTER_SANITIZE_EMAIL );
                $contenidoAlt .= ' Message: ' . filter_var($_POST['mensaje'],FILTER_SANITIZE_SPECIAL_CHARS);
                $mail->AltBody = $contenidoAlt;
                if($mail->send(  )) {
                    ob_get_clean();
                    header('Location: /?stme=1');
                } else {
                    ob_get_clean();
                    header('Location: /?errm=0');
                }
            } catch (Exception $e) {
                ob_get_clean();
                echo "<pre>";
                echo $e;
                echo "</pre>";
                exit;
                header('Location: /?errm=1');
            }
        } else {
            ob_get_clean();
            header('Location: /?errm=2');
        } 
    } else {
        ob_get_clean();
        header('Location: /?errm=2');
    }
}
?>