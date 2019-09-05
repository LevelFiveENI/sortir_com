
// on ajoute la constante $ pour jQuery

const $ = require('jquery');

// fonction ajax inscription à une sortie
$('.inscription').click(function(){
//inscription offset-1 col-4 btn btn-success
    $(this).removeClass('inscription btn-success').addClass('desincription btn btn-danger').text('Me désinscrire');

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
