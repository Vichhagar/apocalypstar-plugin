<?php
// require 'testsign.php';

// use \Inc\Base\Calendars;

use \Inc\Base\Session;
require '../inc/Base/DB.php';
// include_once '../inc/Base/PaypalExpress.php'; 

include_once dirname(__FILE__, 2) . '/inc/Base/PaypalExpress.php'; 

// die(dirname(__FILE__, 2) . '/inc/Base/PaypalExpress.php');
// <?= plugin_dir_url(dirname(__FILE__, 1)) . '/templates/traitementreserved.php'
// require_once '../inc/Base/PaypalExpress.php'; 
include '../inc/Base/Calendars.php';
$reserved = new \Inc\Base\Calendars();
require '../inc/Base/Session.php';
$session = new Session();
$paypal = new PaypalExpress;




if (!empty($_POST)) {

    if (!empty($_POST['name'] && $_POST['firstname'] && $_POST['email']) &&  $_POST['phone'] &&  $_POST['number'] &&  $_POST['days'] &&  $_POST['hour'] ) {
        $name_1 = htmlentities($_POST['name']);
        $firstname_1 = htmlentities($_POST['firstname']);
        $email_1 = htmlentities($_POST['email']);
        $phone_1 = htmlentities($_POST['phone']);
        $level_1 = (int)htmlentities($_POST['level']);
        $number_1 = (int)htmlentities($_POST['number']);
        $days_1 = $reserved->datefr2en(htmlentities($_POST['days']));
        $hour_1 = htmlentities($_POST['hour']);
        $dateCreateReserved = date('d/m/Y H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'];
        $room = $_POST['room'];
        $num_room = $reserved->name_room_reserved($room);
        $admin_reserved = null;

        /** Tester si une réservation existe déjà */
        $data = $reserved->verifReserved($days_1, $hour_1);
        if ($data == true) {
            $session->setFlash('Cette Date vient d\'etre reservé, Merci de bien vouloir en choisir une autre', 'danger');
            header('Location:' . $_SERVER['HTTP_REFERER']);
            exit();
        }

        /** On permet à nouveau à l'utilisateur de reservé après 15Minutes de Blocage */
        $reserved->delIPUser();


        /** On vérifie que l'IP n'est pas bannis suite à un nombre abusive de réservation */
        $reserved->verifIPUser($_SERVER['REMOTE_ADDR']);



        /** On Ajoute l'adresse IP de l'utilisateur à chaque réservation sans paiement  */
        $session->verif_IP();



        /** On enregistre la réservation en base de donnée  */
        $cmd = $name_1 . '-' . $firstname_1 . '-' .$dateCreateReserved;
        $reserved->addReservedsRoom($days_1, $hour_1, $name_1, $firstname_1, $email_1, $phone_1, $level_1, $number_1, $ip, $admin_reserved, $room, 1, $cmd);



    } else {
        header("HTTP/1.1 301 Moved Permanently");
        header('Location:' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}
else {
    $session->redirectHome();
}

/** On Récupère le Prix et on le Convertis en Centime **/
$convertPrice = (float)$reserved->convertPriceCentime($number_1);

/** On Récupère le Prix et en Euro **/
$priceEuro = substr($convertPrice, 0, -2);

/** On convertit la date en Français **/
$dateFrench = $reserved->dateen2fr($days_1);

$lastReserved = $reserved->lastReserved();

/**  ------- E-TRANSACTION PRODUCTION  ------- **/




/*
 * On Réupère le Prix
 * On le Convertis en Centime
 * On créer une référence unique basé sur l'ID, Nom, Prénom, Date de Réservation
 */

$prix = $lastReserved->price;
$convertPrice = $reserved->convertPriceCentime($prix);

/**  --------------- VARIABLES de E-Transaction et  INFORMATION du CLIENT  --------------- */
$pbx_site = '2275733';
$pbx_rang = '01';
$pbx_identifiant = '983988740';
$pbx_cmd = $cmd;
$pbx_porteur = $lastReserved->email;
$pbx_total = $convertPrice;


/*
if ($_SERVER['SERVER_ADDR'] == '127.0.0.1' || $_SERVER['SERVER_ADDR'] == 'localhost') {
    // Param�trage des urls de redirection apr�s paiement
    $domaine = "http://apocalypstar.test";
    $serveurs = array('preprod-tpeweb.e-transactions.fr');

    //---- Clé  Pré Producion ---
    $keyTest = 'BDA79B765FF361855343EF1F8B058BDD5C3B24F4FA25C0FCC23C4630EE77C6A0F5378A326C43D204EDB9FD2ACFA25E105F54A5E498F1A241A81DD83C7A81912B';
}

else if ($_SERVER['SERVER_PORT'] == '8080'){
    // Param�trage des urls de redirection apr�s paiement
    $domaine = "http://dev.apocalypstar.fr:8080";
    $serveurs = array('tpeweb.e-transactions.fr');

    //---- Clé Production ---
    $keyTest = "4F9A87EDB0AA3F5C950BEA2AA9F38458230407CE5575F0D0CFD7450FE97F4E57E7A60D9AF46BC2A98D22CD573995421B0BB30CEBCDD6A302777226AA1AD1AEE7";

}

else if ($_SERVER['SERVER_PORT'] == '443'){
    // Param�trage des urls de redirection apr�s paiement
    $domaine = "https://apocalypstar.com";
    $serveurs = array('tpeweb.e-transactions.fr');

    //---- Clé Production ---
    $keyTest = "4F9A87EDB0AA3F5C950BEA2AA9F38458230407CE5575F0D0CFD7450FE97F4E57E7A60D9AF46BC2A98D22CD573995421B0BB30CEBCDD6A302777226AA1AD1AEE7";
}

*/

$domaine = "https://apocalypstar.com";
$serveurs = array('tpeweb.e-transactions.fr');

$keyTest = "4F9A87EDB0AA3F5C950BEA2AA9F38458230407CE5575F0D0CFD7450FE97F4E57E7A60D9AF46BC2A98D22CD573995421B0BB30CEBCDD6A302777226AA1AD1AEE7";



$pbx_effectue = "$domaine/index.php?p=accepte";
$pbx_annule = "$domaine/index.php?p=annule";
$pbx_refuse = "$domaine/index.php?p=refuse";


// Param�trage de l'url de retour back office site
$pbx_repondre_a = 'http://www.votre-site.extention/page-de-back-office-site';

// Param�trage du retour back office site
$pbx_retour = 'Mt:M;Ref:R;';



/** --------------- TESTS DE DISPONIBILITE DES SERVEURS --------------- **/
foreach($serveurs as $serveur)
{
    $doc = new DOMDocument();
    $doc->loadHTMLFile('https://'.$serveur.'/load.html');
    $server_status = "";
    $element = $doc->getElementById('server_status');

    if($element)
    {
        $server_status = $element->textContent;
    }
    if($server_status == "OK")
    {
        /** $serveurOK: Si serveur opérationnel, on indique l'adresse du Serveur à Contacter */
        $serveurOK = $serveur;
        break;
    }
    // else : La machine est disponible mais les services ne le sont pas.
}
//curl_close($ch); <== voir paybox

if(!$serveurOK)
{
    /** Si aucun serveur ne répond on stoppe la procèdure */
    die("Erreur : Aucun serveur n'a été trouvé");
}


/** --------------- FIN DE TESTS DE DISPONIBILITE DES SERVEURS --------------- **/
$serveurOK = 'https://'.$serveurOK.'/php/';

/** @var  $dateTime: Format de Date IOS 8601 */
$dateTime = date("c");

/** @var: $msg  Contient la signature unique du Message pour authentification HMAC  */
$msg = "PBX_SITE=".$pbx_site.
    "&PBX_RANG=".$pbx_rang.
    "&PBX_IDENTIFIANT=".$pbx_identifiant.
    "&PBX_TOTAL=".$pbx_total.  //PRIX
    "&PBX_DEVISE=978".
    "&PBX_CMD=".$pbx_cmd. //Référence de la Transaction
    "&PBX_PORTEUR=".$pbx_porteur. //Email Du Client
    "&PBX_REPONDRE_A=".$pbx_repondre_a.
    "&PBX_RETOUR=".$pbx_retour.
    "&PBX_EFFECTUE=".$pbx_effectue.
    "&PBX_ANNULE=".$pbx_annule.
    "&PBX_REFUSE=".$pbx_refuse.
    "&PBX_HASH=SHA512".
    "&PBX_TIME=".$dateTime;
// echo $msg;

$binKey = pack("H*", $keyTest);
$hmac = strtoupper(hash_hmac('sha512', $msg, $binKey)); ?>
<style>.hidePaiement{display: none;}</style>
<title>Procèder au Paiement de votre Réservation - Apocalypstar</title>

<link rel="stylesheet" href="../assets/css/home.css">
<link rel="stylesheet" href="../assets/css/menu.css">

<!-- LAYOUT LUCA-->
<main class="fullViewportFlexible backgroundDarkBlue flexWrapper">
    <section class="fullPageContainer">
        <h1 class="hiddenTitle">Confirmation et paiement</h1>
        <div class="backgroundDark containerReservation flexWrapper decorParent blockHalfPage basicPadding framedBlock4">
            <!-- Bloc résumé !-->
            <div class="framedBlock5 backgroundMap basicPadding blockRecap">
                <h2 class="titleBrown">Merci pour votre réservation, <?= $firstname_1 ?> !</h2>
                <div class="font2">
                    <strong>Récapitulatif de votre saisie</strong>
                    <div class="playersPanel">
                        <img src="../assets/img/graphPlayers<?= $number_1 ?>.svg" alt="icône nombre de joueurs">
                        <p class="centerText"> Tarif pour <?= $number_1 ?> joueurs : <strong><?= $priceEuro ?>€</strong></p>
                    </div>
                    <p> Monsieur/madame <strong><?= ucfirst($firstname_1) ?> <?= ucfirst($name_1) ?></strong></p>
                    <p> A réservé l'escape room <strong>"<?= $num_room ?>"</strong> pour le <strong><?= $dateFrench ?></strong> à <strong><?= $hour_1 ?></strong>.</p>
                    
                    <p> Numéro de téléphone: <strong><?= $phone_1 ?></strong></p>
                    <p> E-mail: <strong><?= $email_1 ?></strong></p>
                    <p> La Réservation a été faite pour <?= $number_1 ?> personnes </p>
                </div>
                <strong class="inlineBlock alert alert-danger important alertCustom"> ATTENTION : Veuillez à bien vérifier l'adresse e-mail, un e-mail de confirmation vous y sera envoyé une fois le paiement effectué.<br><span style="font-weight: bold;">*Très important ; Veuillez être présent dix minutes en avance.</span>
                </strong>

                <!------------------ ENVOI DES INFORMATIONS A PAYBOX (Formulaire) ------------------>
                <form method="POST" action="<?php echo $serveurOK; ?>">
                    <input type="hidden" name="PBX_SITE" value="<?php echo $pbx_site; ?>">
                    <input type="hidden" name="PBX_RANG" value="<?php echo $pbx_rang; ?>">
                    <input type="hidden" name="PBX_IDENTIFIANT" value="<?php echo $pbx_identifiant; ?>">
                    <input type="hidden" name="PBX_TOTAL" value="<?php echo $pbx_total; ?>">
                    <input type="hidden" name="PBX_DEVISE" value="978">
                    <input type="hidden" name="PBX_CMD" value="<?php echo $pbx_cmd; ?>">
                    <input type="hidden" name="PBX_PORTEUR" value="<?php echo $pbx_porteur; ?>">
                    <input type="hidden" name="PBX_REPONDRE_A" value="<?php echo $pbx_repondre_a; ?>">
                    <input type="hidden" name="PBX_RETOUR" value="<?php echo $pbx_retour; ?>">
                    <input type="hidden" name="PBX_EFFECTUE" value="<?php echo $pbx_effectue; ?>">
                    <input type="hidden" name="PBX_ANNULE" value="<?php echo $pbx_annule; ?>">
                    <input type="hidden" name="PBX_REFUSE" value="<?php echo $pbx_refuse; ?>">
                    <input type="hidden" name="PBX_HASH" value="SHA512">
                    <input type="hidden" name="PBX_TIME" value="<?php echo $dateTime; ?>">
                    <input type="hidden" name="PBX_HMAC" value="<?php echo $hmac; ?>">
                    <div class='row'>
						<div class="col-md-6">-->
							<h3 class="text-center">Payer par carte bancaire</h3>
						<button type="submit" class="btn btn-success btn-lg paiement center-block" style="background-color: #1b9c94">Payer par carte bancaire </button>
						</div>
						<div class="col-md-6">
							<h3 class="text-center">Payer avec Paypal ou carte de crédit</h3>
                    		<div id="paypal-button"></div>
						</div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>

<script src="https://www.paypalobjects.com/api/checkout.js"></script>

<script>
paypal.Button.render({
    // Configure environment
    env: '<?php echo $paypal->paypalEnv; ?>',
    client: {

        sandbox: '<?php echo $paypal->paypalClientID; ?>',
        production: '<?php echo $paypal->paypalClientID; ?>'
    },
    // Customize button (optional)
    locale: 'fr_FR',
    style: {
        size: 'responsive',
        color: 'gold',
        shape: 'rect',
    },
    // Set up a payment
    payment: function (data, actions) {
        return actions.payment.create({

            transactions: [{
                amount: {
                    total: '<?php echo $priceEuro; ?>',

                    currency: '<?php echo 'EUR'; ?>'
                }
            }]
      });
    },
    // Execute the payment
    onAuthorize: function (data, actions) {
        return actions.payment.execute()
        .then(function () {
            // Show a confirmation message to the buyer
            //window.alert('Thank you for your purchase!');
            
            // Redirect to the payment process page
            window.location = "process.php?paymentID="+data.paymentID+"&token="+data.paymentToken+"&payerID="+data.payerID+"&pid=<?php echo $lastReserved->id; ?>";

        });
    }
}, '#paypal-button');
</script>