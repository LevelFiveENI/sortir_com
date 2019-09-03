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

$("#sortie_ville").change(function() {
        $.ajax({
            //Appeler la nouvelle fonction
            url: '/sortie/ajaxLieu',
            type: "POST",
            dataType:"json",
            data: {
                villeid: $("#sortie_ville").val()
            },

            success: function (json) {
                console.log(json);
                $('#sortie_lieu').empty();
                $.each(json, function(i, optionHtml){
                    $('#sortie_lieu').append($('<option>').text(optionHtml)
                        .attr( { name:"nomLieu", value:i } ))
                })

            },
            error: function (err) {
                alert("Erreur de JS");
            }
        });
    });

$("#ajoutLieu").submit(function(event) {
    event.preventDefault();

    $.ajax({
        //Appeler la nouvelle fonction
        url: "../lieu/new",
        type: "POST",
        dataType:"json",
        data: {
            newLieuNom: $("#lieu_nom").val(),
            newLieuRue: $("#lieu_rue").val(),
            newLieuVille: $("#lieu_nomVille").val()
        },

        success: function () {
            $("#exampleModal").modal('hide');
            $("#successLieu").html($("#lieu_nom").val() + "à été ajouté !");
            $("#lieu_nom").empty();
            $("#lieu_rue").empty();

        },
        error: function () {
            $("#exampleModal").modal('hide');
            $("#successLieu").html($("#lieu_nom").val() + "à été ajouté !");
            $("#lieu_nom").empty();
            $("#lieu_rue").empty();

        }
    });
});

