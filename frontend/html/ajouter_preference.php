<!DOCTYPE html>
<html>
<!--Jquery-->

<head>
    <!--Le CSS du bootstrap-->
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css" />
    <!--JavaScript du bootstrap-->
    <script src="../js/bootstrap.js"></script>
    <link rel="stylesheet" type="text/css" href="../fontawesome/css/all.min.css" />
    <link rel="stylesheet" type="text/css" href="../fontawesome/css/fontawesome.min.css" />
    <!--CSS personnalisé du formulaire d'inscription-->
    <link rel="stylesheet" type="text/css" href="../csspersonnalise/templatestyle.css" />
    <link rel="stylesheet" type="text/css" href="../csspersonnalise/valide_utilisateur.css" />
    <!-- <script src="https://kit.fontawesome.com/dabf916254.js" crossorigin="anonymous"></script> -->
    <title>
        Ajouter préférences
    </title>
    <style>

    </style>
</head>


<body>
    <div class="container-fluid">
        <div class="row sidebar-contenu">
            <div class="col-md-2 sidebargauche">
                <?php include 'menu.php'?>
            </div>
            <div class="col-md-10">
                <div class="row">
                    <?php include 'header.php'?>
                </div>
                <div class="row">
                    <div class="col-md-8 content">
                        <!--Debut alert pour afficher le message de succès ou d'échec-->
                        <div class="alert" hidden role="alert">
                        </div>
                        <!--Fin alert pour afficher le message de succès ou d'échec-->

                        <h3 class="text-left">Ajouter préférences</h3>

                        <!--Debut afficher une ligne-->
                        <hr>
                        <!--Fin afficher une ligne-->

                        <!--Debut des champs du formulaire-->
                        <div class="row">
                            <label class="label col-md-3 control-label">Période:</label>
                            <div class="col-md-9">
                                <select class="form-control periode periodes-list">
                                    <option value="0">---Selectionner la periode---</option>
                                </select>
                            </div>
                        </div>
                        <div class="row sujet-label">
                            <label class="label col-md-3 control-label">Sélectionner les sujets:</label>
                        </div>
                        <div class="row sujet-values">
                            <div class="col-md-5">
                                <select class="form-select left-stages-list" multiple
                                    aria-label="multiple select example">
                                </select>
                            </div>
                            <div class="col-md-2 flesh-gauche-droite">
                                <div class="row">
                                    <div class="col-md-6">
                                        <a href="#" class="deplace-droite"><i
                                                class="fa-solid fa-angles-right fa-2x"></i></a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="#" class="deplace-gauche"><i
                                                class="fa-solid fa-angles-left fa-2x"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <select class="form-select col-md-3 right-stages-list" multiple
                                    aria-label="multiple select example">

                                </select>
                            </div>
                        </div>

                        <button type onclick="creer_preference()" class="btn btn-info">Enregistre</button>
                        <!--Fin des champs du formulaire-->
                    </div>
                </div>
            </div>
        </div>
        <!--Le pied de page-->
        <?php include 'footer.php'?>
    </div>

    <!--Charger jquery-->
    <script src="../jquery-3.6.4.js"></script>
    <!--JavaScript du bootstrap-->
    <script src="../js/bootstrap.js"></script>

</body>
<script>
$(document).ready(function() {
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
});
let selectionGauche = [];
let selectionDroite = [];
let stageSelectionne = [];
$('.periodes-list').on('change', function() {
    fetch("http://localhost/gestionstage/public/stage/list_par_periode?idPeriode=" + this.value + "", {
        method: "GET",
        headers: {
            'Authorization': 'Bearer ' + sessionStorage.getItem("jwt")
        }
    }).then((resultat) => resultat.json()).then((response) => {
        stageNonAttribue = response['data'].filter(stage => stage["attribue"] === "0" || stage[
            "attribue"] === null);
        let count = 1
        $(".left-stages-list").find('option')
            .remove()
        $(".right-stages-list").find('option')
            .remove()
        stageNonAttribue.forEach(stage => {
            $(".left-stages-list").append("<option value=" + stage["idStage"] + ">" +
                count + ". " + stage["intituleProjet"] + "</option>");
            count++;
        });

    });
});

$(document).on('dblclick', '.left-stages-list option', function() {
    stageSelectionne.push(this.value);
    $(".right-stages-list").append(this);
    $(".left-stages-list").remove(this);
});
$(document).on('click', '.left-stages-list option', function() {
    selectionGauche.push(this);
});

$(document).on('dblclick', '.right-stages-list option', function() {
    const index = stageSelectionne.indexOf(this.value);
    stageSelectionne.splice(index, 1);
    $(".left-stages-list").append(this);
    $(".right-stages-list").remove(this);
});
$(document).on('click', '.right-stages-list option', function() {
    selectionDroite.push(this);
});

$(".deplace-droite").on("click", function() {
    selectionGauche.forEach(selection => {
        stageSelectionne.push(selection.value);
    });
    $(".right-stages-list").append(selectionGauche);
});

$(".deplace-gauche").on("click", function() {
    selectionDroite.forEach(selection => {
        const index = stageSelectionne.indexOf(selection.value);
        stageSelectionne.splice(index, 1);
    });
    $(".left-stages-list").append(selectionDroite);
});


function creer_preference() {
    let payload = {}
    // Recuperer les parametres dans le formulaire
    payload["stages"] = stageSelectionne;
    const donnee_utilisateur = JSON.parse(sessionStorage.getItem("donnee_utilisateur"));
    payload["id" + donnee_utilisateur["type"].charAt(0).toUpperCase() + donnee_utilisateur["type"]
        .slice(1)] = donnee_utilisateur["id"];
    const idPeriode = $('.periodes-list').find(":selected").val();
    if (idPeriode !== "") {
        payload["idPeriode"] = idPeriode;
    }
    console.log(payload);

    // Envoyer les parametres pour être sauver dans le backend via Fetch
    fetch("http://localhost/gestionstage/public/preference/create", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            'Authorization': 'Bearer ' + sessionStorage.getItem("jwt")
        },
        body: JSON.stringify(payload)
    }).then((resultat) => resultat.json()).then((response) => {
        console.log(response);
        let text_alert = "";
        // Faire apparaitre l'alert pour afficher un message de succes ou d'erreur
        $(".alert").removeAttr("hidden");
        // reinitialiser le type d'alert
        $(".alert").removeClass("alert-success");
        $(".alert").removeClass("alert-danger");
        //affiche le message de reussite ou d'echeck
        if (response["status"] === "ok") {
            text_alert = "La préférence a été ajoutée avec success";
            window.location.href = 'preferences.php?alertMessage=' + text_alert;

        } else {
            $(".alert").addClass("alert-danger");
            text_alert = response["message"];
        }
        $(".alert").html(text_alert)

    });
}
</script>

</html>