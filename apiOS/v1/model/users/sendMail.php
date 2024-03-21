<?php

class sendMail{

    public static function sendConfirmationOrderMail($fromMail,$toMail,$subjectMail,$msgMail,$orderId) {

        $from = $fromMail;
        $to = $toMail;
        $subject = $subjectMail." " . $orderId;
    
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: " . $from;
    
        mail($to, $subject, $msgMail, $headers);

    }
    public static function sendConfirmationOrderCodeMail($fromMail,$toMail,$subjectMail,$msgMail) {

        $from = $fromMail;
        $to = $toMail;
        $subject = $subjectMail;
    
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: " . $from;
    
        mail($to, $subject, $msgMail, $headers);

    }

}

?>