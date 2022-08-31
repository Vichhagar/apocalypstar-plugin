$(document).ready(function () {

    /** On récupère le Jours du Samedi pour le cacher **/
    var hiddenSaturday = document.getElementById('hidden_saturday').value;
    $(".jour_" + hiddenSaturday).hide();


    /** On récupère le Jours du Vendredi pour cacher l'après midi **/
    var hiddenFriday = document.getElementById('hidden_friday').value;
    $(".jour_" + hiddenFriday + ".horaire_3, .jour_" + hiddenFriday + ".horaire_4," +
        " .jour_" + hiddenFriday + ".horaire_5, .jour_" + hiddenFriday + ".horaire_6," +
        " .jour_" + hiddenFriday + ".horaire_7, .jour_" + hiddenFriday + ".horaire_8").addClass('fermer').removeClass('cliquable reserved').text('FERMÉ');


    /** Calendrier Horaire Creuse */
    var nbDaysWeek = document.getElementById('nb_days_php_to_js').value;


    if (nbDaysWeek == "4-days") {
        var hiddenMonday = document.getElementById('hidden_monday').value;
        $(".jour_" + hiddenMonday).hide();

        var hiddenFriday = document.getElementById('hidden_friday').value;
        $(".jour_" + hiddenFriday).hide();


        /** On rends les horaires du Matin Fermer exepté le Dimanche **/
        var showSunday = document.getElementById('show_sunday').value;
        $('.horaire_0:not(.jour_'+ showSunday + '), ' +
            '.horaire_1:not(.jour_'+ showSunday + '),' +
            ' .horaire_2:not(.jour_'+ showSunday + ')').addClass('fermer').removeClass('cliquable reserved').text('FERMÉ');

    }


    // On récupére la valeur du Jours et de l'Heure du Calendrier
    $('.cliquable').click(function ()
    {
        days = $(this).attr('data-date');
        hour = $(this).attr('data-hour');


        $('.cliquable').each(function ()
        {
            if (days == $(this).attr('data-date') && hour == $(this).attr('data-hour')) {
                $(this).addClass('pre-reserved');
            } else {
                $(this).removeClass('pre-reserved');
            }

        });
    });

    // On récupére la valeur de l'attribut data-level JOUEUR

    $('#niveau > .level').click(function () {
        level = $(this).attr('data-level');
        $('.level').each(function () {
            $(this).removeClass('pre-reserved');

        });
        $(this).addClass('pre-reserved');


        document.forms["formUser"].elements["level"].value = level;


    });

    // On récupére la valeur de l'attribut data-number JOUEUR
    $('#personne > .number').click(function ()
    {
        number = $(this).attr('data-number');
        $('.number').each(function () {
            $(this).removeClass('pre-reserved');
        });
        $(this).addClass('pre-reserved');
        
    });


    // BLOQUER UNE SESSION PAR ADMIN
    $("#form_block_admin").submit(function () {

        // Vérifie que le jour et l'horaire à bien été sélectionné
        if (typeof days == "undefined" || typeof hour == "undefined") {
            alert('Veuillez choisir une date de réservation');
            event.preventDefault();
        }
        else {
            document.forms["formUser"].elements["days"].value = days;
            document.forms["formUser"].elements["hour"].value = hour;
        }

    });


        // SOUSMISSION DU FORMULAIRE COTE ADMINISTRATEUR
    $("#form_reserved_admin").submit(function () {
        event.preventDefault();


        name = document.getElementById("name").value;
        firstname = document.getElementById("firstname").value;
        email = document.getElementById("email").value;
        phone = document.getElementById("phone").value;
        room = document.getElementById("room").value;

        var form = $(this);
        var error = 0;


        // Bouclé sur les Champs pour s'assurer qu'ils sont remplies
        $('#name, #firstname, #email, #phone', form).each(function () {
            if ($(this).val() == '') {
                error++;
            }
            if (error) { // En cas d'erreur(s) détectée(s)
                alert('Veuillez renseigner les champs (Nom, Prénom, Email, Téléphone)');
                return false;
            }
        });


        if (!email.match(/[a-z0-9_\-\.]+@[a-z0-9_\-\.]+\.[a-z]+/i)) {
            alert(email + " n'est pas une adresse e-mail valide");
            error++;
            return false;
        }


        // Vérifie que la DIV Number ou Level et sélectionné
        if (typeof number == "undefined") {
            alert('Veuillez choisir le nombre de participants');
            error++;
            return false;
        }


        // Vérifie que le jour et l'horaire à bien été sélectionné
        if (typeof days == "undefined" || typeof hour == "undefined") {
            error++;
            alert('Veuillez choisir une date de réservation');
            return false;
        }


        if (error == 0 ){
            $.post("../admin/traitementadmin.php",
                {
                    name: name,
                    firstname: firstname,
                    email: email,
                    phone: phone,
                    level: 0,
                    number: number,
                    days: days,
                    hour: hour,
                    room: room
                },
                function (data) {
                    //document.getElementById("form_reserved_admin").reset();//
                    document.location.reload(true);
                });
        }

    });
    

});
