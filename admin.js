const app = new Vue({
	el: '#app',
	data: {
		whatsapp: ' Nuestras cuentas son:%0D%0A%0D%0A💸Localremesas SAC 💸%0D%0A%0D%0A✅Cuenta en Soles✅%0D%0ABCP%0D%0A*191-2521900-0-36*%0D%0ACuenta Corriente%0D%0ALOCAL REMESAS S.A.C%0D%0ARUC: 20603289812%0D%0ACCI  #  00219100252190003658%0D%0A%0D%0A❎Cuenta en Dolares❎%0D%0ABCP%0D%0A*191-2575203-1-60* %0D%0ACuenta Corriente%0D%0ALOCAL REMESAS S.A.C%0D%0ARUC: 20603289812%0D%0ANota: Preguntar tasa de dolares',
		respaldos: ['Seleccione un archivo:'],
		RespaldoSeleccionado: 0,
		amount: 0,
		tipos: [],
		tipos2: ['REGULAR', 'OPERADOR', 'BUSCADOR', 'ESPECIAL', 'ADMIN'],
		tasas: '',
		clase: '',
		id: '',
		small: '',
		tipo_usuario: '',
		usuario: '',
		mensaje: '',
		bancos: '',
		prestamos: null,
		prestamos2: null,
		prestamos3: null,
		pagos_in: '',
		registro: '',
		monto: '',
		nota: '',
		banco_index: 0,
		registros: '',
		referencia: '',
		mensajes: [],
		errores: [],
		divisas: '',
		total_entrante: '',
		totalPrestamos: [],
		pagos_out: '',
		max: null,
		min: null,
		date: null
	},
	methods: {
		eliminarUsuario() {
			var bodyFormData = new FormData();
			bodyFormData.set('id', this.id);
			axios({
				method: 'post',
				url: './eliminarUsuario.php',
				data: bodyFormData,
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				if (response.data) {
					this.getUserInfo()
				} else {
					console.log("error")
				}
			});
		},
		format (n, d) {
			if (!d) d = 2;
			return n.toLocaleString(
				undefined, // leave undefined to use the browser's locale,
						   // or use a string like 'en-US' to override it.
				{ minimumFractionDigits: d }
			  );
		},
		cargar_pagos_all() {
			var bodyFormData = new FormData();
			bodyFormData.set('date', this.date);
			axios({
				method: 'post',
				url: './cargar_pagos_all.php',
				data: bodyFormData,
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				if (response.data) {
					this.pagos_out = response.data.out;
					this.pagos_out.map(item => (item.monto = parseInt(item.monto), item.amount = parseFloat(item.amount), item.tasa = parseFloat(item.tasa)));
					for (let i = 0; i < this.pagos_out.length; i++) {
						if (this.tipos.indexOf(this.pagos_out[i].tipo) == -1) {
							this.tipos.push(this.pagos_out[i].tipo)
						}
					}
				}
			});
		},
		getDivisas(){
			axios({
				method: 'get',
				url: './getDivisas.php',
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				this.divisas = response.data;
			});
		},
		// totalEntrante(){
		// 	var bodyFormData = new FormData();
		// 	bodyFormData.set('tipo_usuario', this.tipo_usuario);
		// 	axios({
		// 		method: 'post',
		// 		url: './totalEntrante.php',
		// 		data: bodyFormData,
		// 		config: { headers: { 'Content-Type': 'multipart/form-data' } }
		// 	}).then(response => {
		// 		let element = 0;
		// 		this.total_entrante = response.data
		// 		for (let i = 0; i < this.total_entrante.length; i++) {
		// 			element = element + parseFloat(this.total_entrante[i].monto);
		// 		}
		// 	});
		// },
		revisarPrestamo(){
			var bodyFormData = new FormData();
			bodyFormData.set('amount', this.amount);
			bodyFormData.set('id_banco', this.bancos[this.banco_index].id);
			bodyFormData.set('referencia', this.referencia);
			bodyFormData.set('id_usuario', this.id);
			axios({
				method: 'post',
				url: './revisarPrestamo.php',
				data: bodyFormData,
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				this.mensajes.push(response.data.mensaje)
				this.errores.push(response.data.error);
				this.getPrestamos();
				window.setTimeout(function() {
					that = this.mensajes;
					$('.alert')
						.fadeTo(500, 0)
						.slideUp(500, function() {
							that = '';
						});
				}, 3000);
			});
		},
		setClase(clase) {
			this.clase = clase;
			localStorage.setItem('clase', clase);
		},
		setRegistro(registro) {
			this.registro = registro;
			localStorage.setItem('registro', registro);
		},
		eliminarBanco(id) {
			var bodyFormData = new FormData();
			bodyFormData.set('id', id);
			axios({
				method: 'post',
				url: './eliminarBanco.php',
				data: bodyFormData,
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				if (response.data) {
					this.getBancos();
				}
			});
		},
		agregarBanco() {
			var bodyFormData = new FormData();
			bodyFormData.set('nombre', document.getElementById('nombre').value.toUpperCase());
			bodyFormData.set('saldo', document.getElementById('saldo').value);
			bodyFormData.set('divisa', document.getElementById('divisa').value.toUpperCase());
			axios({
				method: 'post',
				url: './agregarBanco.php',
				data: bodyFormData,
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				if (response.data) {
					this.getBancos();
				}
			});
		},
		actualizarSaldo(id) {
			var bodyFormData = new FormData();
			bodyFormData.set('id', id);
			bodyFormData.set('saldo', document.getElementById(id).value);
			axios({
				method: 'post',
				url: './actualizarSaldo.php',
				data: bodyFormData,
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				if (response.data) {
					this.getBancos();
				}
			});
		},
		getBancos() {
			axios({
				method: 'get',
				url: './getBancos.php',
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				this.bancos = response.data;
				// this.banco_id = this.bancos[0].id
				this.bancos.map(item => (item.saldo = parseFloat(item.saldo)))
			});
		},
		actualizarUsuario() {
			var bodyFormData = new FormData();
			bodyFormData.set('id', this.id);
			bodyFormData.set('tipo', this.tipo_usuario);
			axios({
				method: 'post',
				url: './actualizarUsuario.php',
				data: bodyFormData,
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				var that = this;
				if (response.data) {
					this.getUserInfo();
					$('#tipo').val(this.tipo_usuario);
					this.mensaje = 'Usuario actualizado exitosamente';
					setTimeout(function() {
						that.mensaje = '';
					}, 3000);
				}
			});
		},
		getUserInfo() {
			if (this.id != '') {
				var bodyFormData = new FormData();
				bodyFormData.set('id', this.id);
				axios({
					method: 'post',
					url: './getUserInfo.php',
					data: bodyFormData,
					config: { headers: { 'Content-Type': 'multipart/form-data' } }
				}).then(response => {
					if (response.data == null) {
						this.small = 'Usuario no existe';
						this.usuario = '';
					} else {
						this.usuario = response.data;
						this.small = '';
						this.tipo_usuario = response.data.tipo;
					}
				});
			}
		},
		actualizarTasa(e) {
			var bodyFormData = new FormData();
			bodyFormData.set('tasa', document.getElementById(e).value);
			bodyFormData.set('divisa', e);
			axios({
				method: 'post',
				url: './actualizarTasa.php',
				data: bodyFormData,
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				if (response.data) {
					this.cargarTasas();
				}
			});
		},
		cargarTasas() {
			axios({
				method: 'get',
				url: './cargarTasas.php',
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				this.tasas = response.data;
			});
		},
		calcularTotalPrestamos() {
			this.totalPrestamos = []
			for (let i = 0; i < this.divisas.length; i++) {
				this.totalPrestamos.push({divisa: this.divisas[i].divisa, total: 0})
				for (let j = 0; j < this.prestamos.length; j++) {
					if (this.prestamos[j].divisa == this.divisas[i].divisa) {
						this.totalPrestamos[i].total = this.totalPrestamos[i].total + this.prestamos[j].monto
					}
				}
			}
		},
		getPrestamos() {
			axios({
				method: 'get',
				url: './getPrestamos.php',
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				if (response.data != null) {
					// console.log(response.data.total)
					this.prestamos = response.data.total
					this.prestamos.map(prestamo => (prestamo.monto = parseFloat(prestamo.monto)))
					this.calcularTotalPrestamos()
					this.prestamos2 = response.data.detallado
				}
			});
		},
		captures_out() {
			axios({
				method: 'get',
				url: './captures_out.php',
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				response.data.forEach(element => {
					var temp = element.indexOf("_")
					var number = element.substring(0, temp)
					if(!isNaN(number) && number != '') {
						Array.prototype.forEach.call(this.prestamos2, e => {
							if (number == e.id_pago_out) {
								e.capture = element;
							}
						})
					}
				})
				this.prestamos3 = this.prestamos2;
			});
		},
		getPagosIn() {
			var bodyFormData = new FormData();
			bodyFormData.set('id', this.id);
			axios({
				method: 'post',
				url: './getPagosIn.php',
				data: bodyFormData,
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				this.pagos_in = response.data;
			});
		},
		onChanges(e) {
			this.banco_id = e.target.value;
		},
		// onChange(e) {
		// 	this.banco_id = e.target.value;
		// 	this.getRegistros();
		// },
		getRegistros() {
			var bodyFormData = new FormData();
			bodyFormData.set('id', this.bancos[this.banco_index].id);
			axios({
				method: 'post',
				url: './getRegistros.php',
				data: bodyFormData,
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				this.registros = response.data;
				if (response.data) {
					// this.registros.map(registros => (registros.monto = this.format(parseFloat(registros.monto))));
				}
			});
		},
		eliminarRegistro(index) {
			var bodyFormData = new FormData();
			bodyFormData.set('banco_id', this.bancos[this.banco_index].id);
			bodyFormData.set('id_registro', this.registros[index].id);
			axios({
				method: 'post',
				url: './eliminarRegistro.php',
				data: bodyFormData,
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				console.log(response.data)
				this.getRegistros();
			});
		},
		editarRegistro(index) {
			var bodyFormData = new FormData();
			bodyFormData.set('banco_id', this.bancos[this.banco_index].id);
			bodyFormData.set('id_registro', this.registros[index].id);
			bodyFormData.set('monto', this.registros[index].monto);
			bodyFormData.set('nota', this.registros[index].nota);
			axios({
				method: 'post',
				url: './editarRegistro.php',
				data: bodyFormData,
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				this.getRegistros();
			});
		},
		agregarRegistro() {
			var bodyFormData = new FormData();
			bodyFormData.set('id', this.bancos[this.banco_index].id);
			bodyFormData.set('monto', this.monto);
			bodyFormData.set('nota', this.nota);
			axios({
				method: 'post',
				url: './agregarRegistro.php',
				data: bodyFormData,
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				this.mensajes.push(response.data.mensajes)
				this.errores.push(response.data.errores)
				window.setTimeout(function() {
					that = this.mensajes;
					that2 = this.errores;
					$('.alert')
						.fadeTo(500, 0)
						.slideUp(500, function() {
							that = [];
							that2 = [];
						});
				}, 3000);
				this.getBancos()
				this.getRegistros();
			});
		},
		total (prom, vif, divisa, prop, divisa2) {
			var total = 0
			var arr = []
			var aux = false;
			Array.from(this.pagos_out).forEach(pago => {
				if (prop == 'amount') {
					if (arr.indexOf(pago.id_pago_in) == -1) {
						arr.push(pago.id_pago_in);
						if (pago[vif] == divisa) {
							if (divisa2) {
								if (pago.divisa == divisa2) {
									total = total + pago[prop];
								}
							} else {
								total = total + pago[prop];
							}
						}
					}
				}else {
					aux = true;
					if (pago[vif] == divisa) {
						if (divisa2) {
							if (pago.divisa == divisa2) {
								total = total + pago[prop];
							}
						} else {
							total = total + pago[prop];
						}
					}
				}
			});
			if (prom) {
				return total
			}
			if (aux) {
				return this.format(total, '0')
			}else {
				return this.format(total)
			}
			// return total.toFixed(2)
			// return String(total).replace(/(.)(?=(\d{3})+$)/g,'$1,')
		},
		tasa(tasa) {
			if (isNaN(tasa)) {
				return 0
			}
			return this.format(parseFloat(tasa.toFixed(2)))
		},
		getRespaldos() {
			axios({
				method: 'get',
				url: './getRespaldos.php',
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				this.respaldos = ['Seleccione un archivo:']
				response.data.forEach(element => {
					this.respaldos.push(element)
				});
			});
		},
		Respaldar() {
			var bodyFormData = new FormData();
			bodyFormData.set('filename', this.respaldos[this.RespaldoSeleccionado]);
			axios({
				method: 'post',
				url: './respaldar.php',
				data: bodyFormData,
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			})
		}
	},
	watch: { 
		bancos: function() { // watch it
			this.banco_index = 0
		},
		clase: function() {
			this.mensajes = []
			this.errores = []
			if (this.clase == "Respaldo") {
				this.getRespaldos()
			}
		},
		registro: function() {
			this.mensajes = []
			this.errores = []
		}
	},
	beforeMount() {
		this.getBancos();
		axios({
			method: 'get',
			url: './session.php',
			config: { headers: { 'Content-Type': 'multipart/form-data' } }
		}).then(response => {
			if (response['data'] == 'ADMIN') {
				this.clase = localStorage.getItem('clase');
				if (localStorage.getItem('registro') == 'true') {
					this.registro = true;
				} else {
					this.registro = false;
				}
				this.tipo_usuario = response.data;
				this.getDivisas();
				this.cargarTasas();
				this.getPrestamos();
				this.getRegistros();
				var today = new Date();
				var dd = String(today.getDate()).padStart(2, '0');
				var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
				var yyyy = today.getFullYear();
				this.max = yyyy + '-' + mm + '-' + dd;
				this.min = yyyy + '-' + mm + '-01';
				this.date = this.max
				this.cargar_pagos_all()
			} else {
				window.location.href = './login.html';
			}
		});
	},
	beforeUpdate() {
		this.captures_out();
	  },
	computed: {
		SmallClass: function() {
			var clase = '';
			if (this.small == 'Username ya existe') {
				clase = 'text-danger';
			}
			if (this.small == 'Username disponible') {
				clase = 'text-success';
			}
			if (this.small == 'Nuevo Usuario') {
				clase = 'text-primary';
			}
			return clase;
		},
		filterBancos() {
			return Array.from(this.bancos).filter(banco => banco.divisa != 'VES');
		},
		filterPrestamos() {
			if (this.prestamos != null) {
				return this.prestamos.filter(prestamo => prestamo.id_usuario == this.id);
			}
		},
		filterPrestamos2() {
			if (this.prestamos2 != null) {
				return this.prestamos2.filter(prestamo => prestamo.id_usuario == this.id);
			}
		}
	}
});