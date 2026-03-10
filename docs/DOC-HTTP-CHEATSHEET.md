# Aide-Mémoire : Routage & Structure URL

## 1. Anatomie d'une URL

`https://www.monsite.fr:80/articles/voir?id=12#sommaire`

| Partie | Nom | Rôle |
| --- | --- | --- |
| **https** | **Scheme** | Le protocole (sécurité). |
| **www.monsite.fr** | **Host** | L'adresse du serveur (IP ou domaine). |
| **:80** | **Port** | La porte d'entrée (80 = HTTP, 443 = HTTPS). |
| **/articles/voir** | **Path** | **Le chemin** (utilisé par le Router). |
| **?id=12** | **Query** | Les données (clé=valeur). |
| **#sommaire** | **Anchor** | Repère interne (invisible pour le serveur). |

---

## 2. Le Routage Back-end (PHP)

Le serveur **Apache** utilise un fichier `.htaccess` pour rediriger le trafic vers un **Front Controller** (`index.php`).

### Mécanisme du .htaccess

1. **Vérification :** Le fichier existe-t-il physiquement ?
    * *Oui* : Apache le donne directement (ex: `style.css`).
    * *Non* : On passe à la suite.

2. **Réécriture :** On envoie tout vers `index.php`.
3. **Analyse :** PHP lit `$_SERVER['REQUEST_URI']` et appelle la bonne fonction.

---

## 3. Le Routage Front-end (JavaScript / SPA)

Dans une **SPA** (*Single Page Application*), le navigateur ne recharge jamais la page.

* **Interception :** On empêche le comportement par défaut des liens avec `event.preventDefault()`.
* **API History :** On change l'URL "artificiellement" avec `window.history.pushState()`.
* **Événement :** On écoute `window.onpopstate` pour gérer les boutons "Précédent/Suivant".

---

## 4. Les Regex (Pour les routes dynamiques)

Pour gérer des URLs variables comme `/article/42`, on utilise des expressions régulières :

