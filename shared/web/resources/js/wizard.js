console.log('hello world')

const app = new Vue({
	el: "#app",
	data: {
		step: 1,
		identityStep: 1,
		identityLogs: '',

		email: '',
		address: '',
		storage: 1000,
		directory: '',
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

		directoryValid() {
			return this.directory.length > 1;
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
		async generateIdentity() {
			try {
				await axios.post('identity.php', {
					createidval: true
				});
			} catch(err) {
				alert('Failed to start identity creation. Check console for details');
				console.log(err);

				return;
			}

			this.identityStep++;

			updateLog();

			setTimeout(() => this.updateLog(), 10 * 1000);
		},

		async updateLog() {
			const {data} = await axios.post('identity.php', {
				status: true
			});

			this.log = data;
		},

		async finish() {
			const data = {
				email: this.email,
				address: this.address,
				host: this.host,
				identity: this.identity
			};

			await axios.post('config.php', data);

			location.href = 'dashboard.php';
		}
	}
});
