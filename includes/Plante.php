<?php

class Plante
{

//Attributs
    private int $idP;
    private string $nom;
    private string $categorie;
    private int $prix;
    private string $dateP;


    //Constructeur
    public function __construct($idP, $nom, $categorie, $prix, $dateP)
    {
        $this->idP = $idP;
        $this->nom = $nom;
        $this->categorie = $categorie;
        $this->prix = $prix;
        $this->dateP = $dateP;
    }

    //
    public function __toString()
    {
        return $this->idP . " " . $this->nom . " " . $this->categorie . " " . $this->prix . " " . $this->dateP;
    }

    //Getters

    /**
     * Fonction qui retourne l'id de la plante
     * @return int
     */
    public function getIdP()
    {
        return $this->idP;
    }

    /**
     * Fonction qui retourne le nom de la plante
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Fonction qui retourne la catégorie de la plante
     * @return string
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Fonction qui retourne le prix de la plante
     * @return int
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Fonction qui retourne la date de la plante
     * @return string
     */
    public function getDateP()
    {
        return $this->dateP;
    }
}
