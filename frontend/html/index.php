<!DOCTYPE html>
<html>

<head>
    <!--Le CSS du bootstrap-->
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css" />
    <!--JavaScript du bootstrap-->
    <script src="../js/bootstrap.js"></script>
    <!--CSS personnalisé du formulaire d'inscription-->
    <link rel="stylesheet" type="text/css" href="../csspersonnalise/inscription.css" />
    <!--CSS personnalisé du formulaire d'authentification-->
    <link rel="stylesheet" type="text/css" href="../csspersonnalise/login.css" />
    <title>
        Login de l'utilisateur
    </title>
</head>

<body>
    <div class="container">
        <div class="row">
            <?php include 'text_accueil.php'?>

            <div class="col-md-5">
                <div class="row">
                    <!--Debut Titre du formulaire-->
                    <div class="col-md-6">
                        <h3 class="text-left">Formulaire du Login</h3>
                    </div>
                    <!--Fin titre du formulaire-->

                    <!--Debut lien vers le formulaire d'inscription-->
                    <div class="col-md-6">
                        <a href="inscriptionutilisateur.php" class="link-primary">Inscription d'un utilisateur</a>
                    </div>
                    <!--Fin lien vers le login-->
                </div>

                <!--Fin alert pour afficher le message de succès ou d'échec-->
                <div class="alert" hidden role="alert">
                </div>
                <!--Debut alert pour afficher le message de succès ou d'échec-->


                <!--Debut afficher une ligne-->
                <hr>
                <!--Fin afficher une ligne-->

                <!--Debut des champs du formulaire de login-->
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
                <div class="row">
                    <div class="col-md-4 ">
                        <button onclick="login()" class="btn btn-info">Login</button>
                    </div>
                </div>
                <!--Fin des champs du formulaire de login-->
            </div>
        </div>

    </div>
    <!--Le pied de page-->
    <?php include 'footer.php'?>
    <!--Charger jquery-->
    <script src="../jquery-3.6.4.js"></script>
</body>
<script>
// Fonction pour décoder la clé token
function parseJwt(token) {
    var base64Url = token.split('.')[1];
    var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
    var jsonPayload = decodeURIComponent(window.atob(base64).split('').map(function(c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));

    return JSON.parse(jsonPayload);
}

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
        // Stocker la clé token dans la session
        sessionStorage.setItem("jwt", response["jwt"]);
        // Décoder la clé token pour récuperer les informations de l'utilisateur connecté
        donnee_utilisateur = parseJwt(response["jwt"])
        // Stocker les informations de l'utilisateur dans la sessions
        sessionStorage.setItem("donnee_utilisateur", JSON.stringify(donnee_utilisateur["data"]));
        let text_alert = "";
        // Faire apparaitre l'alert pour afficher un message de succes ou d'erreur
        $(".alert").removeAttr("hidden");
        // reinitialiser le type d'alert
        $(".alert").removeClass("alert-success");
        $(".alert").removeClass("alert-danger");
        //affiche le message de reussite ou d'echeck
        if (response["status"] === "ok") {
            $(".alert").addClass("alert-success");
            // recuperer les
            let page_accueil = donnee_utilisateur["data"]["pages"].filter(function(page) {
                return page["page_accueil"] === true;
            })[0];
            window.location.href = page_accueil["page"] + '.php';
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