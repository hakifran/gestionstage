<a class="d-flex text-decoration-none mt-5 align-items-center text-dark menutext" href="">
    <span class="fs-4 d-none d-sm-inline">Menu</span>
</a>
<hr class="ligne">
<ul class="nav nav-pills flex-column mt-5 menu-list">
</ul>
<div class="donnee-utilisateur">
    <!-- <i class="fa fa-user" aria-hidden="true"></i> -->
    <br>
    <span id="nom_prenom"></span>
    <br>
    <span id="email"></span>
    <br>
    <span id="type_utilisateur"></span>
    <br>
    <a href="#" onclick="logout()">Se deconnecter</a>
</div>
<script src="../jquery-3.6.4.js"></script>
<script>
$(document).ready(function() {
    let donnee_utilisateur = JSON.parse(sessionStorage.getItem("donnee_utilisateur"));

    if (donnee_utilisateur) {
        $("#nom_prenom").text(donnee_utilisateur["nom"] + " " + donnee_utilisateur["prenom"]);
        $("#email").text(donnee_utilisateur["email"]);
        $("#type_utilisateur").text(donnee_utilisateur["type"]);
        let pages = donnee_utilisateur["pages"]
        let html_code = "";
        pages.forEach((page) => {
            html_code +=
                "<li class='nav-item'><a href='" + page['page'] +
                ".php' class='nav-link text-dark'><i class='fs-5 " + page["icon"] +
                "'></i><span class='fs-5 d-none ms-3 d-sm-inline'>" + page["titre"] +
                "</span></a></li><hr>";
        });
        $(".menu-list").html(html_code);
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
