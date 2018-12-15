const app = new Vue({
	el: '#app',
	data: {
		pagos: '',
		tipo_usuario: 'REGULAR',
		bancos: []
	},
	methods: {
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
			return bancos[cuenta.substring(0, 4)];
		},
		pagar_out(e, id_pago_in) {
			if (document.getElementById(e).value == '' || document.getElementById(e).value == 0) {
				document.getElementById(e).style =
					'border-color: #ff0000;  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(255, 0, 0, 0.6); box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(255, 0, 0, 0.6);';
			} else {
				document.getElementById(e).style = '';
				if (document.getElementById('f' + e).value == '') {
					// document.getElementById('f'+e).parentElement.style = 'border-color: #ff0000;  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(255, 0, 0, 0.6); box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(255, 0, 0, 0.6);'
					document.getElementById('f' + e).style =
						'border-color: #ff0000;  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(255, 0, 0, 0.6); box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(255, 0, 0, 0.6);';
					console.log('no hay archivo');
				} else {
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
						window.location.href = './pagos_out.html';
					});
				}
			}
		},
		cargar_pagos_out() {
			axios({
				method: 'get',
				url: './cargar_pagos_out.php',
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				console.log(response.data);
				this.pagos = response.data;
			});
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
				this.cargar_pagos_out();
				this.getBancos();
			} else {
				window.location.href = './login.html';
			}
		});
	}
});
