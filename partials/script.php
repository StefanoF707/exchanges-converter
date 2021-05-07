<?php 

   $fixer_access_key = '7cfb190bd6606a76d16f8ec4e17ad450';
   $fixer_baseURL = 'http://data.fixer.io/api/';


   // 1 - Inizializzo la sessione cURL
   $curl = curl_init();


   // 2 - Utilizzo la funzione curl_opt per impostare le opzioni

   // 2.1 - settare la url
   curl_setopt($curl, CURLOPT_URL, $fixer_baseURL . 'latest?access_key=' . $fixer_access_key);

   // 2.2 - settare CURLOPT_RETURNTRANSFER a true per non stampare a video la risposta
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

   // 2.3 - Settare CURLOPT_HEADER a false per ignorare gli header di risposta
   curl_setopt($curl, CURLOPT_HEADER , false);


   // 3 - con la funzione curl_exec catturo il risultato della chiamata e lo salvo all'interno di una variabile
   $curl_result = curl_exec($curl);


   // 4 - con la funzione curl_close termino la sessione cURL
   curl_close($curl);

   $array = json_decode($curl_result, true);


   try {
      $servername = 'localhost';
      $username = 'root';
      $password = 'root';
      $dbname = 'db-exchange';
   
      $db = new PDO ("mysql:host=$servername;dbname=$dbname", $username, $password);

   } catch (PDOException $e) {
      echo "Errore: " . $e->getMessage();
      die();
   }
   
   // Prendo tutte le valute presenti nel db e le pusho all'interno di un' array;

   $sql1 = $db->query("SELECT exchange FROM exchanges");

   $allExchanges = [];

   while($result = $sql1->fetch(PDO::FETCH_NUM)) {
      $allExchanges[] = $result[0];
   }


   // Ciclo sui risultati ottenuti dall'api e controllo se la valuta dell'iterazione corrente sia presente all'interno del db.
   
   foreach($array['rates'] as $exchange => $rate) {

      $id = array_search($exchange, array_keys($array['rates'])) + 1;

      if (in_array($exchange, $allExchanges)) {

         $sqlUpdate = $db->query("UPDATE exchanges SET rate='$rate', updated_at=CURRENT_TIMESTAMP WHERE id='$id'");

      } else {

         $sqlInsert = $db->query("INSERT INTO exchanges (exchange, rate) VALUES ('$exchange', '$rate')");

      }
      
   }

   echo 'Script eseguito';

?>