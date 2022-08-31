<?php
namespace Inc\Base;


class Session {

    /* Initialisation de la Session */
    public function __construct (){
        if(!isset($_SESSION)) {
            session_start();
        }
    }
    
    /*Redirect page d'administration : True */
    public function redirect($page){
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: index.php?p=$page");
        exit();
    }


    /* Redirection sur l'acceuil */
    public function redirectHome(){
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: /");
        exit();
    }

    
    /*  Vérifie la session Admin */
    public function auth(){
        // global $session;
        // if (!isset($_SESSION['admin'])){
        //     $session->redirect('auth');
        // }
    }

    /*  Si la session admin existe on redirige */
    public function auth_admin(){
        global $session;
        if (isset($_SESSION['admin'])){
            $session->redirect('genesis');
        }
    }

    /* On déclare le Message Flash */
    public function setFlash($message, $type) {
        $_SESSION['flash'] = array(
            'message' => $message,
            'type' => $type
        );
    }

    /* On affiche le Message Flash */
    public function flash() {
        if (isset($_SESSION['flash'])) { ?>
            <div class="alert alert-<?= $_SESSION['flash']['type'];?> alert-dismissible" role="alert" >
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?= $_SESSION['flash']['message'] ?></div>
            <?php unset ($_SESSION['flash']);
        }
    }
    
    /*Déconnexion */
    public function logout (){
        unset($_SESSION['admin']);
    }


    /* Vérification du Nombre de Réservation  */
    public function verif_IP(){

        if(!isset($_SESSION['error_ip'])) {
            $_SESSION['error_ip'] = array();
        }

        if (count($_SESSION['error_ip']) >= 3) {
            global $reserved;
            $reserved->BanIpUser();
            $_SESSION['error_ip'] = [];

        }

        else {
            array_push($_SESSION['error_ip'],$_SERVER['REMOTE_ADDR']);
        }
    }

}