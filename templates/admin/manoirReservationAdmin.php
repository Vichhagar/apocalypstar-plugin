<h1>manoirReservation()</h1>
<p>shortcode: <code>[manoir_reservation]</code></p>

<?php

require_once "adminHeader.php";
use \Inc\Base\Calendars;
use \Inc\Base\AdminUsers;
// use \Inc\Base\Session;
date_default_timezone_set('Europe/Paris');


/** Vérification de la Session Admin **/
// $session = new Session();
// $session->auth();

$calendars = new calendars();
$user = new AdminUsers();

global $client;


/** On Supprime toute les réservations pré-reserver et non payer par les utilisateurs dans un espace de 10 Minutes */
$calendars->deleletReservednonPayout();


/**  Vérifie que le calendrier de date ne soit pas vide, et récupèrer le tableau contenant toute les dates */
$dateReserved = $calendars->ReservedisEmphty();



if (isset($_POST['cancel'])) {
    $id = (int)$_POST['id'];
    $calendars->deleteReserved($id);
    // header('Location:' . $_SERVER['HTTP_REFERER']);
    echo '<script type="text/javascript"> document.location.reload(true); </script>';
}


if (isset($_POST['notes'])) {
    $id = (int)$_POST['id'];
    $note = ucfirst(htmlentities($_POST['note']));
    $calendars->updateNote($note, $user->getUserName(), $id);
    
}


if (isset($_POST['doit_payer'])) {
    $id = (int)$_POST['id'];
    $refMsg = "Paid by Cash" . " " . date('d/m/Y H:i:s');
    $calendars->payoutReserved($id,$refMsg);
    // header('Location:' . $_SERVER['HTTP_REFERER']);
}

if (isset($_POST['pay_CB'])) {
    $id = (int)$_POST['id'];
    $refMsg = "Paid by CB" . " " . date('d/m/Y H:i:s');
    $calendars->payoutReserved($id,$refMsg);
    // header('Location:' . $_SERVER['HTTP_REFERER']);
}


if (isset($_POST['blockSession'])) {
    $days = $calendars->datefr2en(htmlentities($_POST['days']));
    $hour = $_POST['hour'];
    $name = "Bloqué";
    $firstname = "Bloqué";
    $email = "Bloqué";
    $phone = "Bloqué";
    $calendars->addBlockRoom($days, $hour, $name, $firstname, $email, $phone);
    // header('Location:' . $_SERVER['HTTP_REFERER']);
    echo '<script type="text/javascript"> document.location.reload(true); </script>';
}


$year = date('Y');
$month = date("m");
$days = str_replace(0, 7, date("w"));
$jour = null;

/** Suite de Condition pour commencer la semaine à partir du Jours J, dans l'entet du Calendrier */
$DaysWeek = $calendars->daysCurrent($days);


// Afficher la semaine précédante
if (isset($_GET['jour']) and $_GET['week'] == 'pre') {
    $jour = intval($_GET['jour']);
    $jour = $jour - 7;
}
// Afficher la semaine suivante
elseif (isset($_GET['jour']) and $_GET['week'] == 'next') {
    $jour = intval($_GET['jour']);
    $jour = $jour + 7;
}


/**  $dateCurrent Date de Début et de Fin de Semain **/
$dateCurrent = date("d/m/Y", mktime(0, 0, 0, date("n"), date("d") + $jour, date("y")));
$dateEnd = date("d/m/Y", mktime(0, 0, 0, date("n"), date("d") +$jour + 6, date("y")));
$month = date("F", mktime(0, 0, 0, date("n"), date("d") + $jour, date("y")));
$year = date("Y", mktime(0, 0, 0, date("n"), date("d") + $jour, date("y")));

?>

<!-- On Récupère la Valeur du Jours du Samedi  du Vendredi Après Midi, du Lundi et du Dimanche -->
<input type="hidden" id="hidden_monday" value="<?= $calendars->hideMonday($days) ?>">
<input type="hidden" id="hidden_friday" value="<?= $calendars->hideFridayafternoon($days) ?>">
<input type="hidden" id="hidden_saturday" value="<?= $calendars->hideSaturday($days) ?>">
<input type="hidden" id="show_sunday" value="<?= $calendars->showSunday($days) ?>">


