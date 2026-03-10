## Correction : Structure des URI et URL

| N° | Réponse | Pourquoi ? |
| --- | --- | --- |
| **1** | **B** | Le **Scheme** (ex: `https`, `ftp`) définit la méthode de transport des données (le protocole). |
| **2** | **C** | L'**Ancre** (`#`) est utilisée uniquement par le navigateur pour sauter à un élément précis (ID) dans la page. |
| **3** | **B** | Le **Port** suit toujours le domaine après un `:`. C'est le canal spécifique d'entrée sur la machine cible. |
| **4** | **B** | Le **`?`** marque la fin de l'URL de base et le début des paramètres dynamiques. |
| **5** | **C** | L'**esperluette (`&`)** est le connecteur logique qui permet de lister plusieurs paramètres (`key1=val1&key2=val2`). |
| **6** | **A** | Toute URL est une URI, mais l'URL est plus spécifique car elle indique *comment* et *où* localiser la ressource. |
| **7** | **C** | Le **Path** est l'arborescence (le dossier virtuel) que le routeur utilise pour orienter la requête. |
| **8** | **B** | Les Regex utilisent des symboles comme `(\d+)` pour extraire des valeurs variables (IDs, slugs) directement depuis le Path. |
| **9** | **B** | Le serveur reçoit uniquement `page.html`. Le fragment `#section2` est "coupé" par le navigateur avant l'envoi de la requête. |
| **10** | **B** | Le port **443** est le standard de l'industrie pour les flux chiffrés (HTTPS). |
| **11** | --> | Le serveur reçoit le Path (`/search`) et la Query String (`q=php`). Il ignore totalement `#resultats`. |

---

### A retenir pour la suite

Connaître de la structure d'un URL est indispensable pour comprendre comment le serveur Web, PHP et JavaScript "interprètent" une demande utilisateur.

Souvenez-vous que le serveur Web (Apache ou autre) ne voit que le **Path** et la **Query String**. Tout ce qui concerne l'affichage visuel précis (le scroll vers une section via l'ancre) est 100% géré par le navigateur du client.

