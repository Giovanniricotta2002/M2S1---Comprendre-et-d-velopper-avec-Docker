# Étape d'initialisation
FROM alpine:latest AS init

# Copier vos fichiers dans l'image
COPY ./api_python /api/
COPY ./bdd/bdd_detection_innondation.sql /bdd.sql
COPY ./web /app/

# Commande par défaut pour garder le conteneur actif
CMD ["echo", "3600"]

# Étape pour l'API
FROM python:alpine3.19 AS api

COPY --from=init /api /app

WORKDIR /app

RUN pip install mysql-connector-python

ENTRYPOINT ["python", "2py_http_serv.py"]

# Étape pour la base de données
FROM mysql:8.0.39-debian AS bdd

ARG MYSQL_PAS=pi2

# Copier le fichier SQL dans le répertoire d'initialisation
COPY --from=init /bdd.sql /docker-entrypoint-initdb.d/

# Définir les variables d'environnement
ENV MYSQL_DATABASE=bdd_detection_innondation
ENV MYSQL_USER=pi2
ENV MYSQL_PASSWORD=${MYSQL_PAS}

FROM php:8.3.12-apache-bullseye AS web

COPY --from=init /app /var/www/html/