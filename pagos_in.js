const app = new Vue({
	el: '#app',
	data: {
		pagos: '',
		tipo_usuario: 'REGULAR',
	},
	methods: {
		rechazar_in (e) {
			var bodyFormData = new FormData();
			bodyFormData.set('id', e);
			axios({
		    method: 'post',
		    url: './rechazar_in.php',
		    data: bodyFormData,
		    config: { headers: {'Content-Type': 'multipart/form-data' }}
		    })
		    .then( response => {
				console.log(response)
				this.cargar_pagos_in()
		    })
		},
		aprobar_in (e) {
			var bodyFormData = new FormData();
			bodyFormData.set('id', e);
			axios({
		    method: 'post',
		    url: './aprobar_in.php',
		    data: bodyFormData,
		    config: { headers: {'Content-Type': 'multipart/form-data' }}
		    })
		    .then( response => {
				console.log(response)
				this.cargar_pagos_in()
		    })
		},
		cargar_pagos_in () {
			axios({
		    method: 'get',
		    url: './cargar_pagos_in.php',
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
	    		this.cargar_pagos_in()
	    	} else {
				window.location.href = "./index.html"
			}
	    })
	}
})