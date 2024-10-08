<?php
    $base = $_SERVER['SCRIPT_NAME'];
    $curr_url_param = $_SERVER['REQUEST_URI'];
    //Variable avec l'URL sans les paramètres
    $curr_url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    //Variable($vartab) contenant un tableau associatif avec les paramètres de l'URL
    parse_str(parse_url($_SERVER["REQUEST_URI"], PHP_URL_QUERY),$vartab); 
    
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Détection d'inondation</title>
        <link rel="stylesheet" href="/style/index.css">
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    </head>
    <body>
        <div id="grid_body">
            <h1 class="titre"><a href="/">Menu Principal</a></h1>
            <div id="menu">
                <nav class="menu">
                    <ul id="grid_menu_piece">
                        <?php
                        $piece_pre = null;
                        $pieces = $data;
                        foreach ($pieces as $piece) {
                            if ($piece['piece'] != $piece_pre) {
                                $piece_pre = $piece['piece']; 
                                echo '<li id="piece"><a href="'.$base.'/piece?piece='.$piece['piece'].'">Piece</a></li>';
                            }
                        }
                        ?>
                    </ul>
                </nav>
            </div>
            <div class="affichage">
                <div id="buttom">
                    <?php
                    $periodique = isset($_POST['treel']);
                    $moyenne = isset($_POST['moyenne']);
                    $nbr = /*isset($_POST['nbrmoyenne'])*/4;
                    $namebuttom1 = "Revue Périodique (en temps réel)";
                    $namebuttom2 = "Revue Moyenne (".$nbr." Donnée)";
                    echo
                        '<form action="#" method="post">
                            <div id="buttom">
                                <input type="submit" name="treel" value="'.$namebuttom1.'">
                                <input type="submit" name="moyenne" value="'.$namebuttom2.'">
                            </div>
                        </form>'
                    ?>
                </div>
                <div id="diagrame">
                <?php
                    $array_t = array();
                    $temps = $data;
                    function graph($xaxis, $ligne1, $ligne2){
                        $pc = new C_PhpChartX(array($ligne1, $ligne2), 'basic_chart');
                        $pc->set_animate(true);
                        $pc->set_legend(array(
                            'show' => true,
                            'location' => 'e',
                            'placement' => 'outside',
                            'yoffset' => 0,
                            'rendererOptions' => array('numberRows'=>1),
                            'labels'=>array('Température', 'Humidité')  
                            ));
                        $pc->set_title(array('text'=>'Diagramme de Temperature et d\'Humidité du bâtiment'));
                        $pc->set_axes(array(
                            'yaxis' => array('min'=>10,'max'=>$xaxis,'tickOptions'=>array('formatString'=>'%d%'))));
                        $pc->set_axes(array(
                            'xaxis' => array('min'=>0,'max'=>$xaxis,'tickOptions'=>array('formatString'=>'%d°C'))));
                    
                        $pc->draw();
                    }
                    if ($periodique == $namebuttom1) {
                        for ($i=0; $i < count($temps); $i++) { 
                            $array_t[] = $temps[$i]['temperature'];
                        }
                        for ($i=0; $i < count($temps); $i++) { 
                            $array_h[] = $temps[$i]['humidite'];
                        }
                        for ($i=0; $i < count($array_t); $i++) { 
                            $ligne1[] = $array_t[$i];
                        }
                        for ($i=0; $i < count($array_h); $i++) { 
                            $ligne2[] = $array_h[$i];
                        }
                        $xaxis = (count($array_h) + count($array_t));
                        graph($xaxis, $ligne1, $ligne2);
                    }elseif ($moyenne == $namebuttom2) {
                        for ($i=0; $i < count($temps); $i++) { 
                            $array_t[] = $temps[$i]['temperature'];
                            $array_h[] = $temps[$i]['humidite'];
                        }
                        $array_t_chunk = array_chunk($array_t, $nbr);
                        $array_h_chunk = array_chunk($array_h, $nbr);
                        
                        for ($i=0; $i < count($array_t_chunk); $i++) { 
                            $ligne1[] = array_sum($array_t_chunk[$i])/$nbr;
                        }
                        for ($i=0; $i < count($array_h_chunk); $i++) { 
                            $ligne2[] = array_sum($array_h_chunk[$i])/$nbr;
                        }
                        

                        $xaxis = ((count($array_h_chunk)+30) + count($array_t_chunk)+30)+15;
                        graph($xaxis, $ligne1, $ligne2);
                    }else {
                        for ($i=0; $i < count($temps); $i++) { 
                            $array_t[] = $temps[$i]['temperature'];
                        }
                        for ($i=0; $i < count($temps); $i++) { 
                            $array_h[] = $temps[$i]['humidite'];
                        }
                        for ($i=0; $i < count($array_t); $i++) { 
                            $ligne1[] = $array_t[$i];
                        }
                        for ($i=0; $i < count($array_h); $i++) { 
                            $ligne2[] = $array_h[$i];
                        }
                        $xaxis = (count($array_h) + count($array_t));
                        graph($xaxis, $ligne1, $ligne2);
                    }
                ?> 
                </div>
                <div id="table">
                    <table>
                        <thead>
                            <tr>
                                <th>Température</th>
                                <th>Humidité</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <?php
                                    $table_temp = $data;
                                    foreach ($table_temp as $ue) {
                                        echo '<tr><td>'.$ue['temperature'].'°C</td><td>'.$ue['humidite'].'%</td></tr>';
                                    }
                                ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id="email">
            <?php
                $email = "";
                if (isset($_POST['email'])) {
                    $email = $_POST['email'];
                }
                $array_email = Array(
                    "email" => $email
                );
                $fichier = fopen('email.json', 'w+');
                $json = json_encode($array_email);

                file_put_contents('email.json', $json);

                $Json = file_get_contents("email.json");
                // Converts to an array 
                $myarray = json_decode($Json, true);
                var_dump($myarray['email']); // prints array
                
                compt_email($myarray['email']);

                echo'<form action="#" method="post">
                        <label for="email">Email: <input type="text" name="email" id="email">
                        <input type="submit">
                        </label>
                    </form>';
            ?>
        </div>
    </body>
</html>




















<!--/*envoi_mail('smtp.gmail.com',
                           "inondationdetection@gmail.com", 
                           "raspiadmin", 
                           465, 
                           $myarray, 
                           "Utilisateur 1", 
                           $myarray,
                           "Utilisateur 2", 
                           "Alerte", 
                           "je suis le body d'un test");*/-->