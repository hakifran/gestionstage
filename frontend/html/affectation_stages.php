<!DOCTYPE html>
<html>
<!--Jquery-->

<head>
    <!--Le CSS du bootstrap-->
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css" />
    <!--JavaScript du bootstrap-->
    <script src="../js/bootstrap.js"></script>
    <!--CSS personnalisé du formulaire d'inscription-->
    <link rel="stylesheet" type="text/css" href="../csspersonnalise/templatestyle.css" />
    <!-- <script src="https://kit.fontawesome.com/dabf916254.js" crossorigin="anonymous"></script> -->
    <title>
        Affectation des stages
    </title>
    <style>

    </style>
</head>


<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebargauche">
                <?php include 'menu.php'?>
            </div>
            <div class="col-md-10">
                <div class="row">
                    <?php include 'header.php'?>
                </div>
                <div class="row">
                    <div class="col-md-12 content">
                        <!--Debut alert pour afficher le message de succès ou d'échec-->
                        <div class="alert" hidden role="alert">
                        </div>
                        <!--Fin alert pour afficher le message de succès ou d'échec-->
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="text-left">Affectation des stages</h3>
                            </div>

                        </div>


                        <!--Debut afficher une ligne-->
                        <hr>
                        <!--Fin afficher une ligne-->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="row">
                                    <label class="label col-md-3 control-label">Scope</label>
                                    <div class="col-md-9">
                                        <select class="form-control scope">
                                            <option value="">Tous</option>
                                            <option value="non">Non attribues</option>
                                            <option value="oui">Attribués</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-4">

                                <div class="row">
                                    <label class="label col-md-3 control-label">Périodes</label>
                                    <div class="col-md-9">
                                        <select class="form-control periodes-list">
                                            <option value="">---Selectionner la periode---</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button type onclick="attribuer()"
                                    class="btn btn-info attribuer-button">Attribuer</button>
                            </div>
                        </div>
                        <table class="table">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col"><input class='form-check-input selectionner-tous' type='checkbox'>
                                    </th>
                                    <th scope=" col">Projet</th>
                                    <th scope="col">Entreprise</th>
                                    <th scope="col">Adresse</th>
                                    <th scope="col">Étudiant</th>
                                    <th scope="col">Tuteur</th>
                                    <th scope="col">Periode</th>
                                    <th scope="col">Attribue</th>
                                </tr>
                            </thead>
                            <tbody class="stages-list">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php include 'footer.php'?>

    </div>
    <!--Le pied de page-->

    <!--Charger jquery-->
    <script src="../jquery-3.6.4.js"></script>
    <!--JavaScript du bootstrap-->
    <script src="../js/bootstrap.js"></script>

</body>
<script>
let scope = "";
let periode = "";
let idStages = [];
$(document).ready(function() {
    const urlParamsString = window.location.search;
    const urlParams = new URLSearchParams(urlParamsString);
    const alertMessage = urlParams.get("alertMessage");
    const donnee_utilisateur = JSON.parse(sessionStorage.getItem("donnee_utilisateur"));
    if (alertMessage != undefined && alertMessage != null) {
        // Faire apparaitre l'alert pour afficher un message de succes ou d'erreur
        $(".alert").removeAttr("hidden");
        $(".alert").addClass("alert-success");
        $(".alert").html(alertMessage);
    }

    // recuperer les periodes
    fetch("http://localhost/gestionstage/public/periode/list", {
        method: "GET",
        headers: {
            'Authorization': 'Bearer ' + sessionStorage.getItem("jwt")
        }
    }).then((resultat) => resultat.json()).then((response) => {
        periodes = response['data'];
        $list_periodes = "";
        periodes.forEach(periode => {
            $(".periodes-list").append("<option value=" + periode["idPeriode"] + ">" +
                periode[
                    "intitule"] + "</option>");
        });

    });

    les_attributions("", "");


});

function estPaire(nombre) {
    return nombre % 2;
}

$('.scope').on('change', function() {
    scope = this.value;
    idStages = []
    $('.stages-list tr').remove();
    les_attributions(scope, periode)
});

