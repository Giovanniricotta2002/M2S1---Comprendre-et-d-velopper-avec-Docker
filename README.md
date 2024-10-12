# M2S1---Comprendre-et-d-velopper-avec-Docker

docker --debug build --target init . -t init
docker --debug build --target web . -t web
docker --debug build --target bdd --build-arg MYSQL_PAS=<custom password> . -t bdd
docker --debug build --target api . -t api

docker network create my_network
docker volume create mysql

docker run --rm -d --network my_network -p 80:80 --name web web
docker run --rm -d --network my_network -e MYSQL_PASSWORD=<custom password> -e MYSQL_ROOT_PASSWORD=<custom password> -p 3306:3306 -v "mysql:/var/lib/mysql" --name bdd bdd
docker run --rm -d --network my_network --name api api
