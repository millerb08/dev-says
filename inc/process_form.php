<?php
function process_form() {
    $name = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING));
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    $message = trim(filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS));
    
    if ($name == '') {
        return 'Please enter your Name';
    }
    if ($email == '' && !PHPMailer::validateAddress($email)) {
        return 'Invalid Email Address';
    }
    if (empty($message)) {
        return 'Please enter a message';
    }
    $captcha = json_decode(
        file_get_contents(
            'https://www.google.com/recaptcha/api/siteverify?secret='
            .'6Le1CUgUAAAAABGajSPED5dDGKSHC99zbjPVF6DR'
            .'&response='.$_POST['g-recaptcha-response']
        )
    );
    if ($captcha->success == false) {
          return 'Unable to verify captcha';
    }
    $email_body = '';
    $email_body .= "Name $name\n";
    $email_body .= "Email $email\n";
    $email_body .= "Message $message\n";
    
    $mail = new PHPMailer\PHPMailer\PHPMailer;

    //Tell PHPMailer to use SMTP
    $mail->isSMTP();
    //Enable SMTP debugging
    // 0 = off (for production use)
    // 1 = client messages
    // 2 = client and server messages
    $mail->SMTPDebug = 2;
    //Set the hostname of the mail server
    $mail->Host = 'smtp.gmail.com';
    // use
    // $mail->Host = gethostbyname('smtp.gmail.com');
    // if your network does not support SMTP over IPv6
    //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
    $mail->Port = 587;
    //Set the encryption system to use - ssl (deprecated) or tls
    $mail->SMTPSecure = 'tls';
    //Whether to use SMTP authentication
    $mail->SMTPAuth = true;
    //Username to use for SMTP authentication - use full email address for gmail
    $mail->Username = 'alena@teamtreehouse.com';
    //Password to use for SMTP authentication
    $mail->Password = 'ahqyzxlmobxinjfd';
    //It's important not to use the submitter's address as the from address as it's forgery,
    //which will cause your messages to fail SPF checks.
    //Use an address in your own domain as the from address, put the submitter's address in a reply-to
    $mail->setFrom('alena@teamtreehouse.com', $name);
    $mail->addReplyTo($email, $name);
    $mail->addAddress('alena+mailto@teamtreehouse.com', 'Alena Holligan');
    $mail->Subject = 'Website Contact from ' . $name;
    $mail->Body = $email_body;
    if ($mail->send()) {
        header('location:contact.php?thanks');
        exit;
    }
    return 'Mailer Error: ' . $mail->ErrorInfo;
}