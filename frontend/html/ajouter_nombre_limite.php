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
        Ajouter nombre limite
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

                        <h3 class="text-left titre">Ajouter nombre limite</h3>

                        <!--Debut afficher une ligne-->
                        <hr>
                        <!--Fin afficher une ligne-->

                        <!--Debut des champs du formulaire-->
                        <div class="row">
                            <label class="label col-md-3 control-label">Nombre limite</label>
                            <div class="col-md-9">
                                <input type="number" required class="form-control nombre-limite" name="nombre-limite"
                                    placeholder="Nombre limite">
                            </div>
                        </div>
                        <div class="row">
                            <label class="label col-md-3 control-label">Période</label>
                            <div class="col-md-9">
                                <select class="form-control periodes-list">
                                    <option value="">---Selectionner la periode---</option>
                                </select>
                            </div>
                        </div>

                        <button type onclick="creer_nombre_limite()" class="btn btn-info">Enregistre</button>
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
    if (urlParams.get("idNombreStage")) {
        fetch("http://localhost/gestionstage/public/nombreStage/get?id=" + urlParams.get("idNombreStage"), {
            method: "GET",
            headers: {
                'Authorization': 'Bearer ' + sessionStorage.getItem("jwt")
            }
        }).then((resultat) => resultat.json()).then((response) => {
            const valeurs = response["data"];

            modifier = true;
            $(".titre").text("Modifier le nombre de stage");
            $("title").text("Modifier le nombre de stage");
            $("button").text("Modifier");
            $(".nombre-limite").val(valeurs["nombre"]);
            $(".periodes-list option[value=" + valeurs["idPeriode"] + "]").prop('selected', true);

        });
    }

});




function creer_nombre_limite() {
    const urlParamsString = window.location.search;
    const urlParams = new URLSearchParams(urlParamsString);
    let payload = {}
    // Recuperer les parametres dans le formulaire
    payload["nombre"] = $(".nombre-limite").val() === "" ? null : $(".nombre-limite").val();
    if (urlParams.get("idNombreStage")) {
        payload["idNombreStage"] = urlParams.get("idNombreStage");
    }

    const donnee_utilisateur = JSON.parse(sessionStorage.getItem("donnee_utilisateur"));
    payload["id" + donnee_utilisateur["type"].charAt(0).toUpperCase() + donnee_utilisateur["type"]
        .slice(1)] = donnee_utilisateur["id"];
    const idPeriode = $('.periodes-list').find(":selected").val();
    if (idPeriode !== "") {
        payload["idPeriode"] = idPeriode;
    }


    // Envoyer les parametres pour être sauver dans le backend via Fetch

    fetch("http://localhost/gestionstage/public/nombreStage/" + (modifier === true ? "update" : "create"), {
        method: modifier === true ? "PATCH" : "POST",
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
            text_alert = "La limite de stages a été enregisté";
            window.location.href = 'nombre_limite_stages.php?alertMessage=' + text_alert;

        } else {
            $(".alert").addClass("alert-danger");
            text_alert = response["message"];
        }
        $(".alert").html(text_alert)

    });
}
</script>

</html>