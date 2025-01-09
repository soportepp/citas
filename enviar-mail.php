<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if (isset($_POST['name'])){
    $nombres = htmlentities($_POST['name']);
    $telefono = htmlentities($_POST['number']);
    $email_cliente = htmlentities($_POST['email']);
    $sintomas = htmlentities($_POST['symptoms']);
    $fecha = htmlentities($_POST['datepicker1']);
    $departamento = htmlentities($_POST['departament']);
    $genero = htmlentities($_POST['gender']);
    $hora = htmlentities($_POST['time']);

    // Crear mensaje del correo
    $message = '';
    $message .= '<p>Hola, ha sido registrada una nueva cita en el sitio web, según el detalle siguiente:</p> ';
    $message .= '<p>Cliente: '.$nombres.'</p> ';
    $message .= '<p>Teléfono: '.$telefono.'</p> ';
    $message .= '<p>Email: '.$email_cliente.'</p> ';
    $message .= '<p>Servicio: '.$sintomas.'</p> ';
    $message .= '<p>Departamento: '.$departamento.'</p> ';
    $message .= '<p>Fecha de cita: '.$fecha.'</p> ';
    $message .= '<p>Hora de cita: '.$hora.'</p> ';

    // Configuración de PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Servidor SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'arinmobiliarianet@gmail.com'; // Tu correo
        $mail->Password = 'wert deyk rcuo kvqz'; // Tu contraseña o contraseña de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Configuración del correo
        $mail->setFrom('arinmobiliarianet@gmail.com', 'AR Inmobiliaria');
        $mail->addAddress($email_cliente, $nombres); // Correo destinatario
        $mail->addReplyTo('arinmobiliarianet@gmail.com', 'AR Inmobiliaria'); // Responder al cliente
		$mail->addBCC('ramirezdenisse1718@gmail.com', 'Denisse Romero');


		$template = file_get_contents('email_template.html');

		// Reemplazar los marcadores con los datos del cliente
		$template = str_replace('{{nombre}}', $nombres, $template);
		$template = str_replace('{{telefono}}', $telefono, $template);
		$template = str_replace('{{$email}}', $email_cliente, $template);
		$template = str_replace('{{sintomas}}', $sintomas, $template);
		$template = str_replace('{{departamento}}', $departamento, $template);
		$template = str_replace('{{fecha}}', $fecha, $template);
		$template = str_replace('{{hora}}', $hora, $template);

        $mail->isHTML(true);
		$mail->CharSet = 'UTF-8';
        $mail->Subject = 'Nueva cita en línea de: ' . $nombres;
        $mail->Body = $template;

        $mail->send();

        echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Cita médica registrada!',
            text: 'La cita médica ha sido realizada correctamente.',
            confirmButtonText: 'Aceptar'
        }).then(() => {
            window.location = 'index.html'; // Cambiar la página de redirección si es necesario
        });
    </script>";
} catch (Exception $e) {
    // SweetAlert para error
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: '¡Error al enviar el correo!',
            text: '{$mail->ErrorInfo}',
            confirmButtonText: 'Intentar de nuevo'
        });
    </script>";
}
}
?>
