const app = new Vue({
	el: '#app',
	data: {
		pagos_in: '',
		pagos_out: '',
		tipo_usuario: 'REGULAR'
	},
	methods: {
		cargar_pagos_in() {

		},
		rechazar_in(e) {
			var bodyFormData = new FormData();
			bodyFormData.set('id', e);
			axios({
				method: 'post',
				url: './rechazar_in.php',
				data: bodyFormData,
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				console.log(response);
				this.cargar_pagos_all();
			});
		},
		aprobar_in(e) {
			var bodyFormData = new FormData();
			bodyFormData.set('id', e);
			axios({
				method: 'post',
				url: './aprobar_in.php',
				data: bodyFormData,
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				console.log(response);
				this.cargar_pagos_all();
			});
		},
		cargar_pagos_all() {
			axios({
				method: 'get',
				url: './cargar_pagos_all.php',
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				console.log(response.data)
				if (response.data) {
					this.pagos_in = response.data.in;
					this.pagos_out = response.data.out;
				}
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
				this.cargar_pagos_all();
			} else {
				window.location.href = './login.html';
			}
		});
	}
});
