const app = new Vue({
	el: '#app',
	data: {
		pagos: '',
		tipo_usuario: 'REGULAR',
	},
	methods: {
		Banco (cuenta) {
			var bancos = {
			'0156': "100%BANCO",
			'0196': "ABN AMRO BANK",
			'0172': "BANCAMIGA BANCO MICROFINANCIERO, C.A.",
			'0171': "BANCO ACTIVO BANCO COMERCIAL, C.A.",
			'0166': "BANCO AGRICOLA",
			'0175': "BANCO BICENTENARIO",
			'0128': "BANCO CARONI, C.A. BANCO UNIVERSAL",
			'0164': "BANCO DE DESARROLLO DEL MICROEMPRESARIO",
			'0102': "BANCO DE VENEZUELA S.A.I.C.A.",
			'0114': "BANCO DEL CARIBE C.A.",
			'0149': "BANCO DEL PUEBLO SOBERANO C.A.",
			'0163': "BANCO DEL TESORO",
			'0176': "BANCO ESPIRITO SANTO, S.A.",
			'0115': "BANCO EXTERIOR C.A.",
			'0003': "BANCO INDUSTRIAL DE VENEZUELA.",
			'0173': "BANCO INTERNACIONAL DE DESARROLLO, C.A.",
			'0105': "BANCO MERCANTIL C.A.",
			'0191': "BANCO NACIONAL DE CREDITO",
			'0116': "BANCO OCCIDENTAL DE DESCUENTO.",
			'0138': "BANCO PLAZA",
			'0108': "BANCO PROVINCIAL BBVA",
			'0104': "BANCO VENEZOLANO DE CREDITO S.A.",
			'0168': "BANCRECER S.A. BANCO DE DESARROLLO",
			'0134': "BANESCO BANCO UNIVERSAL",
			'0177': "BANFANB",
			'0146': "BANGENTE",
			'0174': "BANPLUS BANCO COMERCIAL C.A",
			'0190': "CITIBANK.",
			'0121': "CORP BANCA.",
			'0157': "DELSUR BANCO UNIVERSAL",
			'0151': "FONDO COMUN",
			'0601': "INSTITUTO MUNICIPAL DE CR&#201;DITO POPULAR",
			'0169': "MIBANCO BANCO DE DESARROLLO, C.A.",
			'0137': "SOFITASA"}
			return bancos[cuenta.substring(0,4)]
		},
		pagar_out (e,id_pago_in) {
			if (document.getElementById(e).value == '' || document.getElementById(e).value == 0) {
				document.getElementById(e).style = 'border-color: #ff0000;  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(255, 0, 0, 0.6); box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(255, 0, 0, 0.6);'
			} else {
				document.getElementById(e).style = ''
				if (document.getElementById('f'+e).value == '') {
					// document.getElementById('f'+e).parentElement.style = 'border-color: #ff0000;  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(255, 0, 0, 0.6); box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(255, 0, 0, 0.6);'
					document.getElementById('f'+e).style = 'border-color: #ff0000;  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(255, 0, 0, 0.6); box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(255, 0, 0, 0.6);'
					console.log('no hay archivo')
				} else {
					var barra = document.getElementById("barra");
					var bodyFormData = new FormData();
					bodyFormData.append('comprobante', document.getElementById('f'+e).files[0]);
					bodyFormData.set('id', e);
					bodyFormData.set('id_pago_in', id_pago_in);
					bodyFormData.set('referencia', document.getElementById(e).value);
					axios({
				    method: 'post',
				    url: './pagar_out.php',
				    data: bodyFormData,
					config: { headers: {'Content-Type': 'multipart/form-data' }},
					onUploadProgress: (e) => {
						if (e.lengthComputable) {
						   var p = Math.round((e.loaded/e.total)*100);
						   barra.style ="width: "+ p +"%";
						   barra.innerHTML = p + "%";
						   this.small = '...'
						   this.tipo_cliente = '...'
						}
			   }
				    })
				    .then( response => {
						this.cargar_pagos_out()
						barra.style ="width: 0%";
						barra.innerHTML = "";
				    })
				}
			}
		},
		cargar_pagos_out () {
			axios({
		    method: 'get',
		    url: './cargar_pagos_out.php',
		    config: { headers: {'Content-Type': 'multipart/form-data' }}
		    })
		    .then( response => {
				this.pagos = response['data']
		    })
		}
	},
	beforeMount () {
		axios({
	    method: 'get',
	    url: './session.php',
	    config: { headers: {'Content-Type': 'multipart/form-data' }}
	    })
	    .then( response => {
	    	if (response['data'] == 'ADMIN'  || response['data'] == 'OPERADOR') {
				this.tipo_usuario = response.data
				this.cargar_pagos_out()
	    	} else {
				window.location.href = "./login.html"
			}
	    })
	}
})