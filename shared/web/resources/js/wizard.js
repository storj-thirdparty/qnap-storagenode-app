console.log('hello world')

const app = new Vue({
	el: "#app",
	data: {
		step: 1,

		email: '',
		address: '',
		storage: 1000,
		host: '',
		identity: '',

	},
	computed: {
		stepClass() {
			const obj = {};

			obj['step'] = true;
			obj[`step-${this.step}`] = true;

			return obj;
		},

		emailValid() {
			return this.email.match(
				/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
			);
		},

		addressValid() {
			return this.address.match(/^0x[a-fA-F0-9]{40}$/g);
		},

		storageValid() {
			return this.storage > 0;
		},

		hostValid() {
			const [host, port] = this.host.split(':');

			if(typeof port !== 'string' || port.length === 0) {
				return false;
			}

			if(isNaN(Number(port)) === true) {
				return false;
			}

			return true;
		}
	},
	methods: {
		finish() {
			console.log({
				email: this.email,
				address: this.address,
				host: this.host,
				identity: this.identity
			})
		}
	}
});
