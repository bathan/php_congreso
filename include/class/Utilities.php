<?php

class Utilities {

    public function __construct() {
    }

    public static function isValidEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) != false;
    }

    /**
     * Generates a signed request from a data array
     *
     * @param Array $data
     * @param String $secret
     * @return String
     */
    static function generate_signed_request($data, $secret) {
        $data['algorithm'] = 'HMAC-SHA256';
        $payload = self::base64_url_encode(json_encode($data));
        $encoded_sig = self::base64_url_encode(hash_hmac('sha256', $payload, $secret, $raw = true));
        return $encoded_sig . '.' . $payload;
    }

    private static function base64_url_encode($input) {
        return strtr(base64_encode($input), '+/', '-_');
    }

    /**
     * Parse and validate a signed request and returns an array
     * with the values contained in it.
     *
     * A parameter of the decoded request must indicate the algorithm.
     *
     * @author Facebook Inc.
     * http://developers.facebook.com/docs/authentication/
     *
     * @param String $signed_request
     * @param String $secret
     * @return Array
     */
    static function parse_signed_request($signed_request, $secret) {
        list($encoded_sig, $payload) = explode('.', $signed_request, 2);

        // decode the data
        $sig = self::base64_url_decode($encoded_sig);
        $data = json_decode(self::base64_url_decode($payload), true);

        if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
            error_log('Unknown algorithm. Expected HMAC-SHA256');
            return null;
        }

        // check sig
        $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
        if ($sig !== $expected_sig) {
            error_log('Bad Signed JSON signature!');
            return null;
        }

        return $data;
    }

    private static function base64_url_decode($input) {
        return base64_decode(strtr($input, '-_', '+/'));
    }

    public static function populateClassFromArray(&$class,$array) {

        foreach($array as $key=>$value) {
            if(property_exists($class,$key)) {

                $class->$key = $value;
            }
        }
    }

    public static function purifyQueryString(Array $valuesToRemove) {

        $query_string = $_SERVER['QUERY_STRING'];
        parse_str($query_string,$query_string_array);

        $new_array = [];

        foreach($query_string_array as $k=>$v) {
            if(!in_array($k,$valuesToRemove)) {
                $new_array[] = $k."=".$v;
            }
        }

        return implode('&',$new_array);

    }

    public static function sendEmail($email_to,$name_to,$body_html,$body_plain=null,$subject) {
        $mail = new \PHPMailer();

        //$mail->SMTPDebug = 3;                               // Enable verbose debug output

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = _SMTP_SERVER;  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = _SMTP_USER_NAME;                 // SMTP username
        $mail->Password = _SMTP_USER_PASS;                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 25;                                    // TCP port to connect to

        $mail->From = _EMAIL_FROM;
        $mail->FromName = _EMAIL_FROM_NAME;
        $mail->addAddress($email_to, $name_to);     // Add a recipient
        $mail->isHTML(true);                                  // Set email format to HTML

        $body_plain = utf8_encode($body_plain);

        $mail->Subject = $subject;
        $mail->Body    = $body_html;
        if($body_plain){
            $mail->AltBody = $body_plain;
        }

        if(!$mail->send()) {
            throw new Exception($mail->ErrorInfo);
        } else {
            return true;
        }

    }

}