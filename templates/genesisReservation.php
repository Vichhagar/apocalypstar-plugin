<!-- <h1>genesisReservation()</h1> -->
<?php
require_once "header.php";
$icon_003 = file_get_contents(plugin_dir_url(dirname(__FILE__, 1)) . '/assets/img/icons/icon_003_previous.svg');
$icon_004 = file_get_contents(plugin_dir_url(dirname(__FILE__, 1)) . '/assets/img/icons/icon_004_next.svg');

// $this -> plugin_url . '/assets/style.css'
// plugin_dir_url(dirname(__FILE__, 2))

use \Inc\Base\Calendars;
use \Inc\Base\Session;


$calendars = new calendars();
$session = new session();

/** On Supprime toute les réservations pré-reserver et non payer par les utilisateurs dans un espace de 10 Minutes */
$calendars->deleletReservednonPayout();


/** Vérifie que le calendrier de date ne soit pas vide */
$dateReserved = $calendars->ReservedisEmphty();


$year = date('Y');
$month = date("m");
$days = str_replace(0, 7, date("w"));
$jour = null;


/** Suite de Condition pour commencer la semaine à partir du Jours J, dans l'entet du Calendrier */
$DaysWeek = $calendars->daysCurrent($days);

// Afficher la semaine précédante
// Show previous week
if (isset($_GET['jour']) and $_GET['week'] == 'pre') {
    $jour = intval($_GET['jour']);
    $jour = $jour - 7;
} 

// Afficher la semaine suivante
// Show next week
elseif (isset($_GET['jour']) and $_GET['week'] == 'next') {
    $jour = intval($_GET['jour']);
    $jour = $jour + 7;
}


/**  $dateCurrent Date de Début et de Fin de Semain **/
$dateCurrent = date("d/m/Y", mktime(0, 0, 0, date("n"), date("d") + $jour, date("y")));
$dateEnd = date("d/m/Y", mktime(0, 0, 0, date("n"), date("d") + $jour + 6, date("y")));
$month = date("F", mktime(0, 0, 0, date("n"), date("d") + $jour, date("y")));
$year = date("Y", mktime(0, 0, 0, date("n"), date("d") + $jour, date("y"))); ?>

<!-- On Récupère la Valeur du Jours du Samedi  du Vendredi Après Midi, du Lundi et du Dimanche -->
<input type="hidden" id="hidden_monday" value="<?= $calendars->hideMonday($days) ?>">
<input type="hidden" id="hidden_friday" value="<?= $calendars->hideFridayafternoon($days) ?>">
<input type="hidden" id="hidden_saturday" value="<?= $calendars->hideSaturday($days) ?>">
<input type="hidden" id="show_sunday" value="<?= $calendars->showSunday($days) ?>">


<title>Réservation d'escape game à Gérardmer - Apocalypstar.com</title>
<meta name="description"
      content="Vous souhaitez réserver une session d'escape game ? N'hésitez pas à utiliser notre système en ligne de réservation  pour commencer une nouvelle aventure divertissante et ludique !">


<!-- BLOC HEADER DOND Medium Blue -->

<!--Title-->
    <!-- <div class="banner-calendars bannerCalHeight separatorAfter backgroundMediumBlue"> -->
<!--        <h2 class="inlineBlock titleGold">Réservez votre Session du Jeu Genesis (7+)</h2>-->
    </div>
