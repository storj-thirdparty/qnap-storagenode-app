@extends('layouts.master')
@section('class', 'config')
@section('content')
<nav class="navbar navbar-expand-sm">
    <div class="container">
        <router-link to="/" class="navbar-brand">
            <img src="{{ url('img/logo.svg') }}" class="logo">
            <p class="logo-text">Storage Node</p>
        </router-link>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
            <ul class="navbar-nav ">
                <li class="nav-item">
                    <a href="{{ $dashboardurl }}" target="_blank"class="nav-link"><img src="{{ url('img/icon-dashboard.svg') }}" class="nav-icon" alt="Dashboard">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a href="{{url('wizard')}}" class="nav-link"><img src="{{ url('img/icon-setup.svg') }}" class="nav-icon" alt="Setup">Setup</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<?php
$identityFile = base_path('public/identity.pid');

if (file_exists($identityFile)) {
    $pid = file_get_contents($identityFile);
    $pid = (int) $pid;
    // Figure out whether process is running
    $status = exec("if [ -d '/proc/$pid' ] ; then (echo 1 ; exit 0) ; else (echo 0 ; exit 2 ) ; fi");
    if ($status == 1) {
        // if process is running then print true.
        echo "<span id='identityfile' style='display:none;'>true</span>";
    } else {
        // if process is not running then print false.
        echo "<span id='identityfile' style='display:none;'>false</span>";
    }
} else {
    echo "<span id='identityfile' style='display:none;'>false</span>";
}
// Identity Path
$rootBase = $prop['Identity'];

$file1 = "${rootBase}/storagenode/ca.cert";
$file2 = "${rootBase}/storagenode/ca.key";
$file3 = "${rootBase}/storagenode/identity.cert";
$file4 = "${rootBase}/storagenode/identity.key";
$numFiles = `ls ${rootBase}/storagenode | wc -l `;
if (($numFiles == 6) && file_exists($file1) && file_exists($file2) && file_exists($file3) && file_exists($file4)) {
    echo "<span id='file_exists' style='display:none;'>0</span>";
} else {
    echo "<span id='file_exists' style='display:none;'>1</span>";
}
?>
<div class="container">

    <div class="row mb-4">
        <div class="col">
            <h3 class="font-light">Dashboard</h3>
        </div>
        <div class="col text-right">
            <a href="https://documentation.storj.io/" target="_blank" class="documentation-link">Documentation</a>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 col-lg-6">
            <div class="card">
                <div class="row">
                    <div class="col">
                        <p class="card-title">Node Status</p>
                        <p class="node-status">
                            <span class="offline" id="nodeoffline">Offline</span>
                            <span class="online" id="nodeonline">Online</span>
                        </p>
                    </div>
                    <div class="col">
                        <button type="button" id="startbtn" class="btn btn-primary btn-block">Start</button>
                        <button type="button" id="stopbtn" class="btn btn-primary btn-block">Stop</button>
                        <img src='{{ url('img/spinner.gif') }}' class="start_stop_spinner" style="display: none;">
                    </div>
                </div>

            </div>
        </div>
        <div class="col-sm-12 col-lg-6">
            <div class="card">
                <div class="row">
                    <div class="col">
                        <?php if ($storjnodeversion != "") { ?>
                            <p class="card-title"><span id="version"><?php echo " $storjnodeversion" ?> </span></p>
                        <?php } else { ?>
                            <p class="card-title">Please Start the node to get the version</p>
                        <?php } ?>
