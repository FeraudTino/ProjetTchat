#!/usr/bin/php
<?php

function test(){
    //Création de la socket
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    //positionnement des options
    socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
    //Ecoute sur le port 1234 sur toutes les adresses
    socket_bind($socket, "0.0.0.0", 1234);
    //Passage en écoute
    socket_listen($socket);
    //Tableau qui stocke les sockets des clients
    $clients = array();
    //passage en non bloquant de la socket serveur
    socket_set_nonblock( $socket );
    //Boolean qui contrôle l'exécution du programme
    $end = false;
    //Boucle infinie
    while(!$end){
        //On récupère la connexion du client (socket)
        $c = @socket_accept($socket);
        if($c != FALSE){
            //Un client se connecte, passage de la socket en non bloquant
            socket_set_nonblock ( $c );
            //ajout du client au tableau
            $clients[] = $c;
        }
        // On itère sur le tableau de sockets des clients
        for($i = 0; $i < sizeof($clients); $i++){
            //On récupère la socket client
            $c = $clients[$i];
            //On teste si la socket est tjrs active
            if(!$c){
                //Si pas active on la retire du tableau
                $clients = array_splice($clients, $i, 1);
                //on passe à l'occurence suivante
                continue;
            }
            //On lit le contenu de la socket
            if($buf = socket_read ($c, 2048)){
                //le client parle, on renvoie les données
                socket_write($c, "You said : ", $buf);
            }
        }
    }
    socket_close($socket);
}

function main($aray){
    //var_dump($argv);
    test();
}

main($argv); 