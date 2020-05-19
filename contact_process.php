<?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recaptcha_response'])) {
        
        error_reporting(E_ALL);
        ini_set('display_errors','1');

        // Build POST request:
        $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptcha_secret = '6Lfn0PkUAAAAACbaJJ0fJHm1PNHU9tuzcNC_pdBx';
        $recaptcha_response = $_POST['recaptcha_response'];


        //Optención de respuesta de API de google para validar el reCaptcha
        $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
        //Se codifica respuesta de google en Json.
        $response = json_decode($recaptcha);
        echo $recaptcha;

        $mail_to = "info@local-marketing.es";
        
        # Sender Data
        $subject = ($_POST["subject"]);
        $name = str_replace(array("\r","\n"),array(" "," ") , strip_tags(trim($_POST["name"])));
        $email = filter_var(($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $phone = ($_POST["phone"]);
        $city = ($_POST["city"]);
        $option = ($_POST["option"]);
            switch ($option) {
                case '1':
                $mail_to = "info@local-marketing.es";
                    break; 
                case '2':
                $mail_to = "info@local-marketing.es";
                    break;
                case '3':
                $mail_to = "info@local-marketing.es";
                    break;
                case '4':
                $mail_to = "info@local-marketing.es";
                    break;
                }
        $message = trim($_POST["message"]);

        if (empty($name) || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($subject) || empty($message)) {

            # Set a 400 (bad request) response code and exit.
            http_response_code(400);
            echo '<p class="alert alert-warning">Por favor completa los campos necesarios.</p>';
            exit;
        }
        
       
        if ($response->score >= 0.5) {

             # Mail Content
             $content = "Name: $name\n";
             $content .= "Email: $email\n";
             $content .= "Phone: $phone\n";
             $content .= "City: $city\n";
             $content .= "Option: $option\n";
             $content .= "Message:$message\n";
 
             # email headers.
             $headers = "From: $name <$email>";
 
             # Send the email.
             $success = mail($mail_to, $subject, $content, $headers);
             if ($success) {
                 # Set a 200 (okay) response code.
                 http_response_code(200);
                 echo '<p class="alert alert-success">Gracias! Tu mensaje fue enviado.</p>';
             } else {
                 # Set a 500 (internal server error) response code.
                 http_response_code(500);
                 echo '<p class="alert alert-warning">Oops! Algo salió mal, revisa de nuevo.</p>';
             }
             
        } else {
            echo '<p class="alert alert-warning">Por favor presiona el captcha.</p>';    
         }


    } else {
        # Not a POST request, set a 403 (forbidden) response code.
        http_response_code(403);
        echo '<p class="alert alert-warning">Hay algún problema en tu registro, inténtalo de nuevo.</p>';
    }

?>