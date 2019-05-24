const app = new Vue({
	el: '#app',
	data: {
		red: 'border-color: #ff0000;  -webkit-box-shadow: 0 0 8px rgba(255, 0, 0, 0.6);',
		agregar_cuenta: true,
		monto: 0,
		monto3: 0,
		tasa: '',
		divisa: 'PEN',
		id_usuario: 0,
		tipo_usuario: '',
		tipo_cliente: 'REGULAR',
		monto_total: 0,
		tabla: false,
		cuentas_display: '',
		small: 'Usuario nuevo',
		mensajes: [],
		errores: [],
		cuentas: [],
		referencia: '',
		otros: true
	},
	methods: {
		calcularMontoTotal() {
			this.monto_total = 0;
			if (!(this.cuentas_display == undefined)) {
				var plus;
				for (var i = 0; i < this.cuentas_display.length; i++) {
					plus = parseFloat(this.cuentas_display[i]['monto']);
					if (this.cuentas_display[i]['monto'] == '') {
						plus = 0;
					}
					this.monto_total += parseFloat(plus);
				}
				this.monto_total = Math.round(this.monto_total);
			}
		},
		calcularTasa(operador) {
			if (operador == '+') {
				this.tasa = this.tasa * 1.005;
			} else {
				this.tasa = this.tasa / 1.005;
			}
		},
		cargarTasa(tipo) {
			var bodyFormData = new FormData();
			bodyFormData.set('tipo', tipo);
			bodyFormData.set('divisa', this.divisa);
			axios({
				method: 'post',
				url: './cargarTasa.php',
				data: bodyFormData,
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				if (tipo == 'BUSCADOR') {
					this.tasa = Math.floor(response['data'] / 1.04);
				} else {
					this.tasa = response['data'];
				}
			});
		},
		clear() {
			this.monto = 0;
			this.monto_total = 0;
			this.id_usuario = 0;
			this.buscarUsuario();
			this.cargarTasa();
			if (this.referencia != '0') {
				document.getElementById('comprobante').value = document.getElementById('comprobante').defaultValue;
			}
			document.getElementById('banco1').checked = true;
			document.getElementById('divisa1').checked = true;
			document.getElementById('divisa2').disabled = true;
			this.referencia = '';
		},
		onChange() {
			if (document.querySelector('input[name="banco"]:checked').value == 'BANPA / ZELLE' || this.tipo_cliente == 'ESPECIAL') {
				this.otros = false;
			} else {
				this.otros = true;
			}
		},
		disabled() {
			if (document.getElementById('banco3').checked == true) {
				document.getElementById('divisa2').disabled = true;
				document.getElementById('divisa1').checked = true;
				document.getElementById('divisa1').disabled = false;
			} else {
				document.getElementById('divisa2').disabled = false;
			}
			if (document.getElementById('banco4').checked == true) {
				document.getElementById('divisa2').checked = true;
				document.getElementById('divisa1').disabled = true;
				document.getElementById('divisa2').disabled = false;
			}
			this.cargarTasa('REGULAR');
		},
		error(mensaje, obj) {
			this.mensajes = [mensaje];
			this.errores = [true];
			document.getElementById(obj).style = this.red;
			window.scrollTo(0, 0);
		},
		verificarReferencia: function(e) {
			if (e.target.value.length < 1 || e.target.value == '') {
				e.target.style = this.red;
			} else {
				e.target.style = '';
			}
		},
		verificarMonto: function(e) {
			if (e.target.value.length < 1 || isNaN(e.target.value) || e.target.value == 0) {
				e.target.style = this.red;
			} else {
				e.target.style = '';
			}
		},
		verificarCedula: function(e) {
			if (e.target.value.length > 9 || e.target.value.length < 7 || isNaN(e.target.value)) {
				e.target.style = this.red;
				this.agregar_cuenta = false;
			} else {
				e.target.style = '';
				this.agregar_cuenta = true;
			}
		},
		verificarCuenta: function(e) {
			if (e.target.value.length > 20 || e.target.value.length < 20 || isNaN(e.target.value)) {
				e.target.style = this.red;
				this.agregar_cuenta = false;
			} else {
				e.target.style = '';
				this.agregar_cuenta = true;
			}
		},
		verificarNombre: function(e) {
			if (e.target.value.length > 19 || e.target.value.length == 0) {
				e.target.style = this.red;
				this.agregar_cuenta = false;
			} else {
				e.target.style = '';
				this.agregar_cuenta = true;
			}
		},
		insertarPagos_in() {
			if (this.monto == '') {
				this.error('Debe introducir algún monto.', 'monto');
			} else {
				if (this.monto == 0) {
					this.error('El monto debe ser mayor a cero.', 'monto');
				} else {
					if (this.id_usuario == 0) {
						this.error('Debe agregar una cuenta.', 'id_usuario');
					} else {
						if (this.monto_total == 0) {
							this.error('El monto total no puede ser cero.', 'monto_total');
						} else {
							if (this.monto_total > Math.round(this.tasa * this.monto3)) {
								this.error('El monto total es inocrrecto.', 'monto_total');
							} else {
								if (this.referencia == '') {
									this.error('Debe introducir algun número de referencia.', 'referencia');
								} else {
									var bodyFormData = new FormData();
									if (this.referencia != '0') {
										bodyFormData.append('comprobante', document.getElementById('comprobante').files[0]);
										var barra = document.getElementById('barra');
									}
									bodyFormData.set('tasa', this.tasa);
									bodyFormData.set('id_usuario', this.id_usuario);
									bodyFormData.set('divisa', this.divisa);
									bodyFormData.set('banco', document.querySelector('input[name="banco"]:checked').value);
									bodyFormData.set('monto', document.getElementById('monto').value);
									bodyFormData.set('referencia', this.referencia);
									axios({
										method: 'post',
										url: './insertarPagos_in.php',
										data: bodyFormData,
										config: { headers: { 'Content-Type': 'multipart/form-data' } },
										onUploadProgress: e => {
											if (e.lengthComputable && this.referencia != '0') {
												var p = Math.round((e.loaded / e.total) * 100);
												barra.style = 'width: ' + p + '%';
												barra.innerHTML = p + '%';
												this.small = '...';
												this.tipo_cliente = '...';
											}
										}
									}).then(response => {
										console.log(response.data);
										this.mensajes = response.data.mensajes;
										this.errores = response.data.errores;
										if (this.referencia != '0') {
											barra.style = 'width: 0%';
											barra.innerHTML = '';
										}
										this.insertarPagos_out(response.data.id_pago_in, response.data.flag, response.data.restar);
										this.clear();
										window.scrollTo(0, 0);
									});
								}
							}
						}
					}
				}
			}
		},
		insertarPagos_out(id_pago_in, flag, restar) {
			var bodyFormData = new FormData();
			bodyFormData.set('id_usuario', this.id_usuario);
			bodyFormData.set('id_pago_in', id_pago_in);
			bodyFormData.set('flag', flag);
			bodyFormData.set('restar', restar);
			var j = 0;
			for (var i = 0; i < this.cuentas_display.length; i++) {
				if (this.cuentas_display[i].monto > 0) {
					bodyFormData.set('id_cuenta[' + j + ']', this.cuentas_display[i].id);
					bodyFormData.set('monto[' + j + ']', this.cuentas_display[i].monto);
					j++;
				}
			}
			axios({
				method: 'post',
				url: './insertarPagos_out.php',
				data: bodyFormData,
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				this.mensajes = this.mensajes.concat(response.data.mensajes);
				this.errores = this.errores.concat(response.data.errores);
			});
		},
		agregarCuenta() {
			this.cuentas.push({
				nombre: '',
				tipo_cedula: 'V',
				cedula: '',
				tipo_cuenta: 'CORRIENTE',
				cuenta: ''
			});
		},
		borrarCuenta(e) {
			var bodyFormData = new FormData();
			bodyFormData.set('id_cuenta', e.target.value);
			axios({
				method: 'post',
				url: './borrarCuenta.php',
				data: bodyFormData,
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				this.buscarUsuario();
			});
		},
		eliminarCuenta(index) {
			this.cuentas.splice(index, 1);
		},
		cargarCuentas() {
			if (this.agregar_cuenta) {
				var bodyFormData = new FormData();
				bodyFormData.set('id_usuario', this.id_usuario);
				for (var i = 0; i < this.cuentas.length; i++) {
					bodyFormData.set('nombre[' + i + ']', this.cuentas[i].nombre);
					bodyFormData.set('tipo_cedula[' + i + ']', this.cuentas[i].tipo_cedula);
					bodyFormData.set('cedula[' + i + ']', this.cuentas[i].cedula);
					bodyFormData.set('tipo_cuenta[' + i + ']', this.cuentas[i].tipo_cuenta);
					bodyFormData.set('cuenta[' + i + ']', this.cuentas[i].cuenta);
				}
				axios({
					method: 'post',
					url: './cargarCuentas.php',
					data: bodyFormData,
					config: { headers: { 'Content-Type': 'multipart/form-data' } }
				}).then(response => {
					this.mensajes = response['data']['mensajes'];
					this.errores = response['data']['errores'];
					this.id_usuario = response['data']['id_usuario'];
					this.buscarUsuario();
					window.scrollTo(0, 0);
				});
				this.cuentas.splice(0, this.cuentas.length);
			} else {
				this.mensajes = ['Datos de cuenta inválidos. Por favor verificar'];
				this.errores = [true];
				window.scrollTo(0, 0);
			}
		},
		buscarUsuario(e) {
			if (e != undefined) {
				if (e.target.value.length > 10 || e.target.value.length < 1 || isNaN(e.target.value)) {
					e.target.style = this.red;
				} else {
					e.target.style = '';
				}
			}
			var bodyFormData = new FormData();
			if (this.id_usuario != 0) {
				bodyFormData.set('id_usuario', this.id_usuario);
				axios({
					method: 'post',
					url: './buscarUsuario.php',
					data: bodyFormData,
					config: { headers: { 'Content-Type': 'multipart/form-data' } }
				}).then(response => {
					if (response['data']['cuentas']) {
						this.small = 'Usuario ya existe';
						this.cuentas_display = response['data']['cuentas'];
						this.cuentas_display.map(item => (item.monto = 0));
						this.tabla = true;
						this.cargarTasa(response['data']['cuentas'][0].tipo);
						this.tipo_cliente = response['data']['cuentas'][0].tipo;
					} else {
						if (response['data']['tipo']) {
							this.small = 'Usuario ya existe';
							this.tipo_cliente = response['data']['tipo'];
							this.cargarTasa(response['data']['tipo']);
						} else {
							this.cargarTasa('REGULAR');
							this.small = 'Usuario no existe';
							this.tipo_cliente = '';
						}
						this.cuentas_display = '';
						this.tabla = false;
					}
					this.onChange();
				});
			} else {
				this.tipo_cliente = 'REGULAR';
				this.cuentas_display = '';
				this.tabla = false;
				this.small = 'Usuario nuevo';
			}
			this.calcularMontoTotal();
		},
		referencia_cero() {
			if (this.referencia == '0') {
				this.cargarTasa(this.tipo_cliente);
			}
		}
	},
	computed: {
		monto2: function () {
			if (this.divisa == 'PEN') {
				this.monto3 = this.monto-3;
				return this.monto-3
			} else {
				this.monto3 = this.monto-1;
				return this.monto-1
			}
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
				this.cargarTasa('REGULAR');
			} else {
				window.location.href = './login.html';
			}
		});
	}
});
