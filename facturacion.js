const app = new Vue({
	el: '#app',
	data: {
		identificaciones: ['RUC', 'DNI', 'CE', 'PASAPORTE'],
        tipo_usuario: null,
        usuarios: null
	},
	methods: {
		getUsuarios(){
			axios({
				method: 'get',
				url: './getUsuarios.php',
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				// this.usuarios.map(usuario => (usuario.selected = 'DNI'))
				for (let i = 0; i < response.data.length; i++) {
					for (let j = 0; j < this.identificaciones.length; j++) {
						if (response.data[i][this.identificaciones[j]] != "null") {
							response.data[i].selected = this.identificaciones[j]
							break
						}
					}
				}
				this.usuarios = response.data;
			});
		},
		test(){
			this.usuarios.push('')
		},
        actualizarCliente(i){
            var bodyFormData = new FormData();
			bodyFormData.set('id', this.usuarios[i].id);
			bodyFormData.set('nombre', this.usuarios[i].nombre);
			bodyFormData.set('apellido', this.usuarios[i].apellido);
			bodyFormData.set('DNI', this.usuarios[i].DNI);
			bodyFormData.set('RUC', this.usuarios[i].RUC);
			bodyFormData.set('CE', this.usuarios[i].CE);
			bodyFormData.set('PASAPORTE', this.usuarios[i].PASAPORTE);
			bodyFormData.set('tlf', this.usuarios[i].tlf);
			bodyFormData.set('correo', this.usuarios[i].correo);
            axios({
				method: 'post',
                url: './actualizarCliente.php',
                data: bodyFormData,
				config: { headers: { 'Content-Type': 'multipart/form-data' } }
			}).then(response => {
				this.getUsuarios()
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
                this.getUsuarios()
			} else {
				window.location.href = './login.html';
			}
		});
	}
});
