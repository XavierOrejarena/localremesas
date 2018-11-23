const app = new Vue({
	el: '#app',
	data: {
		username: '',
		mensaje: '',
		small: 'Nuevo Usuario',
		style: 'border-color: #ff0000;  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(255, 0, 0, 0.6);'
	},
	methods: {
		buscarUsername: function () {
			if (this.username != '') {
				var bodyFormData = new FormData()
				bodyFormData.set('username', this.username.toUpperCase())
				axios({
				    method: 'post',
				    url: './buscarUsername.php',
				    data: bodyFormData,
				    config: { headers: {'Content-Type': 'multipart/form-data' }}
			    })
			    .then( response => {
			    	if (response['data']) {
			    		this.small = 'Username ya existe'
			    	} else {
			    		this.small = 'Username disponible'
			    	}
			    })
			} else {
				this.small = 'Nuevo Usuario'
			}
		},
		verificarUsername: function (e) {
			if (e.target.value.length < 1 || !isNaN(e.target.value[0])) {
				e.target.style = this.style
			} else {
				e.target.style = ''
			}
		},
		verificarPassword: function (e) {
			if (e.target.value.length < 1 || (document.getElementById('rpassword').value != '' && (e.target.value != document.getElementById('rpassword').value))) {
				e.target.style = this.style
			} else {
				e.target.style = ''
			}
		},
		verificarRPassword: function (e) {
			if (document.getElementById('password').value != e.target.value || e.target.value.length < 1) {
				e.target.style = this.style
			} else {
				e.target.style = ''
			}
		},
		registro () {
			if (document.getElementById('username').value.length < 1 || !isNaN(document.getElementById('username').value[0]) || document.getElementById('password').value < 1 || document.getElementById('password').value != document.getElementById('rpassword').value || document.getElementById('rpassword').value.length < 1 || this.small == 'Username ya existe') {
				this.mensaje = 'Verifique los datos ingresados'
			} else {
				var bodyFormData = new FormData();
				bodyFormData.set('username', this.username.toUpperCase());
				bodyFormData.set('password', document.getElementById('password').value);
				axios({
			    method: 'post',
			    url: './registro.php',
			    data: bodyFormData,
			    config: { headers: {'Content-Type': 'multipart/form-data' }}
			    })
			    .then( response => {
			    	if (response['data']) {
			    		window.location.href = "./index.html"

			    	} else {
			    		this.mensaje = 'Error creando usuario'
			    	}
			    })
			}
		}
	},
	beforeMount () {
		axios({
	    method: 'get',
	    url: './session.php',
	    config: { headers: {'Content-Type': 'multipart/form-data' }}
	    })
	    .then( response => {
	    	if (response['data']) {
	    		window.location.href = "./index.html"
	    	}
	    })
	},
	computed: {
		SmallClass: function () {
			var clase = ''
			if (this.small == 'Username ya existe') { clase = 'text-danger'}
			if (this.small == 'Username disponible') { clase = 'text-success'}
			if (this.small == 'Nuevo Usuario') { clase = 'text-primary'}
			return clase
		}
	}
})