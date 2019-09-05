const app = new Vue({
	el: '#app',
	data: {
		pagos: '',
		tipo_usuario: 'REGULAR',
		bancos: [],
		red: 'border-color: #ff0000;  -webkit-box-shadow: 0 0 8px rgba(255, 0, 0, 0.6);',
		mensajes: [],
		errores: [],
		saldos: {}
	},
	methods: {
		format (n, d) {
			if (!d) d = 2;
			return n.toLocaleString(
				undefined, // leave undefined to use the browser's locale,
						   // or use a string like 'en-US' to override it.
				{ minimumFractionDigits: d }
			  );
		},
		getBancos() {
			axios({
				method: 'get',
				url: './getBancos.php',
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				this.bancos = response.data;
			});
		},
		Banco(cuenta) {
			var bancos = {
				'0156': '100%BANCO',
				'0196': 'ABN AMRO',
				'0172': 'BANCAMIGA',
				'0171': 'ACTIVO',
				'0166': 'AGRICOLA',
				'0175': 'BICENTENARIO',
				'0128': 'CARONI',
				'0164': 'DESARROLLO',
				'0102': 'VENEZUELA',
				'0114': 'CARIBE',
				'0149': 'PUEBLO SOBERANO',
				'0163': 'TESORO',
				'0176': 'ESPIRITO SANTO',
				'0115': 'EXTERIOR',
				'0003': 'INDUSTRIAL',
				'0173': 'INTERNACIONAL DE DESARROLLO',
				'0105': 'MERCANTIL',
				'0191': 'BNC',
				'0116': 'BOD',
				'0138': 'PLAZA',
				'0108': 'PROVINCIAL',
				'0104': 'VENEZOLANO DE CREDITO',
				'0168': 'BANCRECER',
				'0134': 'BANESCO',
				'0177': 'BANFANB',
				'0146': 'BANGENTE',
				'0174': 'BANPLUS',
				'0190': 'CITIBANK.',
				'0121': 'CORP BANCA.',
				'0157': 'DELSUR',
				'0151': 'FONDO COMUN',
				'0601': 'POPULAR',
				'0169': 'MIBANCO',
				'0137': 'SOFITASA'
			};
			if (cuenta) {
				return bancos[cuenta.substring(0, 4)];
			} else {
				return 'SIN CUENTA';
			}
		},
		pagar_out(e, id_pago_in) {
			this.mensajes = []
			this.errores = []
			// if (document.getElementById(e).value == '' || document.getElementById(e).value == 0) {
				// document.getElementById(e).style = this.red
			// } else {
				// document.getElementById(e).style = '';
				if (document.getElementById('f' + e).value == '') {
					(document.getElementById('f'+e).parentElement).parentElement.style = this.red
					document.getElementById('f'+e).parentElement.style = this.red
					// document.getElementById('f' + e).style = this.red
				} else {
					(document.getElementById('f'+e).parentElement).parentElement.style = ''
					document.getElementById('f'+e).parentElement.style = ''
					var barra = document.getElementById('barra');
					var bodyFormData = new FormData();
					bodyFormData.append(
						'id_banco',
						$('#b' + e)
							.find(':selected')
							.val()
					);
					bodyFormData.append('comprobante', document.getElementById('f' + e).files[0]);
					bodyFormData.set('id', e);
					bodyFormData.set('id_pago_in', id_pago_in);
					bodyFormData.set('referencia', document.getElementById(e).value);
					axios({
						method: 'post',
						url: './pagar_out.php',
						data: bodyFormData,
						config: { headers: { 'Content-Type': 'multipart/form-data' } },
						onUploadProgress: e => {
							if (e.lengthComputable) {
								var p = Math.round((e.loaded / e.total) * 100);
								barra.style = 'width: ' + p + '%';
								barra.innerHTML = p + '%';
								this.small = '...';
								this.tipo_cliente = '...';
							}
						}
					}).then(response => {
						this.mensajes = [response.data.mensajes]
						this.errores = [response.data.errores]
						window.setTimeout(function() {
							that = this.mensajes;
							$('.alert')
								.fadeTo(500, 0)
								.slideUp(500, function() {
									that = '';
								});
						}, 3000);
						for (let i = 0; i < this.pagos.length; i++) {
							document.getElementById(this.pagos[i].id_pago_out).value = '';
							document.getElementById('f' + this.pagos[i].id_pago_out).value = document.getElementById('f' + this.pagos[i].id_pago_out).defaultValue;
						}
						window.scrollTo(0, 0);
						barra.style = 'width: ' + 0 + '%';
						barra.innerHTML = 0 + '%';
						this.cargar_pagos_out()
						this.getBancos()
						// this.pagos.map(item => (item.monto = parseInt(item.monto), item.amount = parseFloat(item.amount), item.tasa = parseFloat(item.tasa)));
						// window.location.href = './pagos_out.html';
					}).catch(function (err) {
						console.log(err)
					  });
				}
			// }
		},
		cargar_pagos_out() {
			axios({
				method: 'get',
				url: './cargar_pagos_out.php',
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				this.pagos = response.data;
				response.data.map(item => (item.monto = parseInt(item.monto)));
				this.pagos.forEach(pago => {
					banco = this.Banco(pago.cuenta)
					if (this.saldos[banco]) {
						this.saldos[banco] = this.saldos[banco] + pago.monto
					} else {
						this.saldos[banco] = pago.monto
					}
				});
				console.log(this.saldos)
			});
		},
		rechazar(id) {
			this.mensajes = []
			this.errores = []
			var bodyFormData = new FormData();
			bodyFormData.set('id', id);
			axios({
				method: 'post',
				url: './rechazar_pagos_out.php',
				data: bodyFormData,
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				this.mensajes = [response.data.mensajes]
				this.errores = [response.data.errores]
				this.cargar_pagos_out();
				window.setTimeout(function() {
					that = this.mensajes;
					$('.alert')
						.fadeTo(500, 0)
						.slideUp(500, function() {
							that = '';
						});
				}, 3000);
				// window.location.href = './pagos_out.html';
			});
		}
	},
	computed: {
		filterBancos() {
			return this.bancos.filter(banco => banco.divisa == 'VES');
		}
	},
	beforeMount() {
		axios({
			method: 'get',
			url: './session.php',
			config: { headers: { 'Content-Type': 'multipart/form-data' } }
		}).then(response => {
			if (response.data == 'ADMIN' || response.data == 'OPERADOR') {
				this.tipo_usuario = response.data;
				this.cargar_pagos_out();
				this.getBancos();
			} else {
				window.location.href = './login.html';
			}
		});
	}
});
