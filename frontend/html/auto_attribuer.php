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
        Auto attribue un stage
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
                    <?php include 'header.php'?>
                </div>
                <div class="row">
                    <div class="col-md-8 content">
                        <!--Debut alert pour afficher le message de succès ou d'échec-->
                        <div class="alert" hidden role="alert">
                        </div>
                        <!--Fin alert pour afficher le message de succès ou d'échec-->

                        <h3 class="text-left">Auto attribue un stage</h3>

                        <!--Debut afficher une ligne-->
                        <hr>
                        <!--Fin afficher une ligne-->

                        <!--Debut des champs du formulaire-->
                        <div class="row">
                            <label class="label col-md-3 control-label">Projet</label>
                            <div class="col-md-9">
                                <input disabled type="text" class="form-control projet" name="projet"
                                    placeholder="Projet">
                            </div>
                        </div>
                        <div class="row">
                            <label class="label col-md-3 control-label">Entreprise</label>
                            <div class="col-md-9">
                                <input disabled type="text" class="form-control entreprise" name="entreprise"
                                    placeholder="Entreprise">
                            </div>
                        </div>
                        <div class="row">
                            <label class="label col-md-3 control-label">Période</label>
                            <div class="col-md-9">
                                <input disabled type="text" class="form-control periode" name="periode"
                                    placeholder="Période">
                            </div>
                        </div>
                        <div class="row enseignant">
                            <label class="label col-md-3 control-label">Tuteur</label>
                            <div class="col-md-9">
                                <input disabled type="text" class="form-control tuteur" name="tuteur"
                                    placeholder="tuteur">
                            </div>
                        </div>
                        <div class="row">
                            <label class="label col-md-3 control-label">Attribuer</label>
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
$(document).ready(function() {
    const urlParamsString = window.location.search;
    const urlParams = new URLSearchParams(urlParamsString);
    fetch("http://localhost/gestionstage/public/stage/get?id=" + urlParams.get("idStage"), {
        method: "GET",
        headers: {
            'Authorization': 'Bearer ' + sessionStorage.getItem("jwt")
        }
    }).then((resultat) => resultat.json()).then((response) => {
        const valeurs = response["data"];
        console.log(valeurs);
        $(".projet").val(valeurs["intituleProjet"]);
        $(".entreprise").val(valeurs["nomEnseignant"]);
        $(".periode").val(valeurs["periodeIntitule"]);
        $(".tuteur").val(valeurs["nomEnseignant"] + " " + valeurs["prenomEnseignant"]);
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


    payload["attribuer"] = estCheck ? "true" : "false";
    payload["id" + donnee_utilisateur["type"].charAt(0).toUpperCase() + donnee_utilisateur["type"].slice(1)] =
        donnee_utilisateur;

    // Envoyer les parametres pour être sauver dans le backend via Fetch
    fetch("http://localhost/gestionstage/public/stage/auto_attribue", {
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