# M2S1---Comprendre-et-développer-avec-Docker

Ce document explique comment construire et exécuter des conteneurs Docker pour différentes cibles (init, web, bdd, api) tout en configurant un réseau Docker et des volumes pour les bases de données.

## 1. Construction des images Docker

Chaque commande suivante construit une image Docker spécifique pour différentes cibles dans votre application.

```bash
docker --debug build --target init . -t init
```
- Cette commande construit l'image Docker cible `init` en mode débogage, étiquetée `init`.

----

```bash
docker --debug build --target web . -t web
```

- Cette commande construit l'image cible `web` pour l'application web, en mode débogage.

----

```bash
docker --debug build --target bdd --build-arg MYSQL_PAS=<custom password> . -t bdd
```

- Ici, l'image Docker pour la base de données (`bdd`) est construite. L'argument de construction `MYSQL_PAS` permet de passer un mot de passe personnalisé pour MySQL.

----

```bash
docker --debug build --target api . -t api
```

- Cette commande construit l'image Docker pour l'API avec le tag `api`.

---

## 2. Création du réseau et du volume Docker

Avant de lancer les conteneurs, nous créons un réseau Docker pour permettre la communication entre les conteneurs et un volume pour persister les données de la base de données.

```bash
docker network create my_network
```

- Crée un réseau Docker nommé `my_network`. Les conteneurs dans ce réseau peuvent se connecter et communiquer entre eux.

```bash
docker volume create mysql
```

- Crée un volume Docker nommé `mysql` qui sera utilisé pour stocker les données de la base de données MySQL de manière persistante.

## 3. Lancement des conteneurs

Les commandes suivantes lancent les conteneurs pour l'application Web, la base de données et l'API.

```bash
docker run --rm -d --network my_network -e MYSQL_PASSWORD=<custom password> -e MYSQL_ROOT_PASSWORD=<custom password> -p 3306:3306 -v "mysql:/var/lib/mysql" --name bdd bdd
```

- Cette commande lance un conteneur pour la base de données MySQL (`bdd`) dans le réseau `my_network`, avec les mots de passe MySQL spécifiés dans les variables d'environnement. Le port `3306` de la machine hôte est mappé au port `3306` du conteneur. Le volume `mysql` est monté pour persister les données dans `/var/lib/mysql`.

- Il faudra modifier les fichiers fichier [lecture_json_et_ecriture_BDD ligne 54-56](api_python/lecture_json_et_ecriture_BDD.py) pour l'api et [/web/conf/bdd.php](web/conf/bdd.php) pour la vue web si les variables d'env sont pas bien mis

```bash
docker run --rm -d --network my_network -p 80:80 -e MYSQL_PASSWORD=<custom password> --name web web
```

- Lance le conteneur `web` dans le réseau `my_network`. Le port `80` de la machine hôte est mappé au port `80` du conteneur. Le conteneur est supprimé automatiquement après son arrêt (`--rm`) et fonctionne en arrière-plan (`-d`).

```bash
docker run --rm -d --network my_network -e MYSQL_PASSWORD=<custom password> --name api api
```

- Lance le conteneur `api` dans le réseau `my_network`, en arrière-plan, avec le nom `api`.

---

Avec ce guide, vous êtes en mesure de créer, configurer et exécuter des conteneurs Docker pour un environnement multi-services.
