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
        Auto attribue un stage
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

                        <h3 class="text-left titre">Auto attribue un stage</h3>

                        <!--Debut afficher une ligne-->
                        <hr>
                        <!--Fin afficher une ligne-->

                        <!--Debut des champs du formulaire-->
                        <div class="row">
                            <label class="label col-md-3 control-label">Projet</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control projet a_modifier" name="projet"
                                    placeholder="Projet">
                            </div>
                        </div>
                        <div class="row">
                            <label class="label col-md-3 control-label">Entreprise</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control entreprise a_modifier" name="entreprise"
                                    placeholder="Entreprise">
                            </div>
                        </div>
                        <div class="row">
                            <label class="label col-md-3 control-label">Addresse de l'entreprise</label>
                            <div class="col-md-9">
                                <input type="addresse" required class="form-control addresse a_modifier" name="addresse"
                                    placeholder="Addresse de l'entreprise">
                            </div>
                        </div>
                        <div class="row">
                            <label class="label col-md-3 control-label">Période</label>
                            <div class="col-md-9">
                                <select class="form-control parcours periodes-list">
                                    <option value="">---Selectionner la periode---</option>
                                </select>
                            </div>
                        </div>
                        <div class="row enseignant a_ne_pas_modifier"">
                            <label class=" label col-md-3 control-label type-utilisateur a_modifier">Tuteur</label>
                            <div class="col-md-9">
                                <input disabled type="text" class="form-control tuteur" name="tuteur"
                                    placeholder="tuteur">
                            </div>
                        </div>
                        <div class="row a_ne_pas_modifier">
                            <label class=" label col-md-3 control-label">Attribuer</label>
                            <div class="col-md-9">
                                <input class="form-controlcheckbox form-check-input" type="checkbox" value=""
                                    id="flexCheckChecked" checked>
                            </div>
                        </div>


                        <button onclick="auto_attribuer()" class="btn btn-info">Enregistre</button>
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
let modifier = false;
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
    const urlParamsString = window.location.search;
    const urlParams = new URLSearchParams(urlParamsString);
    const donnee_utilisateur = JSON.parse(sessionStorage.getItem("donnee_utilisateur"));
    const typeUtilisateur = donnee_utilisateur["type"] === "enseignant" ?
        "Etudiant" :
        "Tuteur";
    const autreTypeUtilisateur = donnee_utilisateur["type"] === "enseignant" ?
        "Etudiant" :
        "Enseignant";

    $(".type-utilisateur").text(typeUtilisateur);
    fetch("http://localhost/gestionstage/public/stage/get?id=" + urlParams.get("idStage"), {
        method: "GET",
        headers: {
            'Authorization': 'Bearer ' + sessionStorage.getItem("jwt")
        }
    }).then((resultat) => resultat.json()).then((response) => {
        const valeurs = response["data"];
        $(".projet").val(valeurs["intituleProjet"]);
        $(".entreprise").val(valeurs["nomEntreprise"]);
        $('.periodes-list option[value=' + valeurs["idPeriode"] + ']').prop('selected', true)
        $(".addresse").val(valeurs["adresse"]);
        $(".tuteur").val(valeurs["nom" + autreTypeUtilisateur] + " " + valeurs["prenom" +
            autreTypeUtilisateur]);
        if (valeurs["creer_par"] === donnee_utilisateur["type"]) {
            $(".a_ne_pas_modifier").hide();
            $(".titre").text("Modifier le stage");
            $("title").text("Modifier le stage");
            $("button").text("Modifier");
            modifier = true;
        } else {
            $(".a_modifier").attr("disabled", "true");
        }
        if (valeurs["attribue"] == null || valeurs["attribue"] == 0) {
            $("#flexCheckChecked").removeAttr("checked");
        } else {
            $("#flexCheckChecked").attr("checked", "checked");
        }

    });
});

function auto_attribuer() {
    const urlParamsString = window.location.search;
    const urlParams = new URLSearchParams(urlParamsString);
    const donnee_utilisateur = JSON.parse(sessionStorage.getItem("donnee_utilisateur"));
    let payload = {}
    // Recuperer les parametres dans le formulaire
    const estCheck = $("#flexCheckChecked").is(":checked");
    payload["idStage"] = urlParams.get('idStage');
    payload["intituleProjet"] = $(".projet").val() === "" ? null : $(".projet").val();
    payload["nomEntreprise"] = $(".entreprise").val() === "" ? null : $(".entreprise").val();
    payload["adresse"] = $(".addresse").val() === "" ? null : $(".addresse").val();
    const idPeriode = $('.periodes-list').find(":selected").val();
    if (idPeriode !== "") {
        payload["idPeriode"] = idPeriode;
    }
    payload["attribuer"] = estCheck ? "true" : "false";
    payload["id" + donnee_utilisateur["type"].charAt(0).toUpperCase() + donnee_utilisateur["type"].slice(1)] =
        donnee_utilisateur;
    // Envoyer les parametres pour être sauver dans le backend via Fetch
    fetch("http://localhost/gestionstage/public/stage/" + (modifier == true ? "update" : "auto_attribue"), {
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
            window.location.href = 'mes_stages.php?alertMessage=' + text_alert;
        } else {
            $(".alert").addClass("alert-danger");
            text_alert = response["message"];
        }
        $(".alert").html(text_alert);


    });
}
</script>

</html>