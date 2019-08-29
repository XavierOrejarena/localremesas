const app = new Vue({
	el: '#app',
	data: {
		errores: [],
		mensajes: [],
		bancos: [],
		pagos: [],
		tipo_usuario: ''
	},
	methods: {
		format (n) {
			return n.toLocaleString(
				undefined, // leave undefined to use the browser's locale,
						   // or use a string like 'en-US' to override it.
				{ minimumFractionDigits: 2 }
			  );
		},
		eliminarPago(id) {
			var bodyFormData = new FormData();
			bodyFormData.set('id', id);
			axios({
				method: 'post',
				url: './eliminarPago.php',
				data: bodyFormData,
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				console.log(response.data)
				this.getBancos();
				this.getPagos();
			});
		},
		facturar(id) {
			var bodyFormData = new FormData();
			bodyFormData.set('id', id);
			axios({
				method: 'post',
				url: './LRjson.php',
				data: bodyFormData,
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				console.log(response.data)
			});
		},
		getBancos() {
			axios({
				method: 'get',
				url: './getBancos.php',
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				this.bancos = response.data;
				this.bancos.map(banco => (banco.saldo = this.format(parseFloat(banco.saldo))));
			});
		},
		getPagos() {
			axios({
				method: 'get',
				url: './getPagosInBancos.php',
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				this.pagos = response.data;
				// console.log(this.pagos)
				if (this.pagos != null) {
					// this.pagos.map(pago => (pago.monto = this.format(parseFloat(pago.monto))));
				}
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
		axios({
			method: 'get',
			url: './session.php',
			config: { headers: { 'Content-Type': 'multipart/form-data' } }
		}).then(response => {
			if (response['data'] == 'ADMIN' || response['data'] == 'OPERADOR') {
				this.tipo_usuario = response.data;
			} else {
				window.location.href = './login.html';
			}
		});
		this.getBancos();
		this.getPagos();
	}
});
