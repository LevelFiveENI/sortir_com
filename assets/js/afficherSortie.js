
// on ajoute la constante $ pour jQuery

const $ = require('jquery');




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



