<?php
    $corps =<<<EOT
      <form method="post" action="index.php?action={$cible}&idP={$idP}" name="form_user">
          <label>Nom de la plante :</label>
          <input type="text" name="nom" value="{$nom}" required><br>
          <span class="w3-text-red">{$erreur["nom"]}</span><br/>
          <label>Catégorie :</label>
          <select name="categorie">
            <option>Fleur</option>
            <option>Fruit</option>
            <option>Légume</option>
            <option>Arbre</option>
            <option>Herbe</option>
          </select><br>
          <span class="w3-text-red">{$erreur["categorie"]}</span><br/>
          <label>Prix :</label>
          <input type="number" name="prix" value="{$prix}"><br>
          <span class="w3-text-red">{$erreur["prix"]}</span><br/>

          <label>Date d'ajout :</label>
          <input type="date" name="dateP" value="{$dateP}"><br>
          <span class="w3-text-red">{$erreur["dateP"]}</span><br/>

          <input type="submit" value="Modifier" class="action" >
          <input type='submit' value='Retour' onclick='window.location.href="index.php?action=select"' class='action'>

      </form>

    EOT;
    $zonePrincipale=$corps ;
    ?>

