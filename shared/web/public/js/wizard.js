let debug = false;

const getFolders = debug
	? async path => {
		if(path === '/') {
			return [
				'test/',
				'a/',
				'b/',
				'c/'
			]
		}

		if(path === '/a/') {
			return [
				'photos/',
				'documents/'
			]
		}

		if(path === '/a/photos/') {
			return [
				'holiday/',
				'mountains/'
			]
		}

		return new Promise(resolve => {});
	}
	: async path => {
		const {data} = await axios.post('getdirectorylisting', {
			data: {
				action: 'folders',
				path
			}
		});
		return data.folders;
	};

Vue.component(`file-browser`, {
	template: `<div class='file-browser' v-click-outside="outside">
		<div class='file-browser-container'>
			<h2 class='file-browser-path'>{{path}}</h2>

			<ul class='file-browser-list'>
				<li v-if="path.length > 1" class="file-browser-file" v-on:dblclick="path = path.slice(0, -1).split('/').slice(0, -1).join('/')"><img :src="'img/wizard/back.svg'" alt="Back">../</li>

				<li
					v-for="file in files" v-on:dblclick="setpath(file)"
					v-on:click="selectFile(file)"
					v-bind:class="{
						'file-browser-file': true,
						'file-browser-selected': selectedPath === path + file
					}"
				><img :src="'img/wizard/folder.svg'"  alt="Folder">{{file}}</li>
			</ul>

			<button class='file-browser-done' v-on:click="done">Choose this directory</button>
		</div>
	</div>`,
	data: () => ({
		path: '/',
		files: [],
		selectedPath: '',
		loading: false
	}),
         directives: {
            'click-outside': {
                bind: function (el, binding, vnode) {

                        this.event = function (event) {
                         if (!(el == event.target || el.contains(event.target) || event.target.className == "browse" || event.target.className == "browse-svg" || event.target.className == "browse-png")) {
                            vnode.context[binding.expression](event);
                          }
                        };
                        document.body.addEventListener('click', this.event)
                      },
                      unbind: function (el) {
                        document.body.removeEventListener('click', this.event)
                      },
            }
        },
	methods: {
		async loadFiles() {
			this.loading = true;
			this.files = (await getFolders(this.path)).filter(file => file !== '../');
			this.loading = false;
		},

		selectFile(file) {
			if(this.loading === false) {
                                 if (this.path != "/") {
                                    this.selectedPath = this.path +"/"+ file;
                                } else {
                                   this.selectedPath = this.path + file;
                                }
			}
		},

		done() {
			this.$emit('selected', this.selectedPath);
		},
                outside: function (e) {
                   this.selectedPath = "outside";
                   this.$emit('selected', this.selectedPath);

                },
                setpath(file){
                    if(this.path != "/"){
                        this.path +='/'+ file;
                    }else{
                        this.path += file;
                    }

                }
	},
	watch: {
		path() {
			this.loadFiles();
		}
	},
	async created() {
		this.loadFiles();
	}
});

const app = new Vue({
	el: "#app",
	data: {
		step: 1,
		identityStep: 1,
		identityLogs: "",

		email: "",
		address: "",
		storage: 10000,
		directory: '',
		directoryBrowse: false,
		host: '',
		identity: '',
		authkey: '',
		message: '',
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
			setInterval(() => this.updateLog(), 5 * 60 * 1000);
		},
                setDirectory(selected) {
                    if (selected != "outside") {
                        this.directory = selected;
                        this.directoryBrowse = false;
                    } else {
                        this.directoryBrowse = false;
                    }

                },
		setIdentityDirectory(selected) {
                    if (selected != "outside") {
                        this.identity = selected;
                        this.directoryBrowse = false;
                    } else {
                        this.directoryBrowse = false;
                    }
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

			await axios.post("saveconfig", data);

			location.href = "config";
		},
		async createidentifyToken() {
			const {data} = await axios.post("getidentity", {
				authkey: this.authkey,
				identity: this.identity,
			});

			this.message = data;

			if(data !== "Identity Key File and others already available"){
				this.message = "<p>"+data+"</p>";
			}
    	},
    	async updateLog() {
			const {data} = await axios.post("getidentity", {
				status: true
			});

			this.message = data;
		},
		async processCheck() {
			this.identityStep++;
			const {data} = await axios.post("getidentity", {
				identityCreationProcessCheck: true
			});

			this.processrun = data;
		},
	}
});
