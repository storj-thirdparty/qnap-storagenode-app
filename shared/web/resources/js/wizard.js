console.log('hello world')

function resizeInterface() {
	const scale = Math.min(window.innerWidth / 1400, 1400);
	console.log(scale);
	document.querySelector("#app").style.transform = `scale(${scale})`;
}

resizeInterface();

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
		authkey: '',

	},

	created () {
        this.email = document.querySelector(".email").value
        this.address = document.querySelector(".address").value
        this.storage = document.querySelector(".storage").value
        this.directory = document.querySelector(".directory").value
        this.host = document.querySelector(".host").value
        this.identity = document.querySelector(".identity").value

        this.authkey = document.querySelector("#authkey").value
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
		},

		identityValid() {
			return this.identity.length > 1;
		}
	},
	methods: {
		async generateIdentity() {
			var authkey = $("#authkey").val();
			// var identitypath = "/root/.local/share/storj/identity/storagenode";
			// this.identity = identitypath;
			if(authkey !== ""){
				createidentifyToken(authkey,this.identity);
				this.identityStep++;


				readidentitystatus();

				setInterval(() => readidentitystatus(), 60000);

			}
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
				storage: this.storage,
				directory: this.directory,
				identity: this.identity
			};

			await axios.post('config.php', data);

			location.href = 'config.php';
		}
	}
});

 // Create identity.
function createidentifyToken(createidval,identitypath){
   jQuery.ajax({
      type: "POST",
      url: "identity.php",
      data: {
	    createidval : createidval,
	    identitypath : identitypath,
	    identityString: createidval
       },
      success: function (result) {
        $(".logs").html("<b>Identity creation process is starting.</b><br><p>"+result+"</p>");
      },
      error: function () {
        console.log("Error during create Identitfy operation");
      }
    });
}



// Read status from identity.php file.
function readidentitystatus(){
   jQuery.ajax({
      type: "POST",
      url: "identity.php",
      data: {status : "status",},
      success: function (result) {
        if(result == "identity available at /root/.local/share/storj/identity"){
          $(".logs").html("<b>"+result+"</b>");
          identitydataval = 1;
        }else{
           $(".logs").html("<b>Identity creation process is running.</b><br><p>"+result+"</p>");
        }
      },
      error: function () {
        console.log("In tehre wrong on create Identitfy");
      }
    });
}
