<?php
namespace Inc\Base;


class Calendars extends DB
{

    /** Avant d'effectuer une réservation, on vérifie qu'aucune n'existe déjà */
    // Before making a reservation, we check that none already exist
    public function verifReserved($daysReserved, $heure_reserved)
    {
        $req = $this->pdo->query("SELECT date_reserved, heure_reserved FROM users WHERE date_reserved = '$daysReserved' AND heure_reserved = '$heure_reserved'");
        $data = $req->fetchObject();
        return $data;
    }



    /** Ajouté une Réservation  **/
    // Added a Reservation
    public function addReservedsRoom($days_1, $hour_1, $name_1, $firstname_1, $email_1, $phone_1, $level_1, $number_1, $ip, $admin_reserved, $id_room, $pre_reserved, $reference_paiement = null)
    {
        $req = $this->pdo->prepare
        ("INSERT INTO users (date_reserved, heure_reserved, create_reserved, name, firstname, email, phone, level, price, ip, admin_reserved, id_room, pre_reserved, reference_paiement) 
          VALUES (?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $req->execute(array($days_1, $hour_1, $name_1, $firstname_1, $email_1, $phone_1, $level_1, $number_1, $ip, $admin_reserved, $id_room, $pre_reserved, $reference_paiement));
    }


    /** On vérifie qu'une réservation existe bien pour la page  d'acceptation du paiement */
    // We check that a reservation exists for the payment acceptance page
    public function informationClientReserved($cmd)
    {
        $req = $this->pdo->query("SELECT rooms.name_rooms, users.id, users.name, users.firstname, users.email, users.phone, users.price, users.date_reserved, users.heure_reserved, users.reference_paiement FROM users  INNER JOIN rooms ON users.id_room  = rooms.id WHERE reference_paiement = '$cmd'");
        $data = $req->fetchObject();
        return $data;
    }

	public function getReservationClient($id){
		$req = $this->pdo->query("SELECT rooms.name_rooms, users.id, users.name, users.firstname, users.email, users.phone, users.price, users.date_reserved, users.heure_reserved, users.reference_paiement FROM users  INNER JOIN rooms ON users.id_room  = rooms.id WHERE users.id = '$id'");
        $data = $req->fetchObject();
        return $data;
	}

    /** On valide le paiement et la réservation en Base de Donnée */
    // We validate the payment and the reservation in the Database
    public function validationReserved($id_client)
    {
        $req = $this->pdo->prepare("UPDATE users SET status_paye = 1,  pre_reserved = 0 WHERE id  = ?");
        $req->execute(array($id_client));
        return $req;
    }


    /** Réservation Bloqué par Admin **/
    // Reservation Blocked by Admin
    public function addBlockRoom($days, $hour, $name, $firstname, $email, $phone)
    {
        $req = $this->pdo->prepare
        ("INSERT INTO users (date_reserved, heure_reserved, create_reserved, name, firstname, email, phone, admin_reserved, block_admin) 
          VALUES (?, ?, NOW(), ?, ?, ?, ?, ?, 1)");
        $req->execute(array($days, $hour, $name, $firstname, $email, $phone, $_SESSION['admin']->user));
    }



    /** On déclare un tableau vide et si des réservations existe on les insères dedans  **/
    // We declare an empty array and if reservations exist we insert them in it
    public function ReservedisEmphty()
    {
        $dateReservedbdd = [];
        $req = $this->pdo->query("SELECT id, DATE_FORMAT(date_reserved, '%d/%m/%Y') AS date_reserved, DATE_FORMAT(heure_reserved, '%H:%i') AS heure_reserved  FROM users");

        while ($data = $req->fetch(\PDO::FETCH_OBJ)):
            $dateReservedbdd[] = $data->date_reserved . ' ' . $data->heure_reserved;
        endwhile;

        return $dateReservedbdd;
    }


    /** Récupère les information du Visisteurs pour etre affiché dans le Back Office  */
    // Retrieves Visitor information to be displayed in the Back Office
    public function reservedBlockAdmin($daysReserved, $heure_reserved)
    {
        $req = $this->pdo->query("
                  SELECT id, DATE_FORMAT(date_reserved, '%d/%m/%Y') AS date_reserved, DATE_FORMAT(heure_reserved, '%H:%i') AS heure_reserved, 
                        create_reserved, name, firstname, email, phone, level, price, status_paye, reference_paiement, notes, admin_reserved, admin_note, block_admin
                  FROM users WHERE date_reserved  = '$daysReserved' AND heure_reserved = '$heure_reserved'");
        $data = $req->fetchObject();
        // var_dump($data);
        return $data;
    }

    /**  Récupèrer la dernière réservation effectué  **/
    // Retrieve the last reservation made
    public function lastReserved()
    {
        $req = $this->pdo->query("SELECT MAX(id) as id, id, DATE_FORMAT(date_reserved, '%d/%m/%Y') AS date_reserved, DATE_FORMAT(heure_reserved, '%H:%i') AS heure_reserved, 
                        create_reserved, name, firstname, email, phone, level, price FROM users GROUP BY id DESC ");
        $data = $req->fetchObject();
        return $data;
    }




    /** Compter le nombre de réservation effectué en fonction des rooms */
    // Count the number of reservations made according to the rooms
    // room could be 1, 2, 3
    public function stat_romm($room){
        $req = $this->pdo->query("SELECT COUNT(id) as room FROM users WHERE id_room = $room AND block_admin = 0 ");
        $data= $req->fetchObject();
        $nbTotal = $data->room;
        return $nbTotal;
    }

    /** ADMIN Supprime rune Réservation **/
    // ADMIN Deletes rune Reservation
    public function deleteReserved($id)
    {
        $req = $this->pdo->exec(" DELETE FROM users WHERE id  = '$id'");
        return $req;
    }


    /**  ADMIN Ajouté  une Note  **/
    // ADMIN Added a Note
    public function updateNote($note, $admin_note, $id)
    {
        $req = $this->pdo->prepare("UPDATE users SET notes = ?,  admin_note = ? WHERE id  = ?");
        $req->execute(array($note, $admin_note, $id));
        return $req;
    }

    /**  ADMIN Indique que la réservation et Payer   **/
    // public function payoutReserved($id)
    // {
    //     $req = $this->pdo->prepare("UPDATE users  SET status_paye = 1  WHERE id  = ?");
    //     $req->execute(array($id));
    //     return $req;
    // }
    
    /**  ADMIN Indique que la réservation et Payer   **/
    // ADMIN Indicates that the reservation and Pay
    public function payoutReserved($id,$refMsg)
    {
        $req = $this->pdo->prepare("UPDATE users  SET status_paye = 1, reference_paiement = ?  WHERE id  = ?");
        $req->execute(array($refMsg, $id));
        return $req;
    }



    public function updateDaysCalendars($nbDays)
    {
        $req = $this->pdo->prepare("UPDATE calendars  SET daysWeek = ? WHERE id = 1");
        $req->execute(array($nbDays));
        return $req;
    }


    public function viewDaysCalendars()
    {
        $req = $this->pdo->query("SELECT * FROM calendars");
        $data = $req->fetchObject();
        $nbDaysWeek = $data->daysWeek;
        return $nbDaysWeek;
    }



    /** On Bloque la Réservation pour l'utilisateur en base de donnée temporairement */
    public function BanIpUser (){
        $req = $this->pdo->prepare("INSERT INTO ban_ip (ip_user, timestamp) VALUES (?, NOW())");
        $req->execute(array($_SERVER['REMOTE_ADDR']));
        return $req;
    }



    /** On vérifie que l'IP de l'utilisateur n'est pas bloqué en Base de Donnée  */
    public function verifIPUser ($ip){
        $req = $this->pdo->query("SELECT ip_user FROM ban_ip");
        while ($data = $req->fetchObject()){
            if($data->ip_user == $ip){
                die("Suite à trop grand nombre de réservation effectué sans paiement votre adresse IP à été temporairement bannis.
                    Pour toute informations complémentaire, vous pouvez nous contacter au +33 7 88 60 00 24");

            }
        }
    }


    /** On supprime l'IP Bannis de l'utilisateur lorsque cette dernière est bloqué depuis plus de 15 Minutes */
    public function delIPUser(){
        $req = $this->pdo->exec("DELETE FROM ban_ip WHERE timestamp < DATE_SUB(NOW(), INTERVAL 15 MINUTE)");
        return $req;
    }


    /** On Supprime les réservations qui sont faite uniquement par des clients non payer dans un intervalle de temps de 10Minutes*/
    public function deleletReservednonPayout (){
        $req = $this->pdo->exec("DELETE FROM users WHERE pre_reserved = 1 AND create_reserved < DATE_SUB(NOW(), INTERVAL 10 MINUTE)");
        return $req;
    }



    /** Convertis la Date Français en Anglais */
    public function datefr2en($mydate)
    {
        list($jour, $mois, $annee) = explode('/', $mydate);
        return @date('Y-m-d', mktime(0, 0, 0, $mois, $jour, $annee));
    }

    /** Convertis la Date Anglais  en Français */
    public function dateen2fr($mydate)
    {
        list($annee, $mois, $jour) = explode('-', $mydate);
        return @date('d/m/Y', mktime(0, 0, 0, $mois, $jour, $annee));
    }


    public function monthsen2fr($month)
    {
        switch ($month) {
            case 'January' :
                $month = 'Janvier';
                break;
            case 'February' :
                $month = 'Février';
                break;
            case 'March' :
                $month = 'Mars';
                break;
            case 'April' :
                $month = 'Avril';
                break;
            case 'May' :
                $month = 'Mai';
                break;
            case 'June' :
                $month = 'Juin';
                break;
            case 'July' :
                $month = 'Juillet';
                break;
            case 'August' :
                $month = 'Août';
                break;
            case 'September' :
                $month = 'Septembre';
                break;
            case 'October' :
                $month = 'Octobre';
                break;
            case 'November' :
                $month = 'Novembre';
                break;
            case 'December' :
                $month = 'Décembre';
                break;
        }
        return $month;
    }


    public function nbJoueur($joueur)
    {
        switch ($joueur) {
            case '2' :
                $joueur = '2 Joueurs (90€)';
                break;
            case '3' :
                $joueur = '3 Joueurs (100€)';
                break;
            case '4' :
                $joueur = '4 Joueurs (110€)';
                break;
            case '5' :
                $joueur = '5 Joueurs (120€)';
                break;
            case '6' :
                $joueur = '6 Joueurs (130€)';
                break;
        }
        return $joueur;
    }


    public function convertPriceCentime($joueur)
    {
        switch ($joueur) {
            case '2' :
                $joueur = '9000';
                break;
            case '3' :
                $joueur = '10000';
                break;
            case '4' :
                $joueur = '11000';
                break;
            case '5' :
                $joueur = '12000';
                break;
            case '6' :
                $joueur = '13000';
                break;
        }
        return $joueur;
    }




    public function daysCurrent($days)
    {
        switch ($days) {
            case '1' :
                $DaysWeek = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche']; // Monday
                break;
            case '2' :
                $DaysWeek = ['Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche', 'Lundi'];
                break;
            case '3' :
                $DaysWeek = ['Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche', 'Lundi', 'Mardi'];
                break;
            case '4' :
                $DaysWeek = ['Jeudi', 'Vendredi', 'Samedi', 'Dimanche', 'Lundi', 'Mardi', 'Mercredi'];
                break;
            case '5' :
                $DaysWeek = ['Vendredi', 'Samedi', 'Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi'];
                break;
            case '6' :
                $DaysWeek = ['Samedi', 'Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];
                break;
            case '7' :
                $DaysWeek = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
                break;
        }
        return $DaysWeek;
    }


    public function hideMonday($days){
        switch ($days) {
            case '1' :
                $days = '0';
                break;
            case '2' :
                $days = '6';
                break;
            case '3' :
                $days = '5';
                break;
            case '4' :
                $days = '4';
                break;
            case '5' :
                $days = '3';
                break;
            case '6' :
                $days = '2';
                break;
            case '7' :
                $days = '1';
                break;
        }
        return $days;
    }


    public function hideFridayafternoon($days){
        switch ($days) {
            case '1' :
                $days = '4';
                break;
            case '2' :
                $days = '3';
                break;
            case '3' :
                $days = '2';
                break;
            case '4' :
                $days = '1';
                break;
            case '5' :
                $days = '0';
                break;
            case '6' :
                $days = '6';
                break;
            case '7' :
                $days = '5';
                break;
        }
        return $days;
    }


    public function hideSaturday($days){
        switch ($days) {
            case '1' :
                $days = '5';
                break;
            case '2' :
                $days = '4';
                break;
            case '3' :
                $days = '3';
                break;
            case '4' :
                $days = '2';
                break;
            case '5' :
                $days = '1';
                break;
            case '6' :
                $days = '0';
                break;
            case '7' :
                $days = '6';
                break;
        }
        return $days;
    }

    public function showSunday($days){
        switch ($days) {
            case '1' :
                $days = '6';
                break;
            case '2' :
                $days = '5';
                break;
            case '3' :
                $days = '4';
                break;
            case '4' :
                $days = '3';
                break;
            case '5' :
                $days = '2';
                break;
            case '6' :
                $days = '1';
                break;
            case '7' :
                $days = '0';
                break;
        }
        return $days;
    }

    public function name_room_reserved($num_room){
        if ($num_room == 1 ){
            $name_room = "Laboratoire du Professeur Genesis";
        }elseif ($num_room == 2){
            $name_room = "Manoir";
        }elseif ($num_room == 3){
            $name_room = "Cachot";
        }
        return $name_room;
    }


    /** Affiche le Nom de l'Admin qui effectue la réservation */
    public function admin_reserved_by()
    {
        global $client;
        if ($client->admin_reserved != null) {
            $moderation = "Réservation Effectué par : " . "<b>" . ucfirst($client->admin_reserved) . "</b>";
            return $moderation;
        } else {
            return false;
        }
    }


    /** Affiche le Nom de l'Admin qui place une Notes */
    public function notes_add_by()
    {
        global $client;
        if ($client->admin_note != null AND !empty($client->notes)) {
            $note = "Note de " . ucfirst($client->admin_note) . " : " . $client->notes;
            return $note;
        } else {
            return false;
        }
    }

    public function calendars_genesis()
    {
        return $horaireWeek = [
            ['09:00',  '09:00',   '09:00',    '09:00',     '09:00', '09:00', '09:00'],
            ['10:15',  '10:15',   '10:15',    '10:15',     '10:15', '10:15', '10:15'],
            ['11:30', '11:30',    '11:30',    '11:30',     '11:30', '11:30', '11:30'],
            ['13:30',  '13:30',   '13:30',    '13:30',     '13:30', '13:30', '13:30'],
            ['14:45',  '14:45',   '14:45',    '14:45',     '14:45', '14:45', '14:45'],
            ['16:00',  '16:00',   '16:00',    '16:00',     '16:00', '16:00', '16:00'],
            ['17:15',  '17:15',   '17:15',    '17:15',     '17:15', '17:15', '17:15'],
            ['18:30',  '18:30',   '18:30',    '18:30',     '18:30', '18:30', '18:30'],
            ['19:45',  '19:45',   '19:45',    '19:45',     '19:45', '19:45', '19:45']
        ];
    }

    public function calendars_cachot()
    {
        return $HoraireWeek = [
            ['09:30',  '09:30',   '09:30',    '09:30',     '09:30', '09:30', '09:30'],
            ['10:45',  '10:45',   '10:45',    '10:45',     '10:45', '10:45', '10:45'],
            ['12:00',  '12:00',   '12:00',    '12:00',     '12:00', '12:00', '12:00'],
            ['14:00',  '14:00',   '14:00',    '14:00',     '14:00', '14:00', '14:00'],
            ['15:15',  '15:15',   '15:15',    '15:15',     '15:15', '15:15', '15:15'],
            ['16:30',  '16:30',   '16:30',    '16:30',     '16:30', '16:30', '16:30'],
            ['17:45',  '17:45',   '17:45',    '17:45',     '17:45', '17:45', '17:45'],
            ['19:00',  '19:00',   '19:00',    '19:00',     '19:00', '19:00', '19:00'],
            ['20:15',  '20:15',   '20:15',    '20:15',     '20:15', '20:15', '20:15']
        ];
    }

    public function calendars_manoir()
    {
        return $HoraireWeek = [
            ['09:15', '09:15', '09:15', '09:15', '09:15', '09:15', '09:15'],
            ['10:30', '10:30', '10:30', '10:30', '10:30', '10:30', '10:30'],
            ['11:45', '11:45', '11:45', '11:45', '11:45', '11:45', '11:45'],
            ['13:45', '13:45', '13:45', '13:45', '13:45', '13:45', '13:45'],
            ['15:00', '15:00', '15:00', '15:00', '15:00', '15:00', '15:00'],
            ['16:15', '16:15', '16:15', '16:15', '16:15', '16:15', '16:15'],
            ['17:30', '17:30', '17:30', '17:30', '17:30', '17:30', '17:30'],
            ['18:45', '18:45', '18:45', '18:45', '18:45', '18:45', '18:45'],
            ['20:00', '20:00', '20:00', '20:00', '20:00', '20:00', '20:00']
        ];
    }




    /** Fenetre Popover d'Information sur les Informations du Visiteur **/
    public function infoVisiteur()
    {
        global $client;
        global $calendars;
        global $jour;
        global $nbJour;
        global $horaires; ?>


        <?php
        /** On Vérifie qu'une réservation existe bien en Base de Donnée **/
        
        // var_dump($client);
        if ($client->status_paye == 0){ $colorButton = 'indisponible'; }
        // if ($client->status_paye == 0 AND $client->block_admin == 0){ $colorButton = 'indisponible'; }
        elseif ($client->block_admin == 1){$colorButton = 'blockAdmin';}
        else{ $colorButton = 'indisponible_payout';}

        if (!empty($client->id)){?>
        <div class="<?= $colorButton ?> reserver"
             data-date="<?= date("d/m/Y", mktime(0, 0, 0, date("n"), date("d") - $jour  + $nbJour + 1 )) ?>"
             data-hour="<?= $horaires ?>"
             onclick="$(this).popover('show');"
             title="<b><?= ucwords($client->name . ' ' . $client->firstname) ?></b>"
             data-toggle="popover" data-trigger="click" tabindex="0"
             data-placement="auto" data-html="true" data-content="
                                     <p> Email     : <?= $client->email ?></p>
                                     <p> Téléphone : <?= $client->phone ?> </p>
                                     <p> Nombre de Joueur : <?= $calendars->nbJoueur($client->price) ?></p>
                                     <p> Réservation Créer le  : <?= $calendars->dateen2fr(substr($client->create_reserved, 0, 10)) ?></p>
                                     <p> Référence Paiement : <?= $client->reference_paiement ?> </p>
                                     <p><?= $this->notes_add_by(); ?></p>
                                     <?= $this->admin_reserved_by(); ?><p></p>


                                     <?php //if ($_SESSION['admin']->level != 3): ?>
                                     <?php if (1 != 3): ?>
                                        <button type='button' class='btn btn-danger ' name='cancel' data-toggle='modal'
                                            data-target='#modalDelete<?= $client->id ?>'>Annuler
                                        </button>


                                        <button type='button' class='btn btn-success' name='notes' data-toggle='modal'
                                            data-target='#modalNote<?= $client->id ?>'>Notes
                                    <?php endif; ?>


                                    <?php if (!empty($client->notes)):
                                            $icon = "&nbsp; <span class='glyphicon glyphicon-comment'></span>";
                                                echo $icon;
                                            else:
                                                $icon = null;
                                            endif;  ?>
                                     </button>

                                    <?php if ($client->status_paye == 0 AND $client->block_admin == 0){
                                          $message = "DOIT PAYER "; ?>

                                    <?php // if ($_SESSION['admin']->level != 3): ?>
                                    <?php if (1 != 3): ?>
                                           <form action='' method='post' style='display: inline;'>
                                                <input type='hidden' name='id' value='<?= $client->id ?>'>

                                                <button type='button' class='btn btn-success' name='payer' data-toggle='modal' data-target='#modalPayer<?= $client->id?>'>Payer</button>
                                            </form>

                                    <?php endif; ?>


                                    <?php } elseif ($client->status_paye == 1) {
                                            $message = "PAYER ";?>


                                    <?php // if ($_SESSION['admin']->level != 3): ?>
                                    <?php if (1 != 3): ?>
                                          <button type='button' class='btn a-payer' style='background-color: grey; cursor: none;' name='payer'> A Payer
                                          <?php if (!empty($client->reference_paiement) AND substr($client->reference_paiement,0,12)=="Paid by Cash"):
                                                // Paid by Cash
                                                $icon .= "&nbsp; <span class='glyphicon glyphicon-euro'></span>";
                                                echo $icon;
                                           elseif (!empty($client->reference_paiement) AND substr($client->reference_paiement,0,10)=="Paid by CB"):
                                                $icon .= "&nbsp; <span class='glyphicon glyphicon-credit-card'></span>";
                                           else:
                                               $icon .= "&nbsp; <span class='glyphicon glyphicon-ok'></span>";
                                            endif;  ?>
                                          </button>
                                    <?php endif; ?>


                                    <?php  } elseif ($client->block_admin == 1){
                                            $message = "BLOQUER ADMIN ";?>

                                    <?php  }?>">
                                        <span><?= $horaires . " <br>" . $message . $icon ?></span>


        </div>
        <?php } ?>


        <!-- Fenetre Modal NOTES AJOUTER -->
        <div class="modal fade" id="modalNote<?= $client->id ?>" tabindex="1" style="z-index: 3000;">
            <div class="modal-dialog" role="dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" style="float: left"> Insèrer votre Note</h4>
                    </div>

                    <div class="modal-footer">
                        <form action="" method="post">
                            <input type="hidden" name="id" value="<?= $client->id ?>"">
                            <input type="text" name="note" class="form-control"
                                <?php if (!empty($client->notes)) {
                                    echo "placeholder='$client->notes'";
                                } else {
                                    echo "placeholder='Ajouté la note Ici'";
                                } ?> ><br>
                            <button type="submit" class="btn btn-primary" name="notes">Ajouter la Note</button>
                        </form>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->


        <!-- Fenetre Modal  NOTES SUPPRIMER -->
        <div class="modal fade" id="modalDelete<?= $client->id ?>" tabindex="0" style="z-index: 4000;">
            <div class="modal-dialog" role="dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" style="float: left">Attention la Suppresion de cette Réservation et
                            Irréversible</h4>
                    </div>

                    <div class="modal-footer">
                        <form action="" method="post">
                            <input type="hidden" name="id" value="<?= $client->id ?>"">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-danger" name="cancel">Supprimer
                            </button>
                        </form>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        
        <!-- Fenetre Modal PAYER -->
        <div class="modal fade" id="modalPayer<?= $client->id ?>" tabindex="0" style="z-index: 4000;">
            <div class="modal-dialog" role="dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" style="float: left">Select Mode of Payment</h4>
                    </div>
        
                    <div class="modal-footer">
                        <form action="" method="post">
                            <input type="hidden" name="id" value="<?= $client->id ?>"">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-info" name="doit_payer">Pay by Cash</button>
                            <button type="submit" class="btn btn-info" name="pay_CB">Pay by CB</button>
                        </form>
                    </div>
                </div><!-- /.modal-content -->

        

    <?php }

    /** Pagniation Horaire Calendrier */
    public function weekCalendars($jour)
    {
        $req = $this->pdo->prepare("UPDATE calendars  SET jourWeek = ? WHERE id = 1");
        $req->execute(array($jour));
        return $req;
    }

    public function getweekCalendars()
    {
        $req = $this->pdo->query("SELECT jourWeek  FROM calendars");
        $data = $req->fetchObject();
        return $data;
    }


}
