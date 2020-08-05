<!DOCTYPE html>
<head>
	<link href="resources/css/wizard.css" rel="stylesheet">
	 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
	<?php
                
                $authPass = $_COOKIE['authPass'];
                $loginMode =  json_decode(file_get_contents("logindata.json"), TRUE);
  
                if ((is_null($authPass) || $authPass == "0") && $loginMode['mode'] == "true")
                {
                    $previous_location = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                    setcookie("previous_location", $previous_location, strtotime( '+7 days' ), "/"); // 86400 = 1 day
                    echo '<script>window.location.href = "login.php";</script>';
                }
                $file = "config.json";
		if(file_exists($file)){
		  	$content = file_get_contents($file);
		  	$prop = json_decode($content, true);
		}
	?>

	<div id="app">
		<a href="config.php" v-if="step > 1"><i class="fa fa-home homeicon"></i></a>
		<div v-bind:class="stepClass">

			<img class="back" src="resources/img/back.png" v-if="step > 1 && (step !== 7 || identityStep === 1)" v-on:click="step--">
			<img class="back" src="resources/img/back.png" v-else-if="identityStep > 1" v-on:click="identityStep--">

			<div v-if="step === 1">
			<div class="head"><img src="resources/img/wizard/step-1-head.png"></div>
				<h1 class="title">Storage Node Setup</h1>
				<p>Monetize your excess capacity on the Storj Network</p>

				<button class="start" v-on:click="step++">Get Started</button>
			</div>

			<div v-if="step === 2">
				<div class="head"><img src="resources/img/wizard/step-2-head.png" /></div>
				<h1 class="title">Connect your Email Address</h1>

				<p class="tagline">Join thousands of Node Operators around the world by getting  Node status updates from Storj Labs.</p>

				<label class="email-label">Email Address</label>
				<span class="email-error error-msg" v-if="!emailValid">Please enter a valid email address</span>
				<input type="email" class="email" placeholder="mail@default.com" v-model="email" v-bind:class="{ invalid: !emailValid }" value="<?php if(isset($prop['Email'])) echo $prop['Email'] ?>" required>

				<button class="skip" v-on:click="step++">Skip this step</button>
				<button class="continue" v-on:click="step++" v-bind:disabled="!emailValid">Continue</button>
			</div>

			<div v-if="step === 3">
			<div class="head"><img src="resources/img/wizard/step-3-head.png" /></div>
				<h1 class="title">Connect your Ethereum Wallet Address</h1>
				<p class="tagline">In order to recieve and hold your STORJ token payouts, you need an <a href="https://support.storj.io/hc/en-us/articles/360026611692-How-do-I-hold-STORJ-What-is-a-valid-address-or-compatible-wallet" target="_blank">ERC-20 compatible wallet address</a></p>

				<span class="error-msg address-error" v-if="!addressValid">Please enter a valid ERC-20 address</span>
				<input type="text" class="address" placeholder="Enter ERC-20 Token Compatible Wallet Address" v-model="address" v-bind:class="{ invalid: !addressValid }" value="<?php if(isset($prop['Wallet'])) echo $prop['Wallet'] ?>" required>

				<button class="continue" v-on:click="step++" v-bind:disabled="!addressValid">Continue</button>
			</div>

			<div v-if="step === 4">
			<div class="head"><img src="resources/img/wizard/step-4-head.png" /></div>
				<h1 class="title">Set Your Storage Allocation</h1>

				<p class="tagline">How much disk space do you want to allocate to the Storj Network?</p>

				<label class="storage-label">Storage Allocation</label>
				<span class="error-msg storage-error" v-if="!storageValid">Invalid Entry</span>
				<input class="storage" type="number" min="1" max="1000" value="10000" v-model="storage" v-bind:class="{ invalid: !storageValid }" value="<?php if(isset($prop['Allocation'])) echo $prop['Allocation'] ?>" required>
				<span class="unit">GB</span>

				<button class="continue" v-on:click="step++" v-bind:disabled="!storageValid">Continue</button>
			</div>

			<div v-if="step === 5">
			<div class="head"><img src="resources/img/wizard/step-5-head.png" /></div>
				<h1 class="title">Set Storage Directory</h1>

				<p class="tagline">The local directory where you want files to be stored on your hard drive for the network</p>

				<label class="directory-label">Storage Directory</label>
				<div class="input-group">
				  <input class="directory" type="text" placeholder="/path/to/folder_to_share" v-model="directory" v-bind:class="{ invalid: !directoryValid }" value="<?php if(isset($prop['Directory'])) echo $prop['Directory'] ?>" required>
				  <div class="input-group-prepend">
						<button class="browse" v-on:click="directoryBrowse = true"><img src="resources/img/wizard/folder.svg" class="browse-svg"/>Browse</button>
				  </div>
				</div>

				<file-browser v-if="directoryBrowse" v-on:selected="setDirectory"></file-browser>
				<button class="continue" v-on:click="step++" v-bind:disabled="!directoryValid">Continue</button>
			</div>

			<div v-if="step === 6">
			<div class="head"><img src="resources/img/wizard/step-6-head.png" /></div>
				<h1 class="title">Configure Your External Port Forwarding</h1>

				<p class="tagline">How a storage node communicates with others on the Storj Network, even though it is behind a router. Learn how to configure your DNS and port forwarding with our <a href="https://documentation.storj.io/dependencies/port-forwarding" target="_blank">documentation.</a> </p>

				<label class="host-label">Host Address</label>
				<span class="error-msg host-error" v-if="!hostValid">Please enter a valid address</span>
				<input class="host" type="text" placeholder="hostname.ddns.net:28967" v-model="host" v-bind:class="{ invalid: !hostValid }" value="<?php if(isset($prop['Port'])) echo $prop['Port'] ?>" required>

				<button class="continue" v-on:click="step++" v-bind:disabled="!hostValid">Continue</button>
			</div>

			<div v-if="step === 7">
				<div class="identity-step-1" v-if="identityStep === 1">
				<div class="head"><img src="resources/img/wizard/step-7-head.png" /></div>
					<h1 class="title">Setup Your Identity Path</h1>


					<p class="tagline">Every Node is required to have an identity on the Storj Network. If you’ve already generated and signed your identity for your QNAP Node, enter the path below and click Finish. If you do not have an identity you’ll need to get an <a href="https://storj.io/sign-up-node-operator/" target="_blank">authorization token</a>.</p>

					<label class="identity-label" for="identityPath">Identity Path</label>
					<div class="input-group">
						<input class="identity" type="text" placeholder="/path/to/identity" v-model="identity" value="<?php if(isset($prop['Identity'])) echo $prop['Identity'] ?>" v-bind:class="{ invalid: !identityValid }">
				    <div class="input-group-prepend">
							<button class="browse" v-on:click="directoryBrowse = true"><img src="resources/img/wizard/folder.svg" class="browse-svg"/>Browse</button>
						</div>
				  </div>

          		<file-browser v-if="directoryBrowse" v-on:selected="setIdentityDirectory"></file-browser>

					<button class="no-identity" v-on:click="processCheck">I don't have an identity</button>
					<button class="finish" v-on:click="finish" v-bind:disabled="!identityValid">Finish</button>
				</div>

				<div class="identity-step-2" v-if="identityStep === 2">
				<div class="head"><img src="resources/img/wizard/step-7-head.png" /></div>
					<h1 class="title">Enter Authorization Token</h1>


					<label>Authorization Token</label>
					<span class="error-msg authkey-error" v-if="!authkeyValid">Please enter a valid authorization token</span>
					<input  type="text" placeholder="your@email.com:1BTJeyYWAquvfQWscG9VndHjyYk8PSzQvrJ5DC" id="authkey"  value="<?php if(isset($prop['AuthKey'])) echo $prop['AuthKey'] ?>"  v-model="authkey" v-bind:class="{ invalid: !authkeyValid }" required><br><br><br><br>

					<button class="generate" v-on:click="generateIdentity" v-bind:disabled="!authkeyValid">Generate</button>
				</div>

				<div class="identity-step-3" v-if="identityStep === 3">
				<div class="head"><img src="resources/img/wizard/step-7-head.png" /></div>
					<h1 class="title">Identity Generation Started</h1>

					<p class="tagline">Creating identity can take several hours or even days, depending on your machines processing power & probability. You will be able to track your progress after configuring the rest</p>

					<div class="logs" v-html="message">{{identityLogs}}</div>


					<button class="finish"  v-on:click="finish">Finish</button>
				</div>
			</div>

			<div class="progress" v-if="step > 1" style="-webkit-box-shadow: 0px;box-shadow: inset 0 1px 2px #fff;background-color: white;">
				<div class="point-1" v-bind:class="{ 'point-active': step > 1 }"></div>
				<div class="label-1" v-if="step === 2" v-bind:class="{ 'label-active': step > 1 }">1</div>
				<div class="label-1" v-if="step > 2" v-bind:class="{ 'checkmark': step > 1 }"><img src="resources/img/wizard/checkmark.png"></div>
				<div class="text-1" v-bind:class="{ 'text-active': step === 2 }">Email Address</div>

				<div class="bar-1" v-bind:class="{ 'bar-active': step > 2 }"></div>

				<div class="point-2" v-bind:class="{ 'point-active': step > 2 }"></div>
				<div class="label-2" v-if="step === 3" v-bind:class="{ 'label-active': step > 2 }">2</div>
				<div class="label-2" v-if="step > 3" v-bind:class="{ 'checkmark': step > 2 }"><img src="resources/img/wizard/checkmark.png"></div>
				<div class="text-2" v-bind:class="{ 'text-active': step === 3 }">ETH Wallet Address</div>

				<div class="bar-2" v-bind:class="{ 'bar-active': step > 3 }"></div>

				<div class="point-3" v-bind:class="{ 'point-active': step > 3 }"></div>

				<div class="label-3" v-if="step === 4" v-bind:class="{ 'label-active': step > 3 }">3</div>
				<div class="label-3" v-if="step > 4" v-bind:class="{ 'checkmark': step > 3 }"><img src="resources/img/wizard/checkmark.png"></div>
				<div class="text-3" v-bind:class="{ 'text-active': step === 4 }">Storage Allocation</div>

				<div class="bar-3" v-bind:class="{ 'bar-active': step > 4 }"></div>

				<div class="point-4" v-bind:class="{ 'point-active': step > 4 }"></div>

				<div class="label-4" v-if="step === 5" v-bind:class="{ 'label-active': step > 4 }">4</div>
				<div class="label-4" v-if="step > 5" v-bind:class="{ 'checkmark': step > 4 }"><img src="resources/img/wizard/checkmark.png"></div>
				<div class="text-4" v-bind:class="{ 'text-active': step === 5 }">Storage Directory</div>

				<div class="bar-4" v-bind:class="{ 'bar-active': step > 5 }"></div>

				<div class="point-5" v-bind:class="{ 'point-active': step > 5 }"></div>

				<div class="label-5" v-if="step === 6" v-bind:class="{ 'label-active': step > 5 }">5</div>
				<div class="label-5" v-if="step > 6" v-bind:class="{ 'checkmark': step > 5 }"><img src="resources/img/wizard/checkmark.png"></div>
				<div class="text-5" v-bind:class="{ 'text-active': step === 6 }">Port Forwarding</div>

				<div class="bar-5" v-bind:class="{ 'bar-active': step > 6 }"></div>

				<div class="point-6" v-bind:class="{ 'point-active': step > 6 }"></div>

				<div class="label-6" v-if="step === 7" v-bind:class="{ 'label-active': step > 6 }">6</div>
				<div class="label-6" v-if="step > 7" v-bind:class="{ 'checkmark': step > 6 }"><img src="resources/img/wizard/checkmark.png"></div>
				<div class="text-6" v-bind:class="{ 'text-active': step === 7 }">Identity</div>
			</div>
		</div>
	</div>

	<script src="resources/js/vue.js"></script>
	<script src="resources/js/axios.min.js"></script>
	<script src="resources/js/wizard.js"></script>

</body>
