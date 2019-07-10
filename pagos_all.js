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
						Array.prototype.forEach.call(this.pagos_out, e => {
							if (number == e.id) {
								e.capture = element;
							}
						})
					}
				})

			});
		}
	},
	beforeMount() {
		this.cargar_pagos_all();
		this.captures_out();
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
	},
	computed: {
	}
});
