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
        Nombre limite
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
                    <div class="col-md-12 content">
                        <!--Debut alert pour afficher le message de succès ou d'échec-->
                        <div class="alert" hidden role="alert">
                        </div>
                        <!--Fin alert pour afficher le message de succès ou d'échec-->
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="text-left">Nombre limite</h3>
                            </div>
                            <div class="col-md-6 float-left">
                                <div class="lien-ajouter">
                                    <a href="ajouter_nombre_limite.php">Ajouter</a>
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
                                    <th scope="col">Nombre limite</th>
                                    <th scope="col">Période</th>
                                    <th scope="col">Actions</th>
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
    fetch("http://localhost/gestionstage/public/nombreStage/list?idUtilisateur=" + donnee_utilisateur["id"], {
        method: "GET",
        headers: {
            'Authorization': 'Bearer ' + sessionStorage.getItem("jwt")
        }
    }).then((resultat) => resultat.json()).then((response) => {
        if (response["data"].length > 0) {
            response["data"].forEach((value) => {
                $(".utilisateur-list").append(
                    "<tr class='" + (estPaire(count) == 0 ? "table-primary" : "") +
                    "'><th scope='row'>" + count +
                    "</th><td>" + value["nombre"] + "</td><td>" + value[
                        "periode"] +
                    "</td><td><a href='edit_nombre_limite.php?idNombreStage=" +
                    value["idNombreStage"] +
                    "' >Edit</a></td></tr>"
                );
                // <td><a href='edit_stage.php?idStage=" +
                //     value["idStage"] +
                //     "' class='retrouver-utilisateur'>Edit</a></td>
                count++;
            });
        } else {
            $(".alert").removeAttr("hidden");
            $(".alert").addClass("alert-info");
            $(".alert").html("Pas de nombre limite existants");
        }

    });

});

function estPaire(nombre) {
    return nombre % 2;
}
</script>

</html>