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
