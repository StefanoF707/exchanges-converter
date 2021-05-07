<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js" integrity="sha512-bZS47S7sPOxkjU/4Bt0zrhEtWx0y0CRkhEp8IckzK+ltifIIE9EMIMTuT/mEzoIMewUINruDBIR/jJnbguonqQ==" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
   <link rel="stylesheet" href="css/style.css">
   <title>Fixer</title>
</head>
<body>

<div id="app">

   <div id="converter" v-if="show">

      <div class="convert_input_container">
         <select v-model="exchangeFrom">
            <option v-for="exchange in exchanges">{{ exchange }}</option>
         </select>
         <input type="number" placeholder="Inserisci un importo" v-model="inputValue" >
         <select v-model="exchangeTo">
            <option v-for="exchange in exchanges">{{ exchange }}</option>
         </select>

         <button @click="convertValue">Converti</button>
      </div>
      
      <div class="convert_input_container">

         <h4 v-if="results.result">
            Risultato: 
            <span>{{ results.result.toFixed(2) }} {{ results.convertedTo }}</span>
         </h4>

         <h4 v-if="results.rate">
            Tasso di cambio attuale: 
            <span>{{ results.rate }}</span>
         </h4>

         <h4 v-if="results.lastUpdate">
            Ultimo aggiornamento: 
            <span>{{ results.lastUpdate }}</span>
         </h4>
      </div>


   </div>
</div>

<script src="main.js"></script>
   
</body>
</html>