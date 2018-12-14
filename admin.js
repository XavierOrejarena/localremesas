const app = new Vue({
	el: '#app',
	data: {
		tipos: ['REGULAR', 'OPERADOR', 'MAYORISTA', 'BUSCADOR', 'ESPECIAL', 'ADMIN'],
		tasas: '',
		clase: '',
		id: '',
		small: '',
		tipo_usuario: '',
		usuario: '',
		mensaje: '',
		bancos: '',
		prestamos: '',
		pagos_in: ''
	},
	methods: {
		setClase(clase) {
			this.clase = clase;
			localStorage.setItem('clase', clase);
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
		getPrestamos() {
			axios({
				method: 'get',
				url: './getPrestamos.php',
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				this.prestamos = response.data;
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
		}
	},
	beforeMount() {
		axios({
			method: 'get',
			url: './session.php',
			config: { headers: { 'Content-Type': 'multipart/form-data' } }
		}).then(response => {
			if (response['data'] == 'ADMIN') {
				this.clase = localStorage.getItem('clase');
				this.tipo_usuario = response.data;
				this.cargarTasas();
				this.getBancos();
				this.getPrestamos();
			} else {
				window.location.href = './login.html';
			}
		});
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
			return this.tipos.filter(tipo => tipo != this.usuario.tipo);
		}
	}
});
