const app = new Vue({
	el: '#app',
	data: {
		welcome: true,
		tipo_usuario: 'REGULAR',
	},
	methods: {
		
	},
	beforeMount () {
		axios({
	    method: 'get',
	    url: './session.php',
	    config: { headers: {'Content-Type': 'multipart/form-data' }}
	    })
	    .then( response => {
			if (!(this.welcome = this.tipo_usuario = response['data'])) {
				window.location.href = "./login.html"
			}
	    })
	}
})