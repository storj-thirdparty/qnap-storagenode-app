
function resizeInterface() {
	const scale = Math.min(window.innerWidth / 1400, window.innerHeight / 900);
	document.querySelector("#app").style.transform = `scale(${scale})`;
}

resizeInterface();

const app = new Vue({
	el: "#app",
	data: {
		step: 1,
		identityStep: 1,
		identityLogs: "",

		email: "",
		address: "",
		storage: 10000,
		directory: "",
		host: "",
		identity: "",
		authkey: "",
		message: "",
		processrun: false
	},

	created () {
        this.email = document.querySelector(".email").value;
        this.address = document.querySelector(".address").value;
        this.storage = document.querySelector(".storage").value;
        this.directory = document.querySelector(".directory").value;
        this.host = document.querySelector(".host").value;
        this.identity = document.querySelector(".identity").value;

        this.authkey = document.querySelector("#authkey").value;
    },

	computed: {

		stepClass() {
			const obj = {};

			obj["step"] = true;
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
			const [host, port] = this.host.split(":");

			if(typeof port !== "string" || port.lenth === 0) {
				return false;
			}

			if(isNaN(Number(port)) === true) {
				return false;
			}

			return true;
		},

		identityValid() {
			return this.identity.length > 1;
		},

		authkeyValid() {
			if(this.processrun ==false){
				return this.authkey.length > 1;
			}
		},

		identityGenerationFinished() {
			return this.message.toLowerCase().includes("found");
		}
	},
	methods: {

		async generateIdentity() {
			this.identityStep++;
			this.createidentifyToken();
			setInterval(() => this.updateLog(), 60000);
		},
		async finish() {
			const data = {
				email: this.email,
				address: this.address,
				host: this.host,
				storage: this.storage,
				directory: this.directory,
				identity: this.identity
			};

			await axios.post("config.php", data);

			location.href = "config.php";
		},
		async createidentifyToken() {
			const {data} = await axios.post("identity.php", {
				authkey: this.authkey,
				identity: this.identity,
			});

			this.message = data;

			if(data !== "Identity Key File and others already available"){
				this.message = "<p>"+data+"</p>";
			}
    	},
    	async updateLog() {
			const {data} = await axios.post("identity.php", {
				status: true
			});

			this.message = data;
		},
		async processCheck() {
			this.identityStep++;
			const {data} = await axios.post("identity.php", {
				identityCreationProcessCheck: true
			});

			this.processrun = data;
		},
	}
});
