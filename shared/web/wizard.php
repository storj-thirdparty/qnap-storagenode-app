<!DOCTYPE html>
<head>
	<link href="resources/css/wizard.css" rel="stylesheet">
	 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
	<?php
		$file = "config.json";
		if(file_exists($file)){
		  	$content = file_get_contents($file);
		  	$prop = json_decode($content, true);
		  }
	?>

	<div id="app">
		<a href="config.php"><i class="fa fa-home homeicon"></i></a>
		<div v-bind:class="stepClass">

			<img class="back" src="resources/img/back.png" v-if="step > 1" v-on:click="step--">

			<div v-if="step === 1">
			<div class="head"><img src="resources/img/wizard/step-1-head.png"></div>
				<h1 class="title">Welcome to Storj!</h1>
				<p>Monetize your excess capacity on the Storj Network</p>

				<button class="start" v-on:click="step++">Start</button>
			</div>

			<div v-if="step === 2">
				<div class="head"><img src="resources/img/wizard/step-2-head.png" /></div>
				<h1 class="title">Connect your Email Address</h1>

				<p class="tagline">In order to recieve and hold your STORJ token payouts, you need an ERC-20 compatible wallet address</p>

				<label class="email-label">Email Address</label>
				<input type="email" class="email" placeholder="mail@default.com" v-model="email" v-bind:class="{ invalid: !emailValid }" value="<?php if(isset($prop['Email'])) echo $prop['Email'] ?>">

				<button class="skip" v-on:click="step++">Skip this step</button>
				<button class="continue" v-on:click="step++" v-bind:disabled="!emailValid">Continue</button>
			</div>

			<div v-if="step === 3">
			<div class="head"><img src="resources/img/wizard/step-3-head.png" /></div>
				<h1 class="title">Connect your Ethereum Wallet Address</h1>

				
				<p class="tagline">Join thousands of Node Operators around the world by getting Node status updates from Storj Labs</p>
			

				<label class="address-label">ETH Wallet Address</label>
				<input type="text" class="address" placeholder="Enter ETH Wallet Address" v-model="address" v-bind:class="{ invalid: !addressValid }" value="<?php if(isset($prop['Wallet'])) echo $prop['Wallet'] ?>">

				<button class="continue" v-on:click="step++" v-bind:disabled="!addressValid">Continue</button>
			</div>

			<div v-if="step === 4">
			<div class="head"><img src="resources/img/wizard/step-4-head.png" /></div>
				<h1 class="title">Set Your Storage Allocation</h1>

				<p class="tagline">How much disk space you want to allocate to the Storj network</p>

				<label class="storage-label">Storage Allocation</label>
				<input class="storage" type="number" min="1" max="1000" value="1000000" v-model="storage" v-bind:class="{ invalid: !storageValid }" value="<?php if(isset($prop['Allocation'])) echo $prop['Allocation'] ?>">
				<span class="unit">GB</span>

				<button class="continue" v-on:click="step++" v-bind:disabled="!storageValid">Continue</button>
			</div>

			<div v-if="step === 5">
			<div class="head"><img src="resources/img/wizard/step-5-head.png" /></div>
				<h1 class="title">Set Storage Directory</h1>

				<p class="tagline">The local directory where you want files to be stored on your hard drive for the network</p>

				<label class="directory-label">Storage Directory</label>
				<input class="directory" type="text" placeholder="/path/to/folder_to_share" v-model="directory" v-bind:class="{ invalid: !directoryValid }" value="<?php if(isset($prop['Directory'])) echo $prop['Directory'] ?>">

				<button class="continue" v-on:click="step++" v-bind:disabled="!directoryValid">Continue</button>
			</div>

			<div v-if="step === 6">
			<div class="head"><img src="resources/img/wizard/step-6-head.png" /></div>
				<h1 class="title">Configure Your External Port Forwarding</h1>

				<p class="tagline">How a storage node communicates with others on the Storj network, even though it is behind a router. You need a dynamic DNS service to ensure your storage node is connected</p>

				<label class="host-label">Host Address</label>
				<input class="host" type="text" placeholder="hostname.ddns.net:28967" v-model="host" v-bind:class="{ invalid: !hostValid }" value="<?php if(isset($prop['Port'])) echo $prop['Port'] ?>">

				<button class="continue" v-on:click="step++" v-bind:disabled="!hostValid">Continue</button>
			</div>

			<div v-if="step === 7">
				<div class="identity-step-1" v-if="identityStep === 1">
				<div class="head"><img src="resources/img/wizard/step-7-head.png" /></div>
					<h1 class="title">Setup Your Identity Path</h1>

					<p class="tagline">Every node is required to have a unique identifier on the network. If you haven't already, get an authorization token. Please get the authorization token and create identity on host machine other than NAS</p>

					<label class="identity-label">Identity Path</label>
					<input class="identity" type="text" placeholder="/path/to/identity" v-model="identity" value="<?php if(isset($prop['Identity'])) echo $prop['Identity'] ?>" v-bind:class="{ invalid: !identityValid }">

					<button class="no-identity" v-on:click="identityStep++" v-bind:disabled="!identityValid">I don't have an identity</button>
					<button class="finish" v-on:click="step++" v-bind:disabled="!identityValid">Finish</button>
				</div>

				<div class="identity-step-2" v-if="identityStep === 2">
				<div class="head"><img src="resources/img/wizard/step-7-head.png" /></div>
					<h1 class="title">Generate Your Identity</h1>

					<p class="tagline">Every node is required to have a unique identifier on the network. If you haven't already, get an authorization token. Please get the authorization token and create identity on host machine other than NAS</p>

					<label>Authorization Token</label>
					<input  type="text" placeholder="your@email.com: 1BTJeyYWAquvfQWscG9VndHjyYk8PSzQvrJ5DC" id="authkey"  value="<?php if(isset($prop['AuthKey'])) echo $prop['AuthKey'] ?>"  v-model="authkey" v-bind:class="{ invalid: !authkeyValid }"><br><br><br><br>


					<button class="generate" v-on:click="generateIdentity" v-bind:disabled="!authkeyValid">Generate</button>



					<div class="warning-icon"><img src="resources/img/i.png"></img></div>
					<p class="warning">Creating identity can take several hours or even days, depending on your machines processing power.</p>
				</div>

				<div class="identity-step-3" v-if="identityStep === 3">
				<div class="head"><img src="resources/img/wizard/step-7-head.png" /></div>
					<h1 class="title">Identity Generation Started</h1>

					<p class="tagline">Creating identity can take several hours or even days, depending on your machines processing power & probability. You will be able to track your progress after configuring the rest</p>

					<div class="logs" v-html="message">{{identityLogs}}</div>


					<button class="finish" v-on:click="step++">Finish</button>
				</div>
			</div>

			<div v-if="step === 8">
			<div class="head"><img src="resources/img/wizard/step-8-head.png" /></div>
				<h1 class="title">Congratulations!</h1>

				<p class="tagline">You finished the quest and ready to go</p>

				<button class="finish" v-on:click="finish">Finish</button>
			</div>

			<div class="progress" v-if="step > 1" style="-webkit-box-shadow: 0px;box-shadow: inset 0 1px 2px #fff;background-color: white;">
				<div class="point-1" v-bind:class="{ 'point-active': step > 1 }"></div>
				<div class="label-1" v-bind:class="{ 'label-active': step > 1 }">1</div>
				<div class="text-1" v-bind:class="{ 'text-active': step === 2 }">Email Address</div>

				<div class="bar-1" v-bind:class="{ 'bar-active': step > 2 }"></div>

				<div class="point-2" v-bind:class="{ 'point-active': step > 2 }"></div>
				<div class="label-2" v-bind:class="{ 'label-active': step > 2 }">2</div>
				<div class="text-2" v-bind:class="{ 'text-active': step === 3 }">ETH Wallet Address</div>

				<div class="bar-2" v-bind:class="{ 'bar-active': step > 3 }"></div>

				<div class="point-3" v-bind:class="{ 'point-active': step > 3 }"></div>
				<div class="label-3" v-bind:class="{ 'label-active': step > 3 }">3</div>
				<div class="text-3" v-bind:class="{ 'text-active': step === 4 }">Storage Allocation</div>

				<div class="bar-3" v-bind:class="{ 'bar-active': step > 4 }"></div>

				<div class="point-4" v-bind:class="{ 'point-active': step > 4 }"></div>
				<div class="label-4" v-bind:class="{ 'label-active': step > 4 }">4</div>
				<div class="text-4" v-bind:class="{ 'text-active': step === 5 }">Storage Directory</div>

				<div class="bar-4" v-bind:class="{ 'bar-active': step > 5 }"></div>

				<div class="point-5" v-bind:class="{ 'point-active': step > 5 }"></div>
				<div class="label-5" v-bind:class="{ 'label-active': step > 5 }">5</div>
				<div class="text-5" v-bind:class="{ 'text-active': step === 6 }">Port Forwarding</div>

				<div class="bar-5" v-bind:class="{ 'bar-active': step > 6 }"></div>

				<div class="point-6" v-bind:class="{ 'point-active': step > 6 }"></div>
				<div class="label-6" v-bind:class="{ 'label-active': step > 6 }">6</div>
				<div class="text-6" v-bind:class="{ 'text-active': step === 7 }">Identity</div>
			</div>
		</div>
	</div>

	<script src="resources/js/vue.js"></script>
	<script src="resources/js/axios.min.js"></script>
	<script src="resources/js/wizard.js"></script>

</body>
