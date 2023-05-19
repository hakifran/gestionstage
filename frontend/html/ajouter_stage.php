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
    <link rel="stylesheet" type="text/css" href="../csspersonnalise/valide_utilisateur.css" />
    <!-- <script src="https://kit.fontawesome.com/dabf916254.js" crossorigin="anonymous"></script> -->
    <title>
        Ajouter un stage
    </title>
    <style>

    </style>
</head>


<body>
    <div class="container-fluid">
        <div class="row">
            <?php include 'menu.php'?>
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-12 header">
                        <div class="row">
                            <div class="col-md-6">
                                <img src="../img/logo.png" alt="">
                            </div>
                            <div class="col-md-6 ">
                                <h3 class="applicationtitre">Gestion des stages
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 content">
                        <!--Debut alert pour afficher le message de succès ou d'échec-->
                        <div class="alert" hidden role="alert">
                        </div>
                        <!--Fin alert pour afficher le message de succès ou d'échec-->

                        <h3 class="text-left">Ajouter un stage</h3>

                        <!--Debut afficher une ligne-->
                        <hr>
                        <!--Fin afficher une ligne-->

                        <!--Debut des champs du formulaire-->
                        <div class="row">
                            <label class="label col-md-3 control-label">Projet</label>
                            <div class="col-md-9">
                                <input type="text" required class="form-control projet" name="projet"
                                    placeholder="Project">
                            </div>
                        </div>
                        <div class="row">
                            <label class="label col-md-3 control-label">Nom de l'entreprise</label>
                            <div class="col-md-9">
                                <input type="text" required class="form-control entreprise" name="entreprise"
                                    placeholder="Nom de l'entreprise">
                            </div>
                        </div>
                        <div class="row">
                            <label class="label col-md-3 control-label">Addresse de l'entreprise</label>
                            <div class="col-md-9">
                                <input type="addresse" required class="form-control addresse" name="addresse"
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

                        <button type onclick="creer_un_stage()" class="btn btn-info">Enregistre</button>
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



function creer_un_stage() {
    let payload = {}
    // Recuperer les parametres dans le formulaire
    payload["intituleProjet"] = $(".projet").val() === "" ? null : $(".projet").val();
    payload["nomEntreprise"] = $(".entreprise").val() === "" ? null : $(".entreprise").val();
    payload["adresse"] = $(".addresse").val() === "" ? null : $(".addresse").val();
    const donnee_utilisateur = JSON.parse(sessionStorage.getItem("donnee_utilisateur"));
    payload["id" + donnee_utilisateur["type"].charAt(0).toUpperCase() + donnee_utilisateur["type"]
        .slice(1)] = donnee_utilisateur["id"];
    const idPeriode = $('.periodes-list').find(":selected").val();
    if (idPeriode !== "") {
        payload["idPeriode"] = idPeriode;
    }


    // Envoyer les parametres pour être sauver dans le backend via Fetch
    fetch("http://localhost/gestionstage/public/stage/create", {
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
            text_alert = "Le projet de stage intitulé " + response["data"]["intituleProjet"] +
                " a été sauvé avec succès";
            window.location.href = 'mes_stages.php?alertMessage=' + text_alert;

        } else {
            $(".alert").addClass("alert-danger");
            text_alert = response["message"];
        }
        $(".alert").html(text_alert)

    });
}
</script>

</html>