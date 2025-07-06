<?php

require_once "src/connexion.php";

if (isset($_GET["q"])) {
    $shortcut = htmlspecialchars($_GET["q"]);

    $recup = $connexion->prepare("SELECT COUNT(*) AS nombre FROM links WHERE short_url = ?");
    $recup->execute([$shortcut]);


    while ($resultat = $recup->fetch()) {

        if ($resultat["nombre"] != 1) {
            header("location: ./error=true&message=Adresse url non connue");
            exit();
        }
    }

    // Redirection
    $verifier = $connexion->prepare("SELECT * FROM links WHERE short_url = ?");
    $verifier->execute([$shortcut]);

    while ($resultat = $verifier->fetch()) {
        header('location: ' . $resultat["link_url"]);
        exit();
    }
}

if (!empty($_POST["url"])) {
    // Création et sécurisation des données dans une variable
    $url = htmlspecialchars($_POST["url"]);

    // Vérification du format de l'url
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        header("location: ./?error=true&message=Adresse url non valide");
        exit();
    }

    // Création du raccourci
    $shortcut = crypt($url, rand());

    // Vérifier les doublons (redondances)
    $requete = $connexion->prepare("SELECT COUNT(*) AS nombre FROM links WHERE link_url = ?");
    $requete->execute([$url]);

    while ($resultat = $requete->fetch()) {
        if ($resultat['nombre'] != 0) {
            header("location: ./?error=true&message=Adresse déja raccourcie");
            exit();
        }
    }

    // Ajout du raccourci
    $ajouter = $connexion->prepare("INSERT INTO links(link_url, short_url) VALUES (?, ?)");
    $ajouter->execute([$url, $shortcut]);

    header("location: http://localhost/BELIEVEMY_PHP/PROJETS/RACOURCISSEUR_URL/?short=$shortcut");
    exit();
}

?>

<html>

<head>
    <meta charset="utf-8">
    <title>BITLY - Raccourcissez vos urls</title>
    <link rel="stylesheet" href="design/default.css">
    <link rel="icon" type="image/png" href="assets/favicon.png">
</head>

<body>

    <!-- PRESENTATION -->
    <section id="main">

        <!-- CONTAINER -->
        <div class="container">

            <?php require_once("src/header.php"); ?>

            <!-- PROPOSITION -->
            <h1>Une url longue ? Raccourcissez-là ?</h1>
            <h2>Largement meilleur et plus court que les autres.</h2>

            <!-- FORM -->
            <form method="post" action="index.php">
                <input type="url" name="url" placeholder="Collez un lien à raccourcir">
                <input type="submit" value="Raccourcir">
            </form>

            <?php if (isset($_GET['error']) && isset($_GET['message'])) { ?>

                <div class="center">
                    <div id="result">
                        <b><?php echo htmlspecialchars($_GET['message']); ?></b>
                    </div>
                </div>

            <?php } else if (isset($_GET['short'])) { ?>

                <div class="center">
                    <div id="result">
                        <b>URL RACCOURCIE : </b>
                        http://localhost/BELIEVEMY_PHP/PROJETS/RACOURCISSEUR_URL/?q=<?php echo htmlspecialchars($_GET['short']); ?>
                    </div>
                </div>

            <?php } ?>

        </div>

    </section>

    <!-- MARQUES -->
    <section id="brands">

        <!-- CONTAINER -->
        <div class="container">
            <h3>Ces marques nous font confiance</h3>
            <img src="assets/1.png" alt="1" class="picture">
            <img src="assets/2.png" alt="2" class="picture">
            <img src="assets/3.png" alt="3" class="picture">
            <img src="assets/4.png" alt="4" class="picture">
        </div>

    </section>

    <!-- PIED DE PAGE -->
    <?php require_once("src/footer.php"); ?>
</body>

</html>