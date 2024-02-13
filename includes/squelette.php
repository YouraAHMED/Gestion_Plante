<!DOCTYPE html>
<html lang="fr">
  <head>
    <title><?php echo $titre; ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/form.css"  type="text/css" />

    <script src="js/script.js"></script>

  </head>
  <body>
    <header>
      <h1>Gestion des plantes</h1>


      <!-- formulaire de recherche -->
      <form method="post" action="index.php?action=search">
        <input type="text" name="recherche" placeholder="Search" class="search" />
        <input type="submit" value="Rechercher" class="search" />
      </form>
    </header>
      <p>
      <input type="button" value="Ajouter une plante" onclick="self.location.href='index.php?action=insert'" class="action" />
        <input type="button" value="Liste des plantes" onclick="self.location.href='index.php?action=liste'" class="action" />
        <input type="button" value="À propos" onclick="self.location.href='index.php?action=apropos'" class="action" />
      </p>
    <main>
      
      <h2><?php echo $titre; ?></h2>

      <div id="zonePrincipale">

        <?php  echo $zonePrincipale; ?>
      </div>

      <div id="zoneImage">
        <img id="plante" src="Images/image1.jpg" alt="">
      </div>
    </main>


    <footer>
      Copyright © 2023 - Tous droits réservés.
    </footer>

  </body>


</html>
