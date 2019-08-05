const app = new Vue({
	el: '#app',
	data: {
		welcome: true,
		tipo_usuario: 'REGULAR',
		tasas: null,
		value: 1
	},
	methods: {
		cargarTasas() {
			axios({
				method: 'get',
				url: './cargarTasas.php',
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				this.tasas = response.data;
				// this.tasas.map(item => (item.value = parseFloat(100)))
			});
		},
		format (n, d) {
			if (!d) d = 2;
			return n.toLocaleString(
				undefined,
				{ minimumFractionDigits: d }
			  );
		},
	},
	watch: {
		// value: function() {
		// 	this.value = "Monto: " + this.value 
		// }
	},
	beforeMount () {
		axios({
	    method: 'get',
	    url: './session.php',
	    config: { headers: {'Content-Type': 'multipart/form-data' }}
	    })
	    .then( response => {
			if (!(this.welcome = this.tipo_usuario = response['data'])) {
				window.location.href = "./login.html"
			}else {
				this.cargarTasas()
			}
	    })
	}
})