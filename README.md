# M2S1---Comprendre-et-d-velopper-avec-Docker

docker --debug build --target web . -t web
docker --debug build --target bdd --build-arg MYSQL_PAS=pi2 . -t bdd
docker --debug build --target api . -t api
docker --debug build --target init . -t init

docker network create my_network

docker run --rm -d --network my_network -p 80:80 web
docker run --rm -d --network my_network -p 3306:3306 bdd
docker run --rm -d --network my_network api
