<?php
use \Inc\Base\Session;

require '../inc/Base/AdminUsers.php';
$admin = new \Inc\Base\AdminUsers;

require '../inc/Base/DB.php';



    if (!empty($_POST)) {

        include '../inc/Base/Calendars.php';
        require '../inc/Base/Session.php';
        $reserved = new \Inc\Base\Calendars;
        $session = new Session();


        $name_1 = htmlentities($_POST['name']);
        $firstname_1 = htmlentities($_POST['firstname']);
        $email_1 = htmlentities($_POST['email']);
        $phone_1 = htmlentities($_POST['phone']);
        $level_1 = htmlentities($_POST['level']);
        $number_1 = (int)htmlentities($_POST['number']);
        $days_1 = $reserved->datefr2en(htmlentities($_POST['days']));
        $hour_1 = htmlentities($_POST['hour']);
        $ip = $_SERVER['REMOTE_ADDR'];
        $room = $_POST['room'];
        $readmin = $_POST['admin'];
		$reference_paiement = null;



        /** Tester si une réservation existe déjà */
        $data = $reserved->verifReserved($days_1, $hour_1);
        if ($data == true) {
            $session->setFlash('Cette Date vient d\'etre reservé, Merci de bien vouloir en choisir une autre', 'danger');
            exit();
            // header('Location:' . $_SERVER['HTTP_REFERER']);
        }

        // if (isset($admin)) {
            
        if (isset($readmin)) {
            $admin_reserved = $readmin;

        } else {
            $admin_reserved = null;
        }

        /**  $cmd: Aucune référence lors de la réservation par un Administrateur */
        $reserved->addReservedsRoom($days_1, $hour_1, $name_1, $firstname_1, $email_1, $phone_1,  $level_1, $number_1, $ip, $admin_reserved ,  $room, 2, 0, $reference_paiement);

    }  else {
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: /");
        exit();
    }









