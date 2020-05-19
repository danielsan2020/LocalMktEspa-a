<?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // var_dump($_POST);
        
        access
        $secretKey = '6LdXY_kUAAAAAMIWUHJksadB2V5cgQT5T44K3bjf';       
        $captcha = $_POST['token'];
        if(!$captcha){
          echo '<p class="alert alert-warning">Por favor presiona el captcha.</p>';
          exit;
        }

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

        $ip = $_SERVER['REMOTE_ADDR'];

        $url = "https://www.google.com/recaptcha/api/siteverify";
        $data = [
            'secret' => $secretKey,
            'response' => $_POST['token'],
            'remoteip' => $ip
        ];

        $options = array(
            'http' => array(
              'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
              'method'  => 'POST',
              'content' => http_build_query($data)
            )
          );

        $context  = stream_context_create($options);
        $response = file_get_contents($url, false, $context);

       $responseKeys = json_decode($response, true);
       

        if($responseKeys['success'] != true) {
          echo '<p class="alert alert-warning">Por favor presiona el captcha.</p>';
        } else {

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
        
        
         }


    } else {
        # Not a POST request, set a 403 (forbidden) response code.
        http_response_code(403);
        echo '<p class="alert alert-warning">Hay algún problema en tu registro, inténtalo de nuevo.</p>';
    }

?>