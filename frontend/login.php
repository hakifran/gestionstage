<!DOCTYPE html>
<html>

<head>
    <script src="jquery-3.6.4.js"></script>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
    <script src="js/bootstrap.js"></script>
    <link rel="stylesheet" type="text/css" href="csspersonnalise/inscription.css" />
    <link rel="stylesheet" type="text/css" href="csspersonnalise/login.css" />
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
                        <h3 class="text-left">Formulaire du Login</h3>
                    </div>
                </div>
                <div class="alert" hidden role="alert">
                </div>
                <hr>

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
                            <option value="admin">Administateur</option>
                        </select>
                    </div>
                </div>

                <button onclick="login()" class="btn btn-info">Login</button>
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

function login() {
    let payload = {}
    let type_utilisateur = $('.type-utilisateur').val();
    // Recuperer les parametres dans le formulaire
    payload["email"] = $(".email").val() === "" ? null : $(".email").val();
    payload["password"] = $(".password").val() === "" ? null : $(".password").val();
    payload["typeUtilisateur"] = type_utilisateur;
    // Envoyer les parametres pour être sauver dans le backend via Fetch
    fetch("http://localhost/gestionstage/public/connexion/login", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            'Authorization': 'Basic ' + btoa("admin" + ":" + "@admin")
        },
        body: JSON.stringify(payload)
    }).then((resultat) => resultat.json()).then((response) => {
        console.log(response)
        let text_alert = "";
        // Faire apparaitre l'alert pour afficher un message de succes ou d'erreur
        $(".alert").removeAttr("hidden");
        // reinitialiser le type d'alert
        $(".alert").removeClass("alert-success");
        $(".alert").removeClass("alert-danger");
        //affiche le message de reussite ou d'echeck
        if (response["status"] === "ok") {
            $(".alert").addClass("alert-success");
            text_alert = response["message"];
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