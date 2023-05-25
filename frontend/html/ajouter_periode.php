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
        Ajouter une période
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

                        <h3 class="text-left">Ajouter une période</h3>

                        <!--Debut afficher une ligne-->
                        <hr>
                        <!--Fin afficher une ligne-->

                        <!--Debut des champs du formulaire-->
                        <div class="row">
                            <label class="label col-md-3 control-label">Intitule</label>
                            <div class="col-md-9">
                                <input type="text" required class="form-control intitule" name="intitule"
                                    placeholder="Intitule">
                            </div>
                        </div>
                        <div class="row">
                            <label class="label col-md-3 control-label">Date debut</label>
                            <div class="col-md-9">
                                <input type="date" required class="form-control dateDebut" name="dateDebut"
                                    placeholder="Date debut">
                            </div>
                        </div>
                        <div class="row">
                            <label class="label col-md-3 control-label">Date fin</label>
                            <div class="col-md-9">
                                <input type="date" required class="form-control dateFin" name="dateFin"
                                    placeholder="Date fin">
                            </div>
                        </div>

                        <button type onclick="creer_un_periode()" class="btn btn-info">Enregistre</button>
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



function creer_un_periode() {
    let payload = {}
    // Recuperer les parametres dans le formulaire
    payload["intitule"] = $(".intitule").val() === "" ? null : $(".intitule").val();
    const dateDebut = $(".dateDebut").val() === "" ? null : new Date($(".dateDebut").val());
    const dateFin = $(".dateFin").val() === "" ? null : new Date($(".dateFin").val());
    if (dateDebut > dateFin) {
        // Faire apparaitre l'alert pour afficher un message de succes ou d'erreur
        $(".alert").removeAttr("hidden");
        // reinitialiser le type d'alert
        $(".alert").removeClass("alert-success");
        $(".alert").removeClass("alert-danger");
        //affiche le message de reussite ou d'echeck
        $(".alert").addClass("alert-danger");
        $(".alert").html("La date de fin de période doit être toujours supérieur à la date de début")
    } else {
        payload["dateDebut"] = $(".dateDebut").val() === "" ? null : $(".dateDebut").val();
        payload["dateFin"] = $(".dateFin").val() === "" ? null : $(".dateFin").val();
        payload["courant"] = 1;


        // Envoyer les parametres pour être sauver dans le backend via Fetch
        fetch("http://localhost/gestionstage/public/periode/create", {
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
                text_alert = "La intitulé " + response["data"]["intitule"] +
                    " a été sauvé avec succès";
                window.location.href = 'periodes.php?alertMessage=' + text_alert;

            } else {
                $(".alert").addClass("alert-danger");
                text_alert = response["message"];
            }
            $(".alert").html(text_alert)

        });
    }

}
</script>

</html>