<div class="row row-no-gutters containerContent">
    <div class="col-md-12 col-sm-12 col-xs-12" style="text-align: center;">


        <!-- AVANCER DANS LES SEMAINES  -->
        <h2>Manoir - <?= $calendars->monthsen2fr($month) . ' ' . $year ?></h2>
        <h4>
            <a href="admin.php?page=apocalypstar_manoir&amp;week=pre&amp;jour=<?= $jour ?>"><img src="<?= plugin_dir_url(dirname(__FILE__, 2)) . 'assets/img/arrow2.png' ?>"height="30px"></a>
                Jour du: <?= $dateCurrent . ' au ' . $dateEnd ?>
            <a href="admin.php?page=apocalypstar_manoir&amp;week=next&amp;jour=<?= $jour ?>"><img src="<?= plugin_dir_url(dirname(__FILE__, 2)) . 'assets/img/arrow.png' ?>"  height="30px"></a>
        </h4>
    </div>


    <table  id="calendars" class="calendrier_admin col-md-12 col-sm-12 col-xs-12">
        <!-- Jours de la Semaine -->
        <thead>
        <tr>
            <?php foreach ($DaysWeek as $nbJour => $days): ?>
                <th class="days jour_<?= $nbJour ?>">
                    <?= $days . ' ' . $daysCurrent = date("d/m", mktime(0, 0, 0, date("n"), date("d") + $nbJour + $jour)) ?>
                </th>
            <?php endforeach; ?>
        </tr>
		</thead>
		<tbody>
        <!-- Horaire de la Semaine -->
        <?php foreach ($calendars->calendars_manoir() as $dateHoraire => $horaire): ?>
            <tr>
                <?php foreach ($horaire as $nbJour => $horaires): ?>

                    <?php $daysCurrent = date("d/m/Y", mktime(0, 0, 0, date("n"), date("d") + $nbJour + $jour)) ?>

                    <!-- Si la date est présente dans le tableau et donc réservé -->
                    <?php if (in_array($daysCurrent . ' ' . $horaires, $dateReserved)) { ?>
                        <?php $client = $calendars->reservedBlockAdmin(date("Y-m-d", mktime(0, 0, 0, date("n"), date("d")  + $nbJour + $jour)), $horaires); ?>
                        <td class="hour horaire_<?= $dateHoraire ?> jour_<?= $nbJour ?>"><?php   $calendars->infoVisiteur() ?></td>


                        <?php } else { ?>
                            <td data-date="<?= $daysCurrent ?>"
                                data-hour="<?= $horaires ?>"
                                class="hour cliquable horaire_<?= $dateHoraire ?> jour_<?= $nbJour ?>"> <?= $horaires . '<br>' . 'DISPONIBLE' ?>
                            </td>
                        <?php } ?>

                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <input type=hidden id=nb_days_php_to_js value="<?=  $calendars->viewDaysCalendars(); ?>">
</div>

<?php //if ($_SESSION['admin']->level != 3): ?>

<div class="row">
    <div class="col-md-0 col-md-offset-10">
        <form action="" method="post" id="form_block_admin" name="formUser" class="form_block">
            <input type="hidden" name="days" value="">
            <input type="hidden" name="hour" value="">

            <button type="submit" class="button_block" name="blockSession"> Bloquer cette Session </button>
        </form>
    </div>
</div>

<div class="row">
    <div class="container_form_reservation">
        <form class="form_reserved" id="form_reserved_admin">

            <fieldset>
                <legend>Renseigner une Réservation :</legend>
            </fieldset>

            <div class="form-group form_calendars_div col-md-6 col-sm-12 col-xs-12">
                <input class="form-control form_calendars" id="name"       placeholder="Nom"    name="name">
                <input class="form-control form_calendars" id="firstname"  placeholder="Prénom" name="firstname">

            </div>

            <div class="form-group form_calendars_div col-md-6 col-sm-12 col-xs-12">
                <input class="form-control form_calendars" id="email" placeholder="Email"     name="email">
                <input class="form-control form_calendars" id="phone" placeholder="Téléphone" name="phone">
            </div>
<?php /*            
            <div class="form-group form_calendars_div col-md-6 col-sm-12 col-xs-12" id="niveau">
                <fieldset>
                    <legend>Niveau de Difficulté : *</legend>
                </fieldset>
				<div class="btn btn-default col-md-6 col-xs-12 level" data-level="0"> Mode normal</div>
				<div class="btn btn-default col-md-6 col-xs-12 level" data-level="1"> Mode difficil</div>
            </div>
 */ ?>
            <div class="form-group form_calendars_div col-md-12 col-sm-12 col-xs-12" id="personne">
                <fieldset>
                    <legend>Nombre de Joueurs :</legend>
                </fieldset>
                <div class="btn btn-default col-md-4 col-sm-12 col-xs-12 number"  data-number="2"> 2 Joueurs = 90€</div>
                <div class="btn btn-default col-md-4 col-sm-12 col-xs-12 number"  data-number="3"> 3 Joueurs = 100€</div>
                <div class="btn btn-default col-md-4 col-sm-12 col-xs-12 number"  data-number="4"> 4 Joueurs = 110€</div>
                <div class="btn btn-default col-md-4 col-sm-12 col-xs-12 number"  data-number="5"> 5 Joueurs = 120€</div>
                <div class="btn btn-default col-md-4 col-sm-12 col-xs-12  number" data-number="6"> 6 Joueurs = 130€</div>
            </div>
            <input id="room" type="hidden" name="room" value="2">
            <input id="admin" type="hidden"  name="admin" value="<?php echo $user->getUserName() ?>">

            <button type="submit" class="btn btn-default button_reserved"> Effectuer la Réservation</button>
        </form>
    </div>
</div>

<?php //endif; ?>