* `^` : Début de la chaîne.
* `(\d+)` : Capture un ou plusieurs chiffres (l'ID).
* `$` : Fin de la chaîne.

**Exemple JS :** `const match = path.match(/^\/article\/(\d+)$/);`

---

## 5. Lexique Rapide

* **Front Controller :** Un script unique (`index.php`) par lequel passent toutes les requêtes.
* **Middleware :** Code qui s'exécute *avant* d'arriver au contrôleur final (ex: vérifier si l'utilisateur est connecté).
* **Immuabilité (PSR-7) :** Concept où l'on ne modifie pas une requête, on en crée une nouvelle copie modifiée.

---


### TP : Mon Premier Routeur PHP

Exercice "à trous" conçu pour tester leur compréhension du **routage PHP** et de la manipulation de l'**URI**.

L'objectif est de compléter le script pour qu'un utilisateur tapant `monsite.fr/produit/42` voie s'afficher le bon message.

**Consigne :** Remplace les symboles `[...]` par le code correct.

```php
<?php

/**
 * 1. ANALYSE DE L'URL
 * On récupère le chemin tapé par l'utilisateur
 */
$path = $_SERVER['[...]']; // Quel index de $_SERVER contient l'URL ?

/**
 * 2. CONFIGURATION DES ROUTES
 * On crée un tableau qui associe un "Path" à une fonction
 */
$routes = [
    '/' => function() {
        echo "Bienvenue sur l'accueil";
    },
    '/contact' => function() {
        echo "Formulaire de contact";
    }
];

/**
 * 3. LOGIQUE DU ROUTEUR
 * On vérifie si le chemin existe dans notre tableau
 */
if ([...]($path, $routes)) { // Quelle fonction PHP vérifie si une clé existe dans un tableau ?
    
    // On récupère la fonction associée au chemin
    $action = $routes[$path];
    
    // On exécute la fonction
    $action();

} else {
    // Gestion de la page non trouvée
    echo "Erreur [...]"; // Quel code d'erreur HTTP correspond à "Non trouvé" ?
}

```

### Questions de réflexion (Bonus)

1. **Le .htaccess :** Pour que ce fichier `index.php` reçoive effectivement le chemin `/contact`, quelle condition doit-on écrire dans le `.htaccess` pour éviter que le serveur ne cherche un dossier réel ?
* *Réponse à trou :* `RewriteCond %{REQUEST_FILENAME} [...]`


2. **Dynamisme :** Si je veux gérer `/produit/1`, `/produit/2`, etc., pourquoi le tableau `$routes` tel qu'il est écrit ici ne suffit-il plus ?

---

### Correction (Pour vous)

1. `$_SERVER['REQUEST_URI']`
2. `array_key_exists` (ou `isset`)
3. `404`
4. **Bonus 1 :** `!-d` (ou `!-f`)
5. **Bonus 2 :** Car on ne peut pas lister une infinité de clés. Il faudrait utiliser des **expressions régulières (Regex)** ou découper la chaîne avec `explode()`.

---


### 🛠️ TP : Mon Premier Routeur JavaScript (SPA)

version **JavaScript (SPA)** de l'exercice à trous. C'est un excellent moyen de leur montrer que, bien que le langage change, la logique de "clé/valeur" pour les routes reste identique.

**Consigne :** Complète les parties manquantes `[...]` pour faire fonctionner la navigation sans rechargement de page.

```javascript
/**
 * 1. DEFINITION DES PAGES
 * Un objet où chaque clé est un chemin et chaque valeur une fonction
 */
const routes = {
    "/": () => "<h1>Accueil</h1>",
    "/profil": () => "<h1>Mon Profil</h1>",
    "404": () => "<h1>Page introuvable</h1>"
};

/**
 * 2. LE MOTEUR DU ROUTEUR
 */
function handleLocation() {
    // Quelle propriété de window.location donne le chemin (ex: /profil) ?
    const path = window.location.[...]; 
    
    // On récupère la fonction ou la 404 par défaut
    const renderAction = routes[path] || routes["404"];
    
    // On injecte le HTML dans la div "app"
    document.getElementById("app").innerHTML = [...]; 
}

/**
 * 3. NAVIGATION SANS RECHARGEMENT
 */
window.route = (event) => {
    // Empêcher le navigateur de recharger la page
    event.[...](); 
    
    // Récupérer l'URL du lien cliqué
    const url = event.target.href;
    
    // Ajouter l'URL à l'historique du navigateur sans recharger
    window.history.[...]({}, "", url); 
    
    // Appeler le rendu
    handleLocation();
};

// Gérer le bouton "Précédent" du navigateur
window.onpopstate = handleLocation;

// Premier affichage au chargement
handleLocation();

```

---

### Questions de réflexion (Bonus)

1. **Événement :** Pourquoi doit-on absolument utiliser `onpopstate` ? Que se passerait-il si on l'oubliait quand l'utilisateur clique sur la flèche "Retour" du navigateur ?
2. **Comparaison :** En PHP, on utilise `$_SERVER['REQUEST_URI']`. Quel est l'équivalent exact en JavaScript utilisé dans ce script ?

---

### Correction (Pour vous)

1. `pathname`
2. `renderAction()` (Il ne faut pas oublier d'exécuter la fonction !)
3. `preventDefault()`
4. `pushState`
5. **Bonus 1 :** L'URL changerait dans la barre d'adresse, mais le contenu de la page ne se mettrait pas à jour (le JS ne serait pas prévenu du changement).
6. **Bonus 2 :** `window.location.pathname`


--- 


Voici trois projets progressifs pour mettre en pratique ces concepts. Ils sont conçus pour être réalisés en binôme ou en autonomie.

---

## Projet 1 : Le CMS "Static-PHP" (Focus Back-end)

**Objectif :** Créer un site dont le contenu est stocké dans des fichiers mais accessible via des URLs propres.

* **Le concept :** L'étudiant crée un dossier `pages/` contenant `accueil.html`, `services.html` et `contact.html`.
* **La mission :**
1. Mettre en place le `.htaccess` pour rediriger vers `index.php`.
2. Dans `index.php`, récupérer le `PATH_INFO`.
3. Vérifier si un fichier correspondant existe dans le dossier `pages/`.
4. Si oui, faire un `include` du fichier. Sinon, afficher une 404 personnalisée.


* **Le petit plus :** Ajouter un fichier `header.php` et `footer.php` pour que toutes les pages partagent le même design.

---

## Projet 2 : Le Portfolio "No-Refresh" (Focus Front-end)

**Objectif :** Créer une galerie de projets ultra-rapide en utilisant l'API History de JavaScript.

* **Le concept :** Une seule page HTML qui contient une liste de projets (format JSON ou simple objet JS).
* **La mission :**
1. Cliquer sur une vignette de projet doit changer l'URL en `/projet/nom-du-projet`.
2. Le JavaScript doit intercepter le clic et mettre à jour une zone `<div id="view">` avec les détails du projet.
3. Gérer le bouton "Retour" pour que l'utilisateur puisse revenir à la liste sans recharger.


* **Le petit plus :** Ajouter une petite animation de transition (fondu) lors du changement de contenu.

---

## Projet 3 : Le "Mini-Framework" (Synthèse)

**Objectif :** Construire son propre système de routage inspiré des frameworks pros (comme Laravel ou Symfony).

* **Le concept :** Créer une classe `Router` capable de gérer des routes statiques et dynamiques.
* **La mission :**
1. Implémenter une méthode `addRoute($path, $callback)`.
2. Utiliser les **Regex** pour capturer des paramètres (ex: `/user/(\d+)`).
3. Passer ces paramètres en arguments à la fonction `$callback`.


* **Le petit plus :** Essayer d'implémenter ce même routeur en PHP **ET** en JavaScript pour comparer les structures d'objets.

---

### 💡 Conseil pédagogique pour ces projets

Demandez aux élèves de commencer par le projet PHP. Une fois qu'ils ont compris comment le serveur "pense", le passage au JavaScript est beaucoup plus intuitif car ils réalisent que le JS "simule" simplement ce que le serveur faisait auparavant.

**Souhaitez-vous que je développe le cahier des charges détaillé (étapes par étapes) pour l'un de ces trois projets ?**