<!-- END Title-->
<!-- backgroundDarkBlue -->
<div class="container container_tableau_form ">

    <div class="blockCalendar framedBlock4">
        <strong style="text-align: center"><?php $session->flash(); ?></strong>


        <!-- Entete Calendrier -->
        <!-- Calendar Header -->
        <div class="entete-calendars backgroundGreen separatorAfter2">
            <div class="flexWrapper flexCalendarNav">
                <?php if (time() < mktime(0, 0, 0, date("n"), date("d") + $jour, date("y"))) {?>
                    <a class="calPrevious" href="index.php?p=genesis_reservatino&amp;week=pre&amp;jour=<?= $jour ?>"><?= $icon_003 ?></a>
                <?php } else { ?>
                    <a class="calPreviousBlocked" href="#"><?= $icon_003 ?></a>
                <?php  } ?>
                <div>
                    <h2><?= $calendars->monthsen2fr($month) . ' ' . $year ?></h2>
                    <h4 class=inlineBlock>
                        Semaine du <?= $dateCurrent . ' au ' . $dateEnd ?>
                    </h4>
                </div>
                <a class="calNext" href="index.php?p=genesis_reservation&amp;week=next&amp;jour=<?= $jour ?>"><?= $icon_004 ?></a>
            </div>
        </div>


        <!-- Corps  Calendrier -->
        <div class="divScroll">
        <table>
            <!-- Jours de la Semaine -->
            <tr>
                <?php foreach ($DaysWeek as $nbJour => $days): ?>
                    <th class="jour_<?= $nbJour ?>">
                        <?= $days . ' ' . $daysCurrent = date("d/m", mktime(0, 0, 0, date("n"), date("d") + $nbJour + $jour)) ?>
                    </th>
                <?php endforeach; ?>
            </tr>

            <!-- Horaire de la Semaine -->
            <?php foreach ($calendars->calendars_genesis() as $dateHoraire => $horaire): ?>
                <tr>
                    <?php foreach ($horaire as $nbJour => $horaires): ?>
                        <?php $daysCurrent = date("d/m/Y", mktime(0, 0, 0, date("n"), date("d") + $nbJour + $jour)) ?>


                        <!-- Si la réservation et Effectué le Jour meme -->
                        <?php if (in_array($daysCurrent . ' ' . $horaires, $dateReserved)) { ?>
                            <td data-date="<?= $daysCurrent ?>"
                                data-hour="<?= $horaires ?>"
                                class="indisponible horaire_<?= $dateHoraire ?> jour_<?= $nbJour ?>"> <?= $horaires . '<br>' . 'INDISPONIBLE' ?>
                            </td>


                        <?php } elseif ($nbJour == $jour) { ?>
                            <td
                                    onmouseover="$(this).popover('show');"
                                    onmouseleave="$(this).popover('hide');" data-container="body"
                                    data-toggle="popover"
                                    data-placement="right" data-html="true" data-content="
                                             <b>Réservation Uniquement par Téléphone : </b> <br />
                                                Contacter-Nous au +33 7 88 60 00 24"
                                    class=" today horaire_<?= $dateHoraire ?> jour_<?= $nbJour ?>"> <?= $horaires . '<br>' . 'TELEPHONE' ?>
                            </td>


                            <!-- Sinon on rend les Jours réservables  -->
                        <?php } else {
                            ; ?>
                            <td data-date="<?= $daysCurrent ?>"
                                data-hour="<?= $horaires ?>"
                                class="reserved cliquable horaire_<?= $dateHoraire ?> jour_<?= $nbJour ?>"> <?= $horaires . '<br>' . 'DISPONIBLE' ?>
                            </td>
                        <?php } ?>

                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </table>
        <input type=hidden id=nb_days_php_to_js value="<?= $calendars->viewDaysCalendars(); ?>">
        </div>
    <!-- <div class="decorFull framedBlock4"></div> !-->
    </div>


    <div class="block_reservation backgroundMap framedBlock4">

        <form class="form-horizontal" id="form_reserved_user" method="post" action="<?= plugin_dir_url(dirname(__FILE__, 1)) . '/templates/traitementreserved.php' ?>" name="formUser" target="POPUPW" onsubmit="POPUPW = window.open('about: blank','POPUPW', 'width=1333, height=910');">

            <div style="clear: both">
                <img class="titleImg" src="<?= plugin_dir_url(dirname(__FILE__, 1)) . '/assets/img/Macaron-web-genesis.png' ?>" width="100px">
                <!-- /assets/assets/img/Macaron-web-genesis.png -->
                <legend class="titleBrown">Vos Informations</legend>

                <div class="col-md-6 col-xs-12 paddingLeftZero">
                    <input type="text" class="form-control" placeholder="Nom *" id="name" name="name">

                </div>

                <div class="col-md-6 col-xs-12 paddingRightZero">
                    <input type="text" class="form-control" placeholder="Prénom *" id="firstname" name="firstname">

                </div>

                <div class="col-md-6 col-xs-12 paddingLeftZero">
                    <input type="email" class="form-control" placeholder="e-mail *" id="email" name="email">

                </div>

                <div class="col-md-6 col-xs-12 paddingRightZero">
                    <input type="tel" class="form-control" placeholder="Téléphone *" id="phone" name="phone">

                </div>

                <input type="hidden" name="level" value="">
                <input type="hidden" name="number" value="">
                <input type="hidden" name="days" value="">
                <input type="hidden" name="hour" value="">
                <input type="hidden" name="room" value="1">
                <br>


                <div id="personne" class="personne row" style="margin-left: 0; margin-top: 10px">
                    <legend class="titleBrown clearBoth">Nombre de Joueurs</legend>
                    <div class="btn btn-default col-md-6 col-xs-12 number" data-number="2"> 2 Joueurs = 90 €</div>
                    <div class="btn btn-default col-md-6 col-xs-12 number" data-number="3"> 3 Joueurs = 100€</div>
                    <div class="btn btn-default col-md-6 col-xs-12 number" data-number="4"> 4 Joueurs = 110€</div>
                    <div class="btn btn-default col-md-6 col-xs-12 number" data-number="5"> 5 Joueurs = 120€</div>
                    <div class="btn btn-default col-md-6 col-xs-12 number" data-number="6"> 6 Joueurs = 130€</div>

                </div>
            </div>

            <div class="cgu col-md-12">
                <label style="font-size: 0.9em" class="term_condition" onmouseover="$(this).popover('show');" for="acceptation"
                       onmouseleave="$(this).popover('hide');" data-container="body" data-toggle="popover"
                       data-placement="top" data-html="true" data-content="
                       <b>Termes et Conditions:</b> <br />
                       - Arriver sobre pour participer à ce jeu. <br />
                       - Merci de prévoir votre arrivée 10 minutes en avance. <br />
                       - Une fois votre réservation validée, il n'est pas possible de l'annuler ou d'être remboursé. <br />
                       - Ce jeu n'est pas accessible aux personnes claustrophobe, ou lourdement handicapée sur le plan physique. <br />
                       - L'escape game est accessible aux enfants à partir de 7ans, néamoins, au moins un accompagnant adulte doit faire partie de l'équipe <br />">
                    J'ai lu et accepté les conditions générales <input type="checkbox" id="acceptation">
                </label>
            </div>
            <br>
            <div style="text-align: center; width: 100%">
                <button type="submit" class="button_reserved btnResa" name="send"> Réserver et Payer !</button>
            </div>

        </form>
    </div>
</div>
<!-- <div class="banner-calendars bannerCalHeight separatorAfter backgroundMediumBlue"> -->
