<?php 

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

   if (isset($_GET['exchangeFrom']) && isset($_GET['exchangeTo']) && isset($_GET['inputValue'])) {
      
      $exchangeFrom = $_GET['exchangeFrom'];
      $exchangeTo = $_GET['exchangeTo'];
      $inputValue = $_GET['inputValue'];

      # Query della valuta1
      $sqlFrom = $db->prepare('SELECT rate FROM exchanges WHERE exchange = :parameter');
      $sqlFrom->bindParam(':parameter', $exchangeFrom, PDO::PARAM_STR);
      $sqlFrom->execute();
      $db_exchangeFrom = $sqlFrom->fetch(PDO::FETCH_OBJ);
      # /Query della valuta1 

      # Query della valuta2
      $sqlTo = $db->prepare('SELECT rate, updated_at FROM exchanges WHERE exchange = :parameter');
      $sqlTo->bindParam(':parameter', $exchangeTo, PDO::PARAM_STR);
      $sqlTo->execute();
      $db_exchangeTo = $sqlTo->fetch(PDO::FETCH_OBJ);
      # /Query della valuta2

      $array = [];
      
      if ($exchangeFrom == 'EUR') {

         // Euro -> Altra valuta: moltiplico il valore in input per il tasso di cambio della valuta selezionata

         $result = floatval($inputValue) * floatval($db_exchangeTo->rate);
         
         $array['rate'] = $db_exchangeTo->rate;
         $array['result'] = $result;
         $array['convertedTo'] = $exchangeTo;
         $array['lastUpdate'] = $db_exchangeTo->updated_at;
         
      } elseif ($exchangeTo == 'EUR') {

         // Altra valuta -> Euro: Divido il valore di input per il tasso di cambio della valuta selezionata
         
         $result = floatval($inputValue) / floatval($db_exchangeFrom->rate);

         $array['rate'] = 1 / floatval($db_exchangeFrom->rate);
         $array['result'] = $result;
         $array['convertedTo'] = $exchangeTo;
         $array['lastUpdate'] = $db_exchangeTo->updated_at;

      } else {

         // Altra valuta -> Altra valuta: moltiplico il tasso tra Altra valuta e euro con il tasso tra euro e altra valuta

         $el1 = 1 /  floatval($db_exchangeFrom->rate);
         $el2 = 1 * floatval($db_exchangeTo->rate);

         $rate = $el1 * $el2;

         $result = floatval($inputValue) * $rate;

         $array['rate'] = $rate;
         $array['result'] = $result;
         $array['convertedTo'] = $exchangeTo;
         $array['lastUpdate'] = $db_exchangeTo->updated_at;
      }
      
      echo json_encode($array);

   } else {
      
      $sql = $db->query('SELECT exchange FROM exchanges');

      $array = [];

      while($result = $sql->fetch(PDO::FETCH_OBJ)) {
         $array[] = $result->exchange;
      }

      echo json_encode($array);
   }
?>