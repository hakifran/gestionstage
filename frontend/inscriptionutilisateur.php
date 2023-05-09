<!DOCTYPE html>
<html>

<head>
    <script src="jquery-3.6.4.js"></script>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
    <script src="js/bootstrap.js"></script>
    <link rel="stylesheet" type="text/css" href="csspersonnalise/inscription.css" />
    <title>
        Enregistrement d'un utilisateur
    </title>

</head>

<body>
    <div class="container">
        <div class="row">
            <?php include 'text_accueil.php'?>
            <div class="col-md-5">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="text-left">Formulaire d'inscription</h3>
                    </div>
                </div>
                <div class="alert" hidden role="alert">
                </div>
                <hr>
                <div class="row">
                    <label class="label col-md-3 control-label">Nom</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control nom" name="Nom" placeholder="Nom">
                    </div>
                </div>
                <div class="row">
                    <label class="label col-md-3 control-label">Prenom</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control prenom" name="Prenom" placeholder="Prenom">
                    </div>
                </div>
                <div class="row">
                    <label class="label col-md-3 control-label">E-mail</label>
                    <div class="col-md-9">
                        <input type="Email" class="form-control email" name="Email" placeholder="Email">
                    </div>
                </div>
                <div class="row">
                    <label class="label col-md-3 control-label">Password</label>
                    <div class="col-md-9">
                        <input type="password" class="form-control password" name="Password" placeholder="Password">
                    </div>
                </div>
                <div class="row">
                    <label class="label col-md-3 control-label">Type utilisateur</label>
                    <div class="col-md-9">
                        <select class="form-control type-utilisateur">
                            <option value="enseignant">Enseignant</option>
                            <option value="etudiant">Étudiant</option>
                        </select>
                    </div>
                </div>
                <div class="row enseignant">
                    <label class="label col-md-3 control-label">Titre</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control titre" name="Titre" placeholder="Titre">
                    </div>
                </div>
                <div class="row enseignant">
                    <label class="label col-md-3 control-label">Spécialisation</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control specialisation" name="Specialisation"
                            placeholder="Specialisation">
                    </div>
                </div>
                <div class="row etudiant">
                    <label class="label col-md-3 control-label">Numero étudiant</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control numeroEtudiant" name="NumeroEtudiant"
                            placeholder="Numero étudiant">
                    </div>
                </div>
                <div class="row etudiant">
                    <label class="label col-md-3 control-label">Numero national</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control numeroNational" name="NumeroNational"
                            placeholder="Numero national">
                    </div>
                </div>
                <div class="row etudiant">
                    <label class="label col-md-3 control-label">Parcours</label>
                    <div class="col-md-9">
                        <select class="form-control parcours">
                            <option>Parcours 1</option>
                            <option>Parcours 2</option>
                            <option>Parcours 3</option>
                            <option>Parcours 4</option>
                        </select>
                    </div>
                </div>
                <button onclick="creer_utilisateur()" class="btn btn-info">Enregistre</button>
            </div>
        </div>
    </div>
    <?php include 'footer.php'?>

</body>
<script>
$(document).ready(function() {
    // quand la page charge ce sont les champs de l'enseignant qui apparaissent seulement

    $(".etudiant").hide();
    $(".enseignant").show();
    $('.type-utilisateur').on('change', function(e) {
        // Quand l'utilisateur choisi un type d'utilisateur certains champs sont caché et d'autre apparaissent
        // Et vis-versa
        if (this.value === "etudiant") {
            $(".enseignant").hide();
            $(".etudiant").show();
        } else {
            $(".etudiant").hide();
            $(".enseignant").show();
        }
    });
});

function creer_utilisateur() {
    let payload = {}
    let type_utilisateur = $('.type-utilisateur').val();
    // Recuperer les parametres dans le formulaire
    payload["nom"] = $(".nom").val() === "" ? null : $(".nom").val();
    payload["prenom"] = $(".prenom").val() === "" ? null : $(".prenom").val();
    payload["email"] = $(".email").val() === "" ? null : $(".email").val();
    payload["password"] = $(".password").val() === "" ? null : $(".password").val();
    if (type_utilisateur === "etudiant") {
        payload["numeroEtudiant"] = $(".numeroEtudiant").val() === "" ? null : $(".numeroEtudiant").val();
        payload["numeroNational"] = $(".numeroNational").val() === "" ? null : $(".numeroNational").val();
        payload["parcours"] = $(".parcours").val() === "" ? null : $(".parcours").val();
    } else {
        payload["titre"] = $(".titre").val() === "" ? null : $(".titre").val();
        payload["specialisation"] = $(".specialisation").val() === "" ? null : $(".specialisation").val();
    }
    // Envoyer les parametres pour être sauver dans le backend via Fetch
    fetch("http://localhost/gestionstage/public/" + type_utilisateur + "/create", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            'Authorization': 'Basic ' + btoa("admin" + ":" + "@admin")
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
            $(".alert").addClass("alert-success");
            text_alert = "l'" + type_utilisateur + " " + response["data"]["nom"] + " " + response["data"][
                "prenom"
            ] + " enregistré avec succès"
            //  reinitialiser les champs si c'est un succès
            $("input").val("");
        } else {
            $(".alert").addClass("alert-danger");
            text_alert = response["message"];
        }
        $(".alert").html(text_alert);


    });
}
</script>

</html>