$('.periodes-list').on('change', function() {
    periode = this.value;
    idStages = [];
    $('.stages-list tr').remove();
    les_attributions(scope, periode)
});



$(document).on('change', '.idStageCheckBox', function() {
    if ($(this).is(':checked')) {
        idStages.push($(this).attr("idStage"));
    } else {
        const index = idStages.indexOf($(this).attr("idStage"));
        idStages.splice(index, 1);
    }
});

$(document).on('change', '.selectionner-tous', function() {
    if ($(this).is(':checked')) {
        $('.idStageCheckBox').each(function(i, element) {
            $(element).attr('checked', true);
            idStages.push($(element).attr("idStage"));
        });
    } else {

        $('.idStageCheckBox').each(function(i, element) {
            $(element).attr('checked', false);
            const index = idStages.indexOf($(element).attr("idStage"));
            idStages.splice(index, 1);
        });
    }
    console.log(idStages)
});


function attribuer() {

    let payload = {}

    payload["idStages"] = idStages;
    payload["idPeriode"] = periode;
    // Envoyer les parametres pour être sauver dans le backend via Fetch
    fetch("http://localhost/gestionstage/public/stage/attribue", {
        method: "PATCH",
        headers: {
            "Content-Type": "application/json",
            'Authorization': 'Bearer ' + sessionStorage.getItem("jwt")
        },
        body: JSON.stringify(payload)
    }).then((resultat) => resultat.json()).then((response) => {

        let text_alert = "";
        // Faire apparaitre l'alert pour afficher un message de succes ou d'erreur
        $(".alert").removeAttr("hidden");
        // reinitialiser le type d'alert
        $(".alert").removeClass("alert-success");
        $(".alert").removeClass("alert-danger");
        //affiche le message de reussite ou d'echeck
        if (response["status"] === "ok") {
            text_alert = response["message"];
            window.location.href = 'affectation_stages.php?alertMessage=' + text_alert;
        } else {
            $(".alert").addClass("alert-danger");
            text_alert = response["message"];
        }
        $(".alert").html(text_alert);


    });
}




function les_attributions(attribue_no_attribue, idPeriode) {
    let count = 1
    let url = "http://localhost/gestionstage/public/stage/list_tous_attribue_et_non_attribue_et_par_periode";
    if (attribue_no_attribue !== "") {
        url += "?scope=" + attribue_no_attribue;
        if (idPeriode !== "") {
            url += "&idPeriode=" + idPeriode;
        }
    } else {
        if (idPeriode !== "") {
            url += "?idPeriode=" + idPeriode;
        }
    }

    fetch(url, {
        method: "GET",
        headers: {
            'Authorization': 'Bearer ' + sessionStorage.getItem("jwt")
        }
    }).then((resultat) => resultat.json()).then((response) => {

        if (response["data"].length > 0) {
            response["data"].forEach((value) => {

                $(".stages-list").append(
                    "<tr class='" + (estPaire(count) == 0 ? "table-primary" : "") +
                    "'><th scope='row'>" + count +
                    "</th><td><input class='form-check-input idStageCheckBox' idStage=" + value[
                        "idStage"] +
                    " type='checkbox'></td><td>" + value[
                        "intituleProjet"] + "</td><td>" + value[
                        "nomEntreprise"] +
                    "</td><td>" + value["adresse"] +
                    "</td><td>" + value["etudiant"] +
                    "</td><td>" + (value["enseignant"] === null || value["enseignant"] === "" ?
                        "-" : value["enseignant"]) +
                    "</td><td>" + value["periode"] +
                    "</td><td><input class='form-check-input' type='checkbox' " +
                    (value["attribue"] === "1" ? "checked" : "") +
                    " disabled></td></tr>"
                );
                count++;
            });
        } else {
            $(".alert").removeAttr("hidden");
            $(".alert").addClass("alert-info");
            $(".alert").html("Pas de stage pour ce scope");
        }

    });
}
</script>

</html>