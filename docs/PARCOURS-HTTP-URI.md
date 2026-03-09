# URI et URL


## 1. Distinction : URL vs URI

Pour faire simple : l'**URI** est l'identifiant (le nom de la ressource), tandis que l'**URL** est l'adresse (comment on y accède). Dans le web, on utilise presque toujours le terme URL par abus de langage.

### Schéma complet d'une URL

---

## 2. Anatomie d'une URL : Les 5 composants clés

Prenons l'exemple suivant :
`https://www.monsite.fr:8080/articles/voir?id=12&lang=fr#sommaire`

### A. Le Protocole (Scheme)

* **Exemple :** `https://`
* **Rôle :** Définit les règles de communication.
* **Pour les débutants :** `http` est non sécurisé, `https` est sécurisé (crypté). On trouve aussi `sftp://` pour les fichiers, `mailto:` pour les emails, `tel:` pour les téléphones.

### B. Le Domaine ou IP (Host)

* **Exemple :** `www.monsite.fr` (ou `127.0.0.1` en local).
* **Rôle :** Identifie le serveur sur lequel se trouve la ressource.
* **Note :** Le **Port** (ex: `:8080`) est souvent caché. Par défaut, c'est `80` pour le HTTP et `443` pour le HTTPS.

### C. Le Chemin (Path)

* **Exemple :** `/articles/voir`
* **Rôle :** Indique l'emplacement de la ressource sur le serveur.
* **Lien avec le routage :** C'est cette partie que votre **Router** (PHP ou JS) analyse pour savoir quel contrôleur appeler.

### D. Les Paramètres (Query String)

* **Exemple :** `?id=12&lang=fr`
* **Rôle :** Transmet des données supplémentaires à la page.
* **Syntaxe :** Commence par un `?`. Les couples clé/valeur sont séparés par un `&`.
* **En PHP :** On les récupère via la superglobale `$_GET`.

### E. L'Ancre (Fragment / Anchor)

* **Exemple :** `#sommaire`
* **Rôle :** Pointer vers un endroit précis **à l'intérieur** de la page (ex: un titre).
* **Particularité :** C'est la seule partie de l'URL qui n'est **jamais envoyée au serveur**. Seul le navigateur l'utilise.

---

## 3. Tableau Récapitulatif

| Composant | Nom technique | Exemple | Utile pour le serveur ? |
| --- | --- | --- | --- |
| **Protocole** | Scheme | `https` | Oui |
| **Domaine** | Host | `monsite.fr` | Oui |
| **Chemin** | Path | `/contact` | **Crucial pour le routage** |
| **Paramètres** | Query String | `?id=5` | Oui (données variables) |
| **Ancre** | Fragment | `#footer` | **Non** (Front uniquement) |

---

## 4. Exercice de compréhension

**Question :** Si l'utilisateur tape `https://google.com/search?q=php#resultats`, quelle partie exacte le script PHP sur le serveur va-t-il recevoir pour décider quoi afficher ?

* **Réponse :** Le serveur reçoit le Path (`/search`) et la Query String (`q=php`). Il ignore totalement `#resultats`.

---

### 💡 QCM : Structure URL et Routage

**1. Quelle partie de l'URL n'est JAMAIS envoyée au serveur ?**

* A) Le protocole (Scheme)
* B) Le chemin (Path)
* C) L'ancre (Fragment)

**2. Dans l'URL `https://mon-app.com/produits?tri=prix`, à quoi correspond `/produits` ?**

* A) Le Host
* B) Le Path
* C) La Query String

**3. Quel fichier du serveur Apache permet de dire "Envoie toutes les requêtes vers index.php" ?**

* A) config.php
* B) .htaccess
* C) router.js

**4. Dans une SPA (Single Page Application), quel outil JS permet de changer l'URL sans recharger la page ?**

* A) L'API History (`pushState`)
* B) La fonction `window.location.reload()`
* C) L'événement `onscroll`

**5. Si un utilisateur demande `/img/logo.png` et que ce fichier existe sur le serveur, que fait le `.htaccess` (avec les règles standards) ?**

* A) Il redirige quand même vers `index.php`
* B) Il ignore la réécriture et laisse Apache servir l'image directement
* C) Il affiche une erreur 404

---

### 🔑 Correction pour l'enseignant

1. **C** (L'ancre reste côté client dans le navigateur).
2. **B** (C'est le chemin utilisé par le Router pour aiguiller la requête).
3. **B** (C'est le rôle du moteur de réécriture d'Apache).
4. **A** (C'est la base du routage côté client).
5. **B** (Grâce à la condition `RewriteCond %{REQUEST_FILENAME} !-f`).

---

### 🎯 Activité pratique suggérée

Demandez à vos élèves d'ouvrir la **console développeur** (F12) de leur navigateur sur un site comme Wikipédia, d'aller dans l'onglet **Console** et de taper :

```javascript
console.log(window.location.pathname);
console.log(window.location.search);

```

Cela leur permet de voir "en vrai" comment le JavaScript segmente l'URL qu'ils ont sous les yeux.

**Souhaitez-vous que je rédige un "aide-mémoire" (Cheat Sheet) au format PDF/Markdown qu'ils pourraient garder pour leurs futurs projets ?**