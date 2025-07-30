<?php
// Especifica que la respuesta será en formato JSON
header('Content-Type: application/json');

// Comprueba si la solicitud se hizo por el método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- CONFIGURACIÓN DEL CORREO ---
    // Reemplaza esta dirección con el correo donde quieres recibir los mensajes
    $destinatario = "giovannimerida92@gmail.com";
    $asunto = "Nuevo Mensaje del Formulario de Nicolas Máquiavelo";
    // ---------------------------------

    // Limpia y valida los datos recibidos del formulario
    $nombre = filter_var(trim($_POST["nombre"]), FILTER_SANITIZE_STRING);
    $correo = filter_var(trim($_POST["correo"]), FILTER_SANITIZE_EMAIL);
    $telefono = filter_var(trim($_POST["telefono"]), FILTER_SANITIZE_STRING);

    // Validación básica: asegura que los campos no estén vacíos y el email sea válido
    if (empty($nombre) || !filter_var($correo, FILTER_VALIDATE_EMAIL) || empty($telefono)) {
        // Si hay un error, envía una respuesta JSON de error
        echo json_encode([
            'status' => 'error',
            'message' => 'Por favor, completa todos los campos correctamente.'
        ]);
        exit;
    }

    // Construye el cuerpo del mensaje
    $cuerpo_mensaje = "Has recibido un nuevo mensaje desde el sitio web de Nicolas Máquiavelo.\n\n";
    $cuerpo_mensaje .= "Nombre: " . $nombre . "\n";
    $cuerpo_mensaje .= "Correo Electrónico: " . $correo . "\n";
    $cuerpo_mensaje .= "Teléfono: " . $telefono . "\n";

    // Construye las cabeceras del correo
    $headers = "From: no-reply@nicolasmaquiavelo.edu.mx" . "\r\n" .
               "Reply-To: " . $correo . "\r\n" .
               "X-Mailer: PHP/" . phpversion();

    // Intenta enviar el correo
    if (mail($destinatario, $asunto, $cuerpo_mensaje, $headers)) {
        // Si se envía con éxito, envía una respuesta JSON de éxito
        echo json_encode([
            'status' => 'success',
            'message' => 'Mensaje enviado con éxito.'
        ]);
    } else {
        // Si falla el envío, envía una respuesta JSON de error
        echo json_encode([
            'status' => 'error',
            'message' => 'Hubo un problema al enviar el mensaje. Inténtalo de nuevo más tarde.'
        ]);
    }

} else {
    // Si no es una solicitud POST, envía un error
    echo json_encode([
        'status' => 'error',
        'message' => 'Método de solicitud no permitido.'
    ]);
}
?>