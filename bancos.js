const app = new Vue({
	el: '#app',
	data: {
		mensaje: '',
		bancos: []
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
		setBanco(e) {
			axios.post('./setBanco.php', new FormData(e.target)).then(response => {
				var that = this;
				if (response.data) {
					this.getBancos();
					e.target[0].value = '';
					e.target[1].value = '';
					this.mensaje = 'Pago agregado exitosamente.';
				} else {
					this.mensaje = 'Hubo un error al agregar los datos.';
				}
				window.setTimeout(function() {
					$('.alert')
						.fadeTo(500, 0)
						.slideUp(500, function() {
							that.mensaje = '';
						});
				}, 2000);
			});
		}
	},
	computed: {
		filterBancos() {
			return this.bancos.filter(banco => banco.divisa != 'VEF');
		}
	},
	beforeMount() {
		this.getBancos();
	}
});
