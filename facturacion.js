const app = new Vue({
	el: '#app',
	data: {
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
				this.usuarios = response.data;
			});
        },
        actualizarCliente(i){
            console.log(this.usuarios[i].id)
            var bodyFormData = new FormData();
			bodyFormData.set('id', this.usuarios[i].id);
			bodyFormData.set('nombre', this.usuarios[i].nombre);
			bodyFormData.set('apellido', this.usuarios[i].apellido);
			bodyFormData.set('dni', this.usuarios[i].dni);
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
