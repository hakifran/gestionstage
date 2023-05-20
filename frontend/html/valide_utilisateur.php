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
        Valider un utilisateur
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

                        <h3 class="text-left">Valider un utilisateur</h3>

                        <!--Debut afficher une ligne-->
                        <hr>
                        <!--Fin afficher une ligne-->

                        <!--Debut des champs du formulaire-->
                        <div class="row">
                            <label class="label col-md-3 control-label">Nom</label>
                            <div class="col-md-9">
                                <input disabled type="text" class="form-control nom" name="Nom" placeholder="Nom">
                            </div>
                        </div>
                        <div class="row">
                            <label class="label col-md-3 control-label">Prenom</label>
                            <div class="col-md-9">
                                <input disabled type="text" class="form-control prenom" name="Prenom"
                                    placeholder="Prenom">
                            </div>
                        </div>
                        <div class="row">
                            <label class="label col-md-3 control-label">E-mail</label>
                            <div class="col-md-9">
                                <input disabled type="Email" class="form-control email" name="Email"
                                    placeholder="Email">
                            </div>
                        </div>
                        <div class="row enseignant">
                            <label class="label col-md-3 control-label">Titre</label>
                            <div class="col-md-9">
                                <input disabled type="text" class="form-control titre" name="Titre" placeholder="Titre">
                            </div>
                        </div>
                        <div class="row enseignant">
                            <label class="label col-md-3 control-label">Spécialisation</label>
                            <div class="col-md-9">
                                <input disabled type="text" class="form-control specialisation" name="Specialisation"
                                    placeholder="Specialisation">
                            </div>
                        </div>
                        <div class="row etudiant">
                            <label class="label col-md-3 control-label">Numero étudiant</label>
                            <div class="col-md-9">
                                <input disabled type="text" class="form-control numeroEtudiant" name="NumeroEtudiant"
                                    placeholder="Numero étudiant">
                            </div>
                        </div>
                        <div class="row etudiant">
                            <label class="label col-md-3 control-label">Numero national</label>
                            <div class="col-md-9">
                                <input disabled type="text" class="form-control numeroNational" name="NumeroNational"
                                    placeholder="Numero national">
                            </div>
                        </div>
                        <div class="row etudiant">
                            <label class="label col-md-3 control-label">Parcours</label>
                            <div class="col-md-9">
                                <select disabled class="form-control parcours">
                                    <option value="Parcours 1">Parcours 1</option>
                                    <option value="Parcours 2">Parcours 2</option>
                                    <option value="Parcours 3">Parcours 3</option>
                                    <option value="Parcours 4">Parcours 4</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <label class="label col-md-3 control-label">Valide</label>
                            <div class="col-md-9">
                                <input class="form-controlcheckbox form-check-input" type="checkbox" value=""
                                    id="flexCheckChecked" checked>
                            </div>
                        </div>


                        <button onclick="valider_utilisateur()" class="btn btn-info">Enregistre</button>
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
    // quand la page charge ce sont les champs de l'enseignant qui apparaissent seulement

    $(".etudiant").hide();
    $(".enseignant").show();
    const urlParamsString = window.location.search;
    const urlParams = new URLSearchParams(urlParamsString);

    // Afficher certain champs selon le type d'utilisateur
    if (urlParams.get("typeUtilisateur") === "etudiant") {
        $(".enseignant").hide();
        $(".etudiant").show();
    } else {
        $(".etudiant").hide();
        $(".enseignant").show();
    }

    // recuperer les données relative à l'utilisateur
    fetch("http://localhost/gestionstage/public/" + urlParams.get("typeUtilisateur") + "/get?id=" +
        urlParams.get("idUtilisateur").toString() + "", {
            method: "GET",
            headers: {
                'Authorization': 'Bearer ' + sessionStorage.getItem("jwt")
            }
        }).then((resultat) => resultat.json()).then((response) => {
        const valeurs = response["data"];
        console.log(valeurs);
        $(".nom").val(valeurs["nom"]);
        $(".prenom").val(valeurs["prenom"]);
        $(".email").val(valeurs["email"]);
        if (valeurs["valide"] == null || valeurs["valide"] == 0) {
            $("#flexCheckChecked").removeAttr("checked");
        } else {
            $("#flexCheckChecked").attr("checked", "checked");
        }
        if (urlParams.get("typeUtilisateur") === "enseignant") {
            $(".titre").val(valeurs["titre"]);
            $(".specialisation").val(valeurs["specialisation"]);
        } else {
            $(".numeroEtudiant").val(valeurs["numeroEtudiant"]);
            $(".numeroNational").val(valeurs["numeroNational"]);
            $("select option[value='" + valeurs["parcours"] + "']").attr("selected", "selected");
        }
    });
});

function valider_utilisateur() {
    const urlParamsString = window.location.search;
    const urlParams = new URLSearchParams(urlParamsString);
    let payload = {}
    let type_utilisateur = $('.type-utilisateur').val();
    // Recuperer les parametres dans le formulaire
    payload["id"] = urlParams.get('idUtilisateur');
    const estCheck = $("#flexCheckChecked").is(":checked");

    payload["valide"] = estCheck ? "true" : "false";
    payload["typeUtilisateur"] = type_utilisateur;
    // Envoyer les parametres pour être sauver dans le backend via Fetch
    fetch("http://localhost/gestionstage/public/" + urlParams.get("typeUtilisateur") + "/valider", {
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
            text_alert = "nombre d'" + urlParams.get("typeUtilisateur") + " validé est " + response["count"];
            window.location.href = 'utilisateurs.php?alertMessage=' + text_alert;
        } else {
            $(".alert").addClass("alert-danger");
            text_alert = response["message"];
        }
        $(".alert").html(text_alert);


    });
}
</script>

</html>