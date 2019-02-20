const app = new Vue({
	el: '#app',
	data: {
		errores: [],
		mensajes: [],
		bancos: [],
		pagos: []
	},
	methods: {
		getBancos() {
			axios({
				method: 'get',
				url: './getBancos.php',
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				this.bancos = response.data;
			});
		},
		getPagos() {
			axios({
				method: 'get',
				url: './getPagosInBancos.php',
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				console.log(response.data);
				this.pagos = response.data;
			});
		},
		setBanco(e) {
			if (!isNaN(e.target[0].value) && !isNaN(e.target[1].value) && (e.target[0].value != '' && e.target[1].value != '')) {
				axios.post('./setBanco.php', new FormData(e.target)).then(response => {
					var that = this;
					this.getBancos();
					this.getPagos();
					e.target[0].value = '';
					e.target[1].value = '';
					this.mensajes = response.data.mensajes;
					this.errores = response.data.errores;
					window.setTimeout(function() {
						$('.alert')
							.fadeTo(500, 0)
							.slideUp(500, function() {
								that.mensajes = '';
							});
					}, 10000);
				});
			} else {
				this.mensajes = ['Datos inválidos.'];
				this.errores = [true];
			}
		}
	},
	computed: {
		filterBancos() {
			return this.bancos.filter(banco => banco.divisa != 'VES');
		}
	},
	beforeMount() {
		this.getBancos();
		this.getPagos();
	}
});
