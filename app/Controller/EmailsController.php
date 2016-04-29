<?php
class EmailsController extends AppController {
	
public function send_mail($receiver = 'tharangalakma90@gmail.com', $name = 'tharanga', $pass = '1990dilantha') {
        $confirmation_link = "http://" . $_SERVER['HTTP_HOST'] . $this->webroot . "users/login/";
        //$message = 'Hiii,' . $name . ', Your Password is: ' . $pass;
		$message = 'Hi machn';
        App::uses('CakeEmail', 'Network/Email');
        $email = new CakeEmail('gmail');
        $email->from('lakmalichathu91@gmail.com');
        $email->to($receiver);
        $email->subject('Mail Confirmation');
        $email->send($message . " " . $confirmation_link);
    }
	}
?>