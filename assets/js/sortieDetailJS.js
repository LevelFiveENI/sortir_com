/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');


// ajout des infos pour le bootstrap (jeremy)

const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');

///Mise en place du compte à rebourd

$(document).ready(function () {
    var dateFormat = $("#dateSortie").val()
    console.log(dateFormat);
    // Set the date we're counting down to
    var countDownDate = new Date(dateFormat).getTime();

// Update the count down every 1 second
    var x = setInterval(function() {

        // Get today's date and time
        var now = new Date().getTime();

        // Find the distance between now and the count down date
        var distance = countDownDate - now;

        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Display the result in the element with id="demo"
        document.getElementById("countDown").innerHTML = days + " jours " + hours + " heures "
            + minutes + " minutes " + seconds + " secondes ";

        // If the count down is finished, write some text
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("countDown").innerHTML = "EXPIRED";
        }
    }, 1000);

})

// fonction ajax inscription à une sortie
$('.inscription').click(function(){
//inscription offset-1 col-4 btn btn-success
    $(this).removeClass('inscription btn btn-success').addClass('desincription btn btn-danger').text('Me désinscrire');

    $.ajax({
        url : '/afficher/inscription',
        type: "POST",
        data: {
            sortieId:$(this).val()
        },
        success: function (data) {
            console.log(data);
            //alert("inscription réussi")
            history.go()
        },
        error: function () {
            alert("Erreur de JS");
        }

    })

})

// fonction ajax desinscription à une sortie
$('.desincription').click(function(){
    //desincription offset-1 col-5 btn btn-danger
    $(this).removeClass('desincription btn btn-danger').addClass('inscription btn btn-success').text('M\'inscrire');

    $.ajax({
        url : '/afficher/desinscription',
        type: "POST",
        data: {
            sortieId:$(this).val()
        },
        success: function (data) {
            console.log(data);
            //alert("desinscription réussi")
            history.go()

        },

        error: function () {
            alert("Erreur de JS");
        }

    })

})