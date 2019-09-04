
// on ajoute la constante $ pour jQuery

const $ = require('jquery');

$(document).ready(function() {



});













// fonction ajax inscription à une sortie
$('.inscription').click(function(){
$.ajax({
    url : '/afficher/inscription',
    type: "POST",
    data: {
        sortieId:$(this).val()
    },
    success: function (data) {
        console.log(data);
        alert("inscription réussi")
    },

    error: function () {
        alert("Erreur de JS");
    }

})
})

// fonction ajax desinscription à une sortie
    $('.desincription').click(function(){
        $.ajax({
            url : '/afficher/desinscription',
            type: "POST",
            data: {
                sortieId:$(this).val()
            },
            success: function (data) {
                console.log(data);
                alert("desinscription réussi")
            },

            error: function () {
                alert("Erreur de JS");
            }

        })

    })









/* exemple ajax valou
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

 */











// fonction pour que la date de fin se mette a jour en fonction de la date de debut
$('#dateMini').change(function () {

    var dateMi = $('#dateMini').val();

    $('#dateMaxi').val(dateMi);

})


// fonction pour que la date de fin ne puissent pas etre inferieur à la date de debut
//(si la date est inf. on remet la date de debut)
$('#dateMaxi').change(function () {

    var dateMi = $('#dateMini').val();

    if($('#dateMaxi').val()<$('#dateMini').val()){

        alert("la date de fin ne peut pas être inférieure à la date de début");
        $('#dateMaxi').val(dateMi);


    }
})
