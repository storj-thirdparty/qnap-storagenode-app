@extends('layouts.master')
@push('styles')
<link href="{{ url('css/wizard.css') }}" rel="stylesheet">
@endpush
@section('content')
<div class="wizard">
    <div id="app">
        <div class="wizard">

            <div v-bind:class="stepClass">

                <a href="config.php" v-if="step > 1"><i class="fa fa-home homeicon"></i></a>
                <img class="back" src="{{ url('img/icon-wizard-back.svg') }}" alt="Back" v-if="step > 1 && (step !== 7 || identityStep === 1)" v-on:click="step--">
                <img class="back" src="{{ url('img/icon-wizard-back.svg') }}" alt="Back" v-else-if="identityStep > 1" v-on:click="identityStep--">

                <div class="container-lg">

                    <div class="row justify-content-center text-center">
                        <div class="col-sm-10 col-md-8 col-lg-6">

                            <div v-if="step === 1" class="mt-5">
                                <img src="{{ url('img/icon-node.svg') }}" class="head" alt="Storj - Storage Node">
                                <h1 class="title">Storage Node Setup</h1>
                                <p>Monetize your excess capacity on the Storj Network</p>
                                <button class="btn btn-primary mt-3" v-on:click="step++">Get Started</button>
                            </div>

                            <div v-if="step === 2">
                                <div class="head"><img src="{{ url('img/icon-email.svg') }}" alt="Email Address"/></div>
                                <h1 class="title">Connect your Email Address</h1>

                                <p class="tagline">Join thousands of Node Operators around the world by getting  Node status updates from Storj Labs.</p>

                                <div class="form-group text-left mt-4 mb-4">
                                    <label for="emailAddress">Email Address</label>
                                    <span class="email-error error-msg" v-if="!emailValid">Please enter a valid email address</span>
                                    <input type="email" id="emailAddress" class="email form-control" placeholder="mail@default.com" v-model="email" v-bind:class="{ invalid: !emailValid }" value="<?php if (isset($prop['Email'])) echo $prop['Email'] ?>" required>
                                </div>

                                <button class="btn btn-outline-primary skip" v-on:click="step++">Skip this step</button>
                                <button class="btn btn-primary continue" v-on:click="step++" v-bind:disabled="!emailValid">Continue</button>
                            </div>

                            <div v-if="step === 3">
                                <div class="head"><img src="{{ url('img/icon-wallet.svg') }}" alt="Wallet Address"/></div>
                                <h1 class="title">Connect your Ethereum Wallet Address</h1>
                                <p class="tagline">In order to recieve and hold your STORJ token payouts, you need an <a href="https://support.storj.io/hc/en-us/articles/360026611692-How-do-I-hold-STORJ-What-is-a-valid-address-or-compatible-wallet" target="_blank">ERC-20 compatible wallet address</a></p>

                                <div class="form-group text-left mt-4 mb-4">
                                    <label for="walletAddress">Wallet Address</label>
                                    <span class="error-msg address-error" v-if="!addressValid">Please enter a valid ERC-20 address</span>
                                    <input type="text" id="walletAddress" class="address form-control" placeholder="Enter ERC-20 Token Compatible Wallet Address" v-model="address" v-bind:class="{ invalid: !addressValid }" value="<?php if (isset($prop['Wallet'])) echo $prop['Wallet'] ?>" required>
                                </div>

                                <button class="btn btn-primary continue" v-on:click="step++" v-bind:disabled="!addressValid">Continue</button>
                            </div>

                            <div v-if="step === 4">
                                <div class="head"><img src="{{ url('img/icon-storage.svg') }}" alt="Storage Allocation" /></div>
                                <h1 class="title">Set Your Storage Allocation</h1>

                                <p class="tagline">How much disk space do you want to allocate to the Storj Network?</p>

                                <div class="form-group text-left mt-4 mb-4 mw-300">
                                    <label for="storageAllocation">Storage Allocation</label>
                                    <div class="input-group">
                                        <span class="error-msg storage-error" v-if="!storageValid">Invalid Entry</span>
                                        <input class="storage form-control" id="storageAllocation" type="number" min="1" max="100000"  v-model="storage" v-bind:class="{ invalid: !storageValid }" value="<?php if (isset($prop['Allocation'])) {
        if ($prop['Allocation'] != "") {
            echo $prop['Allocation'];
        } else {
            echo "10000";
        }
    } else {
        echo "10000";
    } ?>" aria-describedby="unitGB" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text unit" id="unitGB">GB</span>
                                        </div>
                                    </div>
                                </div>

                                <button class="btn btn-primary" v-on:click="step++" v-bind:disabled="!storageValid">Continue</button>
                            </div>

                            <div v-if="step === 5">
                                <div class="head"><img src="{{ url('img/icon-directory.svg') }}" alt="Storage Directory" /></div>
                                <h1 class="title">Set Storage Directory</h1>

                                <p class="tagline">The local directory where you want files to be stored on your hard drive for the network</p>

                                <div class="form-group text-left mt-4 mb-4">
                                    <label for="storageDirectory">Storage Directory</label>
                                    <div class="input-group">
                                        <input class="directory form-control" id="storageDirectory" type="text" placeholder="/path/to/folder_to_share" v-model="directory" v-bind:class="{ invalid: !directoryValid }" value="<?php if (isset($prop['Directory'])) echo $prop['Directory'] ?>" required>
                                        <div class="input-group-prepend">
                                            <button class="browse" v-on:click="directoryBrowse = true"><img src="{{ url('img/wizard/folder.svg') }}" class="browse-svg" alt="Browse Folder"/>Browse</button>
                                        </div>
                                    </div>
                                </div>

                                <file-browser v-if="directoryBrowse" v-on:selected="setDirectory"></file-browser>
                                <button class="btn btn-primary" v-on:click="step++" v-bind:disabled="!directoryValid">Continue</button>
                            </div>

                            <div v-if="step === 6">
                                <div class="head"><img src="{{ url('img/icon-port.svg') }}" alt="External Port Forwarding" /></div>
                                <h1 class="title">Configure Your External Port Forwarding</h1>

                                <p class="tagline">How a storage node communicates with others on the Storj Network, even though it is behind a router. Learn how to configure your DNS and port forwarding with our <a href="https://documentation.storj.io/dependencies/port-forwarding" target="_blank">documentation.</a> </p>

                                <div class="form-group text-left mt-4 mb-4">
                                    <label for="hostAddress">Host Address</label>
                                    <span class="error-msg host-error" v-if="!hostValid">Please enter a valid address</span>
                                    <input class="host form-control" id="hostAddress" type="text" placeholder="hostname.ddns.net:28967" v-model="host" v-bind:class="{ invalid: !hostValid }" value="<?php if (isset($prop['Port'])) echo $prop['Port'] ?>" required>
                                </div>

                                <button class="btn btn-primary" v-on:click="step++" v-bind:disabled="!hostValid">Continue</button>
                            </div>

                            <div v-if="step === 7">
                                <div class="identity-step-1" v-if="identityStep === 1">
                                    <div class="head"><img src="{{ url('img/icon-identity.svg') }}" alt="Identity Path" /></div>
                                    <h1 class="title">Setup Your Identity Path</h1>

                                    <p class="tagline">Every Node is required to have an identity on the Storj Network. If you’ve already generated and signed your identity for your QNAP Node, enter the path below and click Finish. If you do not have an identity you’ll need to get an <a href="https://storj.io/sign-up-node-operator/" target="_blank">authorization token</a>.</p>

                                    <div class="form-group text-left mt-4 mb-4">
                                        <label for="identityPath">Identity Path</label>
                                        <div class="input-group">
                                            <input class="identity form-control" id="identityPath" type="text" placeholder="/path/to/identity" v-model="identity" value="<?php if (isset($prop['Identity'])) echo $prop['Identity'] ?>" v-bind:class="{ invalid: !identityValid }">
                                            <div class="input-group-prepend">
                                                <button class="browse" v-on:click="directoryBrowse = true"><img src="{{ url('img/wizard/folder.svg') }}" class="browse-svg" alt="Folder Browse"/>Browse</button>
                                            </div>
                                        </div>
                                    </div>

                                    <file-browser v-if="directoryBrowse" v-on:selected="setIdentityDirectory"></file-browser>

                                    <button class="btn btn-outline-primary" v-on:click="processCheck">I don't have an identity</button>
                                    <button class="btn btn-primary" v-on:click="finish" v-bind:disabled="!identityValid">Finish</button>
                                </div>

                                <div class="identity-step-2" v-if="identityStep === 2">
                                    <div class="head"><img src="{{ url('img/icon-identity.svg') }}" alt="Identity"/></div>
                                    <h1 class="title">Enter Authorization Token</h1>

                                    <div class="form-group text-left mt-4 mb-4">
                                        <label for="authorizationToken">Authorization Token</label>
                                        <span class="error-msg authkey-error" v-if="!authkeyValid">Please enter a valid authorization token</span>
                                        <input type="text" class="form-control" id="authToken" placeholder="your@email.com:1BTJeyYWAquvfQWscG9VndHjyYk8PSzQvrJ5DC" id="authkey"  value="<?php if (isset($prop['AuthKey'])) echo $prop['AuthKey'] ?>"  v-model="authkey" v-bind:class="{ invalid: !authkeyValid }" required>
                                    </div>

                                    <button class="btn btn-primary" v-on:click="generateIdentity" v-bind:disabled="!authkeyValid">Generate</button>
                                </div>

                                <div class="identity-step-3" v-if="identityStep === 3">
                                    <div class="head"><img src="{{ url('img/icon-identity.svg') }}" alt="Identity"/></div>
                                    <h1 class="title">Identity Generation Started</h1>

                                    <p class="tagline">Creating identity can take several hours or even days, depending on your machines processing power & probability. You will be able to track your progress after configuring the rest</p>



                                    <button class="btn btn-primary" v-on:click="finish">Finish</button>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>

        <footer>
            <div class="wizard-progress" v-if="step > 1">
                <div class="wizard-progress-track"></div>

                <div class="wizard-progress-step" :class="{ 'is-active': step === 2, 'is-complete': step > 2 }">
                    <p class="wizard-progress-label" :class="{ 'label-active': step === 2 }">Email Address</p>
                </div>

                <div class="wizard-progress-step" :class="{ 'is-active': step === 3, 'is-complete': step > 3 }">
                    <p class="wizard-progress-label" :class="{ 'label-active': step === 3 }">Wallet Address</p>
                </div>

                <div class="wizard-progress-step" :class="{ 'is-active': step === 4, 'is-complete': step > 4 }">
                    <p class="wizard-progress-label" :class="{ 'label-active': step === 4 }">Storage Allocation</p>
                </div>

                <div class="wizard-progress-step" :class="{ 'is-active': step === 5, 'is-complete': step > 5 }">
                    <p class="wizard-progress-label" :class="{ 'label-active': step === 5 }">Storage Directory</p>
                </div>

                <div class="wizard-progress-step" :class="{ 'is-active': step === 6, 'is-complete': step > 6 }">
                    <p class="wizard-progress-label" :class="{ 'label-active': step === 6 }">Port Forwarding</p>
                </div>

                <div class="wizard-progress-step" :class="{ 'is-active': step === 7 }">
                    <p class="wizard-progress-label" :class="{ 'label-active': step === 7 }">Identity</p>
                </div>
            </div>
        </footer>

    </div>
</div>
@push('scripts')
<script type="text/javascript" src="{{ url('js/wizard.js') }}"></script>
@endpush

@stop
