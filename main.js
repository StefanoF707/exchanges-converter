let app = new Vue({
   el: '#app',
   data: {
      show: false,
      exchanges: [],
      exchangeFrom: 'EUR',
      exchangeTo: 'USD',
      inputValue: 0,
      results: {},
   },
   methods: {
      convertValue() {
         axios
            .get('partials/api.php', {
               params: {
                  exchangeFrom: this.exchangeFrom,
                  exchangeTo: this.exchangeTo,
                  inputValue: this.inputValue,
               },
            })
            .then( response => {
               this.results = response.data;
               console.log(response.data);
            } )
      }
   },
   mounted() {
      axios
         .get('partials/api.php')
         .then( response => {
            this.exchanges = response.data;

            this.show = true;
         } );
   }
})