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
    <!-- <script src="https://kit.fontawesome.com/dabf916254.js" crossorigin="anonymous"></script> -->
    <title>
        Liste des stages
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
                    <div class="col-md-12 content">
                        <!--Debut alert pour afficher le message de succès ou d'échec-->
                        <div class="alert" hidden role="alert">
                        </div>
                        <!--Fin alert pour afficher le message de succès ou d'échec-->
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="text-left">Liste des stages</h3>
                            </div>
                            <div class="col-md-6 float-left">
                                <div class="lien-ajouter">
                                    <a href="ajouter_stage.php">Ajouter</a>
                                </div>

                            </div>
                        </div>


                        <!--Debut afficher une ligne-->
                        <hr>
                        <!--Fin afficher une ligne-->
                        <table class="table">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Projet</th>
                                    <th scope="col">Entreprise</th>
                                    <th scope="col">Adresse</th>
                                    <th scope="col" class="type-utilisateur">Tuteur</th>
                                    <th scope="col">Periode</th>
                                    <th scope="col">Attribue</th>
                                    <th scope="col">valide</th>
                                    <!-- <th scope="col">Action</th> -->
                                </tr>
                            </thead>
                            <tbody class="utilisateur-list">

                            </tbody>
                        </table>
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
    const alertMessage = urlParams.get("alertMessage");
    const donnee_utilisateur = JSON.parse(sessionStorage.getItem("donnee_utilisateur"));
    if (alertMessage != undefined && alertMessage != null) {
        // Faire apparaitre l'alert pour afficher un message de succes ou d'erreur
        $(".alert").removeAttr("hidden");
        $(".alert").addClass("alert-success");
        $(".alert").html(alertMessage);
    }
    // recuperer tous les enseignants
    let count = 1
    fetch("http://localhost/gestionstage/public/stage/list?idUtilisateur=" + donnee_utilisateur["id"] +
        "&attribue=false&all=true", {
            method: "GET",
            headers: {
                'Authorization': 'Bearer ' + sessionStorage.getItem("jwt")
            }
        }).then((resultat) => resultat.json()).then((response) => {
        if (response["data"].length > 0) {
            response["data"].forEach((value) => {
                const typeUtilisateur = donnee_utilisateur["type"] === "enseignant" ?
                    "Etudiant" :
                    "Tuteur";
                const autreTypeUtilisateur = donnee_utilisateur["type"] === "enseignant" ?
                    "Etudiant" :
                    "Enseignant";

                $(".type-utilisateur").text(typeUtilisateur);
                "Etudiant";
                $(".utilisateur-list").append(
                    "<tr class='" + (estPaire(count) == 0 ? "table-primary" : "") +
                    "'><th scope='row'>" + count +
                    "</th><td>" + value["intituleProjet"] + "</td><td>" + value[
                        "nomEntreprise"] +
                    "</td><td>" + value["adresse"] +
                    "</td><td>" + (value["nom" + autreTypeUtilisateur] == null ? "-" :
                        value["nom" + autreTypeUtilisateur]) +
                    "</td><td>" + value["periode"] +
                    "</td><td><input class='form - check - input' type='checkbox' " +
                    (value["attribue"] == 1 ? "checked" : "") +
                    " disabled></td><td><input class='form - check - input' type='checkbox' " +
                    (value["stage_valide"] == 1 ? "checked" : "") +
                    " disabled></td></tr>"
                );
                // <td><a href='edit_stage.php?idStage=" +
                //     value["idStage"] +
                //     "' class='retrouver-utilisateur'>Edit</a></td>
                count++;
            });
        } else {
            $(".alert").removeAttr("hidden");
            $(".alert").addClass("alert-info");
            $(".alert").html("Pas de stage pour cet utilisateur");
        }

    });

});

function estPaire(nombre) {
    return nombre % 2;
}
</script>

</html>