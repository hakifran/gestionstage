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
        Liste des utilisateurs
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
                    <div class="col-md-12 content">
                        <table class="table">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nom</th>
                                    <th scope="col">Prenom</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Valide</th>
                                    <th scope="col">Edit</th>
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
    // recuperer tous les enseignants
    let count = 1
    fetch("http://localhost/gestionstage/public/enseignant/list", {
        method: "GET",
        headers: {
            'Authorization': 'Bearer ' + sessionStorage.getItem("jwt")
        }
    }).then((resultat) => resultat.json()).then((response) => {
        let hey = "hello";
        response["data"].forEach((value) => {
            let typeUtilisateur = "enseignant";
            $(".utilisateur-list").append(
                "<tr class='" + (estPaire(count) == 0 ? "table-primary" : "") +
                "'><th scope='row'>" + count +
                "</th><td>" + value["nom"] + "</td><td>" + value["prenom"] +
                "</td><td>" + value["email"] +
                "</td><td>Enseignant</td><td><input class='form - check - input' type='checkbox' " +
                (value["valide"] == 1 ? "checked" : "") +
                " disabled></td><td><a href='valide_utilisateur.php?typeUtilisateur=enseignant&idUtilisateur=" +
                value["idEnseignant"] + "' class='retrouver-utilisateur'>Edit</a></td></tr>"
            );
            // <i class='fa fa-pencil'></i>
            count++;
        });
    });
    // recuperer tous les étudiants
    fetch("http://localhost/gestionstage/public/etudiant/list", {
        method: "GET",
        headers: {
            'Authorization': 'Bearer ' + sessionStorage.getItem("jwt")
        }
    }).then((resultat) => resultat.json()).then((response) => {
        let typeUtilisateur = "etudiant";

        response["data"].forEach((value) => {
            $(".utilisateur-list").append(
                "<tr class='" + (estPaire(count) == 0 ? "table-primary" : "") +
                "'><th scope='row'>" + count +
                "</th><td>" + value["nom"] + "</td><td>" + value["prenom"] +
                "</td><td>" + value["email"] +
                "</td><td>Etudiant</td><td><input class='form - check - input' type='checkbox' " +
                (value["valide"] == 1 ? "checked" : "") +
                " disabled></td><td><a href='valide_utilisateur.php?typeUtilisateur=etudiant&idUtilisateur=" +
                value["idEtudiant"] + "' class='retrouver-utilisateur'>Edit</a></td></tr>"
            );
            count++;
        });
        // <i class='fa fa-pencil'></i>
    });
});

function estPaire(nombre) {
    return nombre % 2;
}
</script>

</html>