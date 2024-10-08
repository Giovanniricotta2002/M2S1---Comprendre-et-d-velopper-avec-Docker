#!/usr/local/bin/python3 //localisation de python pour linux
#coding:utf-8 //le type de codage

from lecture_json_et_ecriture_BDD import infile, mail_envoi, extract_ip, servhttp_to_mysql, replace ##fonction utiliser et crée par moi
import http.server ## librairie pour faire marcher le serveur HTTP
import socketserver ## autre librairie pour faire marcher le serveur HTTP
from io import BytesIO ## pour envoilier et recevoir la réponce

PORT = 8080 ## port utiliser par le serveur HTTP
ip = extract_ip() ## ip de la raspberry detecter automatiquement

class MyHttpRequestHandler(http.server.SimpleHTTPRequestHandler): ## class custom pour parametrée POST et GET comme il faut
    def do_GET(self): ## fonction pour GET 
        if(self == '/data.json'): ## si "self" et identique a '/data.json' alors il envoie les information du fichier data.json
            self.path = '/data.json'
        elif(self == '/json_data.json'): ## si "self" et identique a '/json_data.json' alors il envoie les information du fichier json_data.json et utiliser pour les test
            self.path = '/json_data.json'
        return http.server.SimpleHTTPRequestHandler.do_GET(self) ## renvoie la valeur de la fonction do_GET
    def do_POST(self): ## fonction pour POST
        content_length = int(self.headers['Content-Length']) ## recupère la taille du header en nombre
        body = self.rfile.read(content_length) ## lis le header
        self.send_response(200) ## envoie que tout est OK
        self.end_headers() ## coupe le headers
        response = BytesIO() ## début de la partie reponse a l'utilisateur
        response.write(b'This is POST request. ') ## dit que POST et bien recu
        response.write(b'Received: ') ## et affiche ce que l'utilisateur envoie
        response.write(body)
        self.wfile.write(response.getvalue()) ## récupère la valeur de la réponse
        with open('data.json', 'w') as fh: ## crée un fichier
                fh.write(replace(body)) ## ecrit dans un fichier
        servhttp_to_mysql('data.json', ip) ## envoie les donnée du fichier
        obj_python = infile('data.json') ## recupère les valeur du fichier pour les mettre dans une variable en json
        alerte = obj_python['data']['alerte'] ## recupère la valeur de alerte dans une variabme
        if(alerte == True): ## si la valeur de la variable alerte et en true alors il envoi un mail
            mail_envoi('pio.ricotta@gmail.com', 'inondationdetection@gmail.com', 'raspiadmin', 'Alerte', 'Alerte vous avez trop de temperature')
 
Handler = MyHttpRequestHandler ## met la class dans une variable
 
def main(): ## fonction principale
    print(ip) ## affiche l'ip de la raspberry
    try: ## fait que le serveur fonction automatiquement 
        with socketserver.TCPServer((ip, PORT), Handler) as httpd:
            print("serving at port", PORT)
            httpd.allow_reuse_address = True
            httpd.serve_forever() ## fait en sorte que le serveur boucle a l'infini
    except KeyboardInterrupt: ## si l'utilisateur coupe le serveur affiche se message
        print (" entered, stopping web server....")
        httpd.server_close() ## ferme le serveur proprement

if __name__ == '__main__': ## permet dans la nouvel version de python de pouvoir inclure d'autre fichier dans le fichier principale
    main()






































#Handler = http.server.SimpleHTTPRequestHandler

#with socketserver.TCPServer((ip, PORT), Handler) as httpd:
#    print("serving at port", PORT)
#    httpd.serve_forever()
