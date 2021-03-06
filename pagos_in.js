const app = new Vue({
	el: '#app',
	data: {
		pagos: '',
		tipo_usuario: 'REGULAR',
		mensajes: [],
		errores: []
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
		rechazar_in(e) {
			this.mensajes = []
			this.errores = []
			var bodyFormData = new FormData();
			bodyFormData.set('id', e);
			axios({
				method: 'post',
				url: './rechazar_in.php',
				data: bodyFormData,
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				this.mensajes = [response.data.mensajes]
				this.errores = [response.data.errores]
				this.cargar_pagos_in();
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
		aprobar_in(e) {
			this.mensajes = []
			this.errores = []
			var bodyFormData = new FormData();
			bodyFormData.set('id', e);
			axios({
				method: 'post',
				url: './aprobar_in.php',
				data: bodyFormData,
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				console.log(response.data)
				this.mensajes = [response.data.mensajes]
				this.errores = [response.data.errores]
				this.cargar_pagos_in();
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
		cargar_pagos_in() {
			axios({
				method: 'get',
				url: './cargar_pagos_in.php',
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				this.pagos = response['data'];
			});
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
				this.cargar_pagos_in();
			} else {
				window.location.href = './login.html';
			}
		});
	}
});
