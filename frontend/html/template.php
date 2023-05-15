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
    <script src="https://kit.fontawesome.com/dabf916254.js" crossorigin="anonymous"></script>
    <title>
        Enregistrement d'un utilisateur
    </title>
    <style>

    </style>
</head>


<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto col-md-2 min-vh-100 p-2 d-flex flex-column sidebargauche">
                <a class="d-flex text-decoration-none mt-5 align-items-center text-dark menutext" href="">
                    <span class="fs-4 d-none d-sm-inline">Menu</span>
                </a>
                <hr class="ligne">
                <ul class="nav nav-pills flex-column mt-5">
                    <li class="nav-item">
                        <a href="#" class="nav-link text-dark">
                            <i class="fs-5 fa fa-guage"></i>
                            <span class="fs-5 d-none ms-3 d-sm-inline font-weight-bold">
                                Dashboard
                            </span>

                        </a>
                    </li>
                    <hr>
                    <li class="nav-item">
                        <a href="#" class="nav-link text-dark">
                            <i class="fs-5 fa fa-guage"></i>
                            <span class="fs-5 d-none ms-3 d-sm-inline">
                                Home
                            </span>

                        </a>
                    </li>
                    <hr>
                    <li class="nav-item">
                        <a href="#" class="nav-link text-dark">
                            <i class="fs-5 fa fa-guage"></i>
                            <span class="fs-5 d-none ms-3 d-sm-inline">
                                Disable
                            </span>

                        </a>
                    </li>
                </ul>
                <div class="donnee-utilisateur">
                    <i class="fa fa-user" aria-hidden="true"></i>
                    <br>
                    <span id="nom_prenom"></span>
                    <br>
                    <span id="email"></span>
                    <br>
                    <span id="type_utilisateur"></span>
                    <br>
                    <a href="#" onclick="logout()">Se deconnecter</a>
                </div>
            </div>
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

    <!-- https: //www.youtube.com/watch?v=nVuDtzqkalo
    https: //www.youtube.com/watch?v=U4ftsqSt81w
    https: //www.youtube.com/watch?v=DZKf9l42WCo -->
</body>
<script>
$(document).ready(function() {
    let donnee_utilisateur = JSON.parse(sessionStorage.getItem("donnee_utilisateur"))
    if (donnee_utilisateur) {
        $("#nom_prenom").text(donnee_utilisateur["nom"] + " " + donnee_utilisateur["prenom"]);
        $("#email").text(donnee_utilisateur["email"]);
        $("#type_utilisateur").text(donnee_utilisateur["type"]);
    } else {
        $("#nom_prenom").text("Non connecté");
    }

});

function logout() {
    sessionStorage.removeItem('jwt');
    sessionStorage.removeItem("donnee_utilisateur");
    window.location.href = 'index.php';
}
</script>

</html>