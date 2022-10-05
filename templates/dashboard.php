<?php

// use \Inc\Base\Calendars;
// use \Inc\Base\Session;
// use app\calendars;
$nb_reserved = new \Inc\Base\Calendars();
// $session = new \Inc\Base\Session();
// $session->auth();
?>



<div class="form_admin" style="text-align: center">
<br><br><br><br><br>
    <h2>Statistique des Réservations pour les Diffèrentes Room's :</h2>
        <h5 class="textSize">Réservation effectué pour     <b> Genesis</b> : <?= $nb_reserved->stat_romm(1) ?></h5>
        <h5 class="textSize">Réservation effectué pour le  <b> Manoir </b> : <?= $nb_reserved->stat_romm(2) ?></h5>
        <h5 class="textSize">Réservation effectué pour le  <b> Cachot </b> : <?= $nb_reserved->stat_romm(3) ?></h5>
</div>