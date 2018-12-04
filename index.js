const app = new Vue({
	el: '#app',
	data: {
		welcome: false,
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
			this.welcome = this.tipo_usuario = response['data']
	    })
	}
})