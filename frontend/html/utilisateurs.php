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
        Enregistrement d'un utilisateur
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
                                    <th scope="col">First</th>
                                    <th scope="col">Last</th>
                                    <th scope="col">Handle</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">1</th>
                                    <td>Mark</td>
                                    <td>Otto</td>
                                    <td>@mdo</td>
                                </tr>
                                <tr>
                                    <th scope="row">2</th>
                                    <td>Jacob</td>
                                    <td>Thornton</td>
                                    <td>@fat</td>
                                </tr>
                                <tr>
                                    <th scope="row">3</th>
                                    <td>Larry</td>
                                    <td>the Bird</td>
                                    <td>@twitter</td>
                                </tr>
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
    console.log(sessionStorage.getItem("jwt"));
    fetch("http://localhost/gestionstage/public/enseignant/list", {
        method: "GET",
        headers: {
            'Authorization': 'Bearer ' + sessionStorage.getItem("jwt")
        }
    }).then((resultat) => resultat.json()).then((response) => {
        console.log(response);
    });
});
</script>

</html>