<!--              <p class="text-muted">Latest version installed</p>-->
                    </div>
                    <div class="col">
                        <button type="button" class="btn btn-primary btn-block" id="updatebtn">Update Node</button>
                        <img src='{{ url('img/spinner.gif') }}' class="update_spinner" style="display: none;"> 
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card">

                <a class="dropdown-toggle logs-toggle" data-toggle="collapse" href="#collapseLogs" role="button" aria-expanded="false" aria-controls="collapseLogs">
                    Latest Log<span class="expand-caret caret"></span>
                </a>

                <div class="collapse" id="collapseLogs">
                    <div class="card-body logs">
                        <!-- log message -->
                        <iframe>
                            <p id="msg"></p>
                        </iframe>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 col-lg-6">
            <div class="card">
                <div class="row">
                    <div class="col-3 col-sm-2 col-lg-3 text-center">
                        <img src="{{ url('img/icon-identity.svg') }}" class="card-img img-fluid" alt="Identity">
                    </div>
                    <div class="col-9 col-sm-10 col-lg-9">
                        <p class="card-title mb-2">Identity <img src="{{ url('img/icon-tooltip.svg') }}" class="tooltip-icon" alt="Tooltip" data-toggle="tooltip" data-placement="top" title="Every node is required to have a unique identifier on the network. If you haven't already, get an authorization token. Please get the authorization token and create identity on host machine other than NAS."></p>
                        <p class="text-muted mb-3" id="identity_status"></p>
                        <button class="btn btn-light editbtn" data-toggle="modal" data-target="#identity">Edit</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-lg-6">
            <div class="card">
                <div class="row">
                    <div class="col-3 col-sm-2 col-lg-3 text-center">
                        <img src="{{ url('img/icon-port.svg') }}" class="card-img img-fluid" alt="Port Forwarding">
                    </div>
                    <div class="col-9 col-sm-10 col-lg-9">
                        <p class="card-title mb-2">Port Forwarding <img src="{{ url('img/icon-tooltip.svg') }}" class="tooltip-icon" alt="Tooltip" data-toggle="tooltip" data-placement="top" title="How a storage node communicates with others on the Storj network, even though it is behind a router. You need a dynamic DNS service to ensure your storage node is connected."></p>
                        <p class="text-muted mb-3" id="externalAddressval"></p>
                        <button class="btn btn-light editbtn" data-toggle="modal" data-target="#externalAddress">Edit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 col-lg-6">
            <div class="card">
                <div class="row">
                    <div class="col-3 col-sm-2 col-lg-3 text-center">
                        <img src="{{ url('img/icon-wallet.svg') }}" class="card-img img-fluid" alt="Wallet Address">
                    </div>
                    <div class="col-9 col-sm-10 col-lg-9">
                        <p class="card-title mb-2">Wallet Address <img src="{{ url('img/icon-tooltip.svg') }}" class="tooltip-icon" alt="Tooltip" data-toggle="tooltip" data-placement="top" title="In order to recieve and hold your STORJ token payouts, you need an ERC-20 compatible wallet address."></p>
                        <p class="text-muted mb-3 truncate" id="wallettbtnval"></p>
                        <button class="btn btn-light editbtn" data-toggle="modal" data-target="#walletAddress">Edit</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-lg-6">
            <div class="card">
                <div class="row">
                    <div class="col-3 col-sm-2 col-lg-3 text-center">
                        <img src="{{ url('img/icon-storage.svg') }}" class="card-img img-fluid" alt="Storage Allocation">
                    </div>
                    <div class="col-9 col-sm-10 col-lg-9">
                        <p class="card-title mb-2">Storage Allocation <img src="{{ url('img/icon-tooltip.svg') }}" class="tooltip-icon" alt="Tooltip" data-toggle="tooltip" data-placement="top" title="How much disk space you want to allocate to the Storj network"></p>
                        <p class="text-muted mb-3" id="storagebtnval"></p>
                        <button class="btn btn-light editbtn" data-toggle="modal" data-target="#storageAllocation">Edit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 col-lg-6">
            <div class="card">
                <div class="row">
                    <div class="col-3 col-sm-2 col-lg-3 text-center">
                        <img src="{{ url('img/icon-directory.svg') }}" class="card-img img-fluid" alt="Storage Directory">
                    </div>
                    <div class="col-9 col-sm-10 col-lg-9">
                        <p class="card-title mb-2">Storage Directory <img src="{{ url('img/icon-tooltip.svg') }}" class="tooltip-icon" alt="Tooltip" data-toggle="tooltip" data-placement="top" title="The local directory where you want files to be stored on your hard drive for the network"></p>
                        <p class="text-muted mb-3" id="directorybtnval"></p>
                        <button class="btn btn-light editbtn" data-toggle="modal" data-target="#directory" data-toggle="modal" data-target="#directory">Edit</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-lg-6">
            <div class="card">
                <div class="row">
                    <div class="col-3 col-sm-2 col-lg-3 text-center">
                        <img src="{{ url('img/icon-email.svg') }}" class="card-img img-fluid" alt="Email Address">
                    </div>
                    <div class="col-9 col-sm-10 col-lg-9">
                        <p class="card-title mb-2">Email Address <img src="{{ url('img/icon-tooltip.svg') }}" class="tooltip-icon" alt="Tooltip" data-toggle="tooltip" data-placement="top" title="Email address so that you can recieve notification you when a new version is released."></p>
                        <p class="text-muted mb-3" id="emailAddressval"></p>
                        <button class="btn btn-light editbtn" data-toggle="modal" data-target="#emailAddress">Edit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-lg-6">
            <div class="card">
                <div class="row">
                    <div class="col-3 col-sm-2 col-lg-3 text-center">
                        <img src="{{ url('img/icon-security.svg') }}" class="card-img img-fluid" alt="Security">
                    </div>
                    <div class="col-9 col-sm-10 col-lg-9">
                        <p class="card-title mb-2">Security <img src="{{ url('img/icon-tooltip.svg') }}" class="tooltip-icon" alt="Tooltip" data-toggle="tooltip" data-placement="top" title="Enable or disable login functionality to access the application"></p>
                        <p class="text-muted mb-3"></p>
                        <div class="onoffswitch">
                            <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch" tabindex="0" <?php echo ($loginMode['mode'] == "true" ? 'checked' : ''); ?>>
                            <label class="onoffswitch-label" for="myonoffswitch">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="identity" tabindex="-1" role="dialog" aria-labelledby="identity" aria-hidden="true">
        <div id="app">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Identity</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted mb-4">Every Node is required to have an identity on the Storj Network. If you’ve already generated and signed your identity for your QNAP Node, enter the path below and click Finish. If you do not have an identity you’ll need to get an <a href="https://storj.io/sign-up-node-operator/" target="_blank">authorization token</a>.</p>
                        <p class="modal-input-title mb-2">Authorization Token</p>
                        <input class="modal-input form-control mb-4" type="text" id="identity_token" name="identity_token" placeholder="your@email.com: 1BTJeyYWAquvfQWscG9VndHjyYk8PSzQvrJ5DC" value="<?php if (isset($prop['AuthKey'])) echo $prop['AuthKey'] ?>"/>
                        <p class="modal-input-title mb-2">Identity Path</p>
                        <div class="input-group">
                            <input class="modal-input form-control directory" type="text" id="identity_path" name="identity_path" placeholder="/path/to/identity" value="<?php if (isset($prop['Identity'])) echo $prop['Identity'] ?>"/>
                            <div class="input-group-prepend">
                                <button class="browse" v-on:click="directoryBrowse = true"><img src="{{ url('img/wizard/folder.svg') }}" class="browse-svg"/>Browse</button>
                            </div>
                        </div>
                        <p class="identity_path_msg msg small text-danger mt-2" style="display:none;">This is a required field.</p>
                        <file-browser v-if="directoryBrowse" v-on:selected="setIdentityTokenDirectory"></file-browser>
                        <p class="identity_note text-muted small mt-4">Note: Creating identity can take several hours or even days, depending on your machines processing power & luck.<strong>If you change the Identity path then please also clean the storage folder or change the storage path.</strong></p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-light" data-dismiss="modal">Close</button>
                        <!--  Replace Set Identity Path to Create Identity -->
                        <button class="btn btn-primary" id="create_identity"> Create Identity</button>
                        <button class="btn btn-primary" id="stop_identity" disabled style="cursor: not-allowed;"> Stop Identity</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="externalAddress" tabindex="-1" role="dialog" aria-labelledby="externalAddress" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Configure Your External Port Forwarding</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-4">How a storage node communicates with others on the Storj Network, even though it is behind a router. Learn how to configure your DNS and port forwarding with our <a href="https://documentation.storj.io/dependencies/port-forwarding" target="_blank">documentation.</a> </p>
                    <label for="host_address">Host Address</label>
                    <input class="modal-input form-control" id="host_address" name="host_address" type="text" class="quantity" placeholder="hostname.ddns.net:28967" value="<?php if (isset($prop['Port'])) echo $prop['Port'] ?>"/>
                    <p class="host_token_msg msg small text-danger mt-2" style="display:none;">Invalid host address.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" id="create_address">Set External Address</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="walletAddress" tabindex="-1" role="dialog" aria-labelledby="walletAddress" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ethereum Wallet Address</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-4">In order to recieve and hold your STORJ token payouts, you need an <a href="https://support.storj.io/hc/en-us/articles/360026611692-How-do-I-hold-STORJ-What-is-a-valid-address-or-compatible-wallet" target="_blank">ERC-20 compatible wallet address.</a></p>
                    <label for="wallet_address">Wallet Address</label>
                    <input class="modal-input form-control" name="Wallet Address" id="wallet_address" placeholder="Enter ERC-20 Token Compatible Wallet Address" value="<?php if (isset($prop['Wallet'])) echo $prop['Wallet'] ?>"/>
                    <p class="wallet_token_msg msg small text-danger mt-2" style="display:none;">This is a required field.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" id="create_wallet">Set Wallet Address</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="storageAllocation" tabindex="-1" role="dialog" aria-labelledby="storageAllocation" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Storage Allocation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-4">How much disk space do you want to allocate to the Storj Network?</p>
                    <label for="storage_allocate">Storage Allocation</label>

                    <div class="input-group">
                        <input class="modal-input shorter storage form-control" id="storage_allocate" name="storage_allocate" type="number" step="1" min="1" class="quantity" value="<?php if (isset($prop['Allocation'])) echo $prop['Allocation'] ?>"/>
                        <div class="input-group-append">
                            <span class="modal-input-metric input-group-text unit">GB</span>
                        </div>
                    </div>
                    <p class="storage_token_msg msg small text-danger mt-2" style="display:none;">Minimum 500 GB is required.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" id="allocate_storage">Set Storage Capacity</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="emailAddress" tabindex="-1" role="dialog" aria-labelledby="email_address" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Connect Your Email Address</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-4">Join thousands of Node Operators around the world by getting Node status updates from Storj Labs.</p>
                    <label for="email_address">Email Address</label>
                    <input class="modal-input form-control" id="email_address" name="email_address" type="email" placeholder="mail@default.com" value="<?php if (isset($prop['Email'])) echo $prop['Email'] ?>"/>
                    <p class="email_token_msg msg small text-danger mt-2" style="display:none;">Enter a valid Email address.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" id="create_emailaddress">Set Email Address</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="directory" tabindex="-1" role="dialog" aria-labelledby="directory" aria-hidden="true">
        <div id="app2">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="identity">Storage Directory</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted mb-4">The local directory where you want files to be stored on your hard drive for the network.</p>
                        <label for="storage_directory">Storage Directory</label>
                        <div class="input-group">
                            <input class="modal-input form-control directory" id="storage_directory" name="storage_directory" placeholder="/path/to/folder_to_share" value="<?php if (isset($prop['Directory'])) echo $prop['Directory'] ?>"  />
                            <div class="input-group-prepend">
                                <button class="browse" v-on:click="directoryBrowse = true"><img src="{{ url('img/wizard/folder.svg') }}" class="browse-svg"/>Browse</button>
                            </div>
                        </div>
                        <file-browser v-if="directoryBrowse" v-on:selected="setStorageDirectory"></file-browser>
                        <p class="directory_token_msg msg small text-danger mt-2" style="display:none;">This is a required field.</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-light" data-dismiss="modal">Close</button>
                        <button class="btn btn-primary" id="create_directory">Set Directory</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Config</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Identity creating at <b>/root/.local/share/storj/identity/storagenode</b></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</div>
@push('scripts')
<script type="text/javascript" src="{{ url('js/config.js') }}"></script>
<script>
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})
</script>
@endpush

@stop
