function formatMoney(n, c, d, t) {
    var c = isNaN((c = Math.abs(c))) ? 2 : c,
        d = d == undefined ? '.' : d,
        t = t == undefined ? ',' : t,
        s = n < 0 ? '-' : '',
        i = String(parseInt((n = Math.abs(Number(n) || 0).toFixed(c)))),
        j = (j = i.length) > 3 ? j % 3 : 0;

    return (
        s +
        (j ? i.substr(0, j) + t : '') +
        i.substr(j).replace(/(\d{3})(?=\d)/g, '$1' + t) +
        (c
            ? d +
              Math.abs(n - i)
                  .toFixed(c)
                  .slice(2)
            : '')
    );
}
const app = new Vue({
    el: '#app',
    data: {
        mensaje: '',
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
                this.bancos.forEach(banco => {
                    banco.saldo = formatMoney(banco.saldo);
                });
            });
        },
        setBanco(e) {
            axios
                .post('./setBanco.php', new FormData(e.target))
                .then(response => {
                    var that = this;
                    if (response.data) {
                        this.getBancos();
                        e.target[3].value = ''
                        e.target[4].value = ''
                        this.mensaje = 'Pago agregado exitosamente.';
                    } else {
                        this.mensaje = 'Hubo un error al agregar los datos.';
                    }
                    window.setTimeout(function() {
                        $(".alert").fadeTo(500, 0).slideUp(500, function(){
                            that.mensaje = '';
                        });
                    }, 2000);
                });
        }
    },
    computed: {
        filterBancos() {
            return this.bancos.filter(banco => banco.divisa != 'VEF');
        }
    },
    beforeMount() {
        this.getBancos();
    }
});
