import json  ## librairie pour le forma json
import smtplib ## librairie pour le serveur SMTP
import socket ## librairie pour me permettre d'avoir l'ip de la machine
import mysql.connector ## librairie pour mysql



def infile(file): ## fonction qui lis unh fichier et renvoie une variable en json
    fileObject = open(file, "r")
    jsonContent = fileObject.read()
    obj_python = json.loads(jsonContent)
    print(obj_python)
    return obj_python
    
def mail_envoi(emailto, emailuser, passworduser, subject, message): ## fonction qui permet d'envoilier des mais au utilisateur
    to = emailto
    gmail_user = emailuser
    gmail_pwd = passworduser
    smtpserver = smtplib.SMTP("smtp.gmail.com",587)
    smtpserver.ehlo()
    smtpserver.starttls()
    smtpserver.ehlo() # extra characters to permit edit
    smtpserver.login(gmail_user, gmail_pwd)
    header = 'To:' + to + '\n' + 'From: ' + gmail_user + '\n' + 'Subject:'+subject+' \n'
    print (header)
    msg = header + '\n '+message+' \n\n'
    smtpserver.sendmail(gmail_user, to, msg)
    print ('done!')
    smtpserver.quit()
    
def extract_ip(): ## fonction qui recupère l'ip
    st = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
    try:       
        st.connect(('10.255.255.255', 1))
        IP = st.getsockname()[0]
    except Exception:
        IP = '127.0.0.1'
    finally:
        st.close()
    return IP

def servhttp_to_mysql(fichier, ip): ## fonction qui permet de mettre les valeur dans la BDD
    obj_python = infile(fichier)


    piece = obj_python['data']['piece']
    th_temp = obj_python['data']['temp']
    th_hum = obj_python['data']['hum']
    th_veau = obj_python['data']['veau']
    alerte = obj_python['data']['alerte']


    connection_params = { ## variable qui permet la connection a la BDD
        'host': ip,
        'user': "pi2",
        'password': "",
        'database': "bdd_detection_innondation",
    }
    request = """INSERT INTO `data`(`temperature`, `humidite`, `Niveau_deau`, `piece`, `alerte`) 
                VALUES (%s, %s, %s, %s, %s)"""

    params = (th_temp, th_hum, th_veau, piece, alerte)


    with mysql.connector.connect(**connection_params) as db :
        with db.cursor() as c:
            #print("Debut de l'execution SQL")
            c.execute(request, params)
            #print("Execution de la requette SQL fini")
            db.commit()
            
def replace(body): ## fonction remplace les valeur d'une chaine de caractère en une autre
    decodeutf = body.decode('utf-8')
    replace_vide = decodeutf.replace("'", '')
    replace_data = replace_vide.replace("data", '"data"')
    replace_piece = replace_data.replace("piece", '"piece"')
    replace_temp = replace_piece.replace("temp", '"temp"')
    replace_hum = replace_temp.replace("hum", '"hum"')
    replace_veau = replace_hum.replace("veau", '"veau"')
    replace_alerte = replace_veau.replace("alerte", '"alerte"')
    str_body = str(replace_alerte)

    return str_body


#with mysql.connector.connect(**connection_params) as db :
#    with db.cursor() as c:
#        print("Debut de l'execution SQL")
#        c.execute(request, params)
#        print("Execution de la requette SQL fini")
#        db.commit()


#ip = '192.168.1.105' #mainson
#ip = '192.168.12.126' #lycee


#with socketserver.TCPServer(("", PORT), Handler) as httpd:
#    print("Http Server Serving at port", PORT)
#    httpd.serve_forever()


        #Handler = http.server.SimpleHTTPRequestHandler
