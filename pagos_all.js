const app = new Vue({
	el: '#app',
	data: {
		pagos_in: '',
		pagos_out: '',
		tipo_usuario: 'REGULAR'
	},
	methods: {
		rechazar_in(e) {
			var bodyFormData = new FormData();
			bodyFormData.set('id', e);
			axios({
				method: 'post',
				url: './rechazar_in.php',
				data: bodyFormData,
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
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
		},
		filterTasa(id) {
			var total = 0;
			for (let i = 0; i < this.pagos_out.length; i++) {
				if (this.pagos_out[i].id_pago_in == id) {
					total = total + parseInt(this.pagos_out[i].monto)
				}
				
			}
			return total;
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
	},
	computed: {
	}
});
