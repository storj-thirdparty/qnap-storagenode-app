var identitydataval, createAddressval, createWalletval, storageallocateval, directoryAllocationval, emailiddataval;

var identityVal, identityPath, addressVal, walletVal, storageVal, directoryVal, emailiddataVal;

var identityText, addressText, storageText;

function Stopprocess(url) {
    jQuery.ajax({
        type: "POST",
        url,
        data: {isstopAjax: 1},
        success: () => {
            window.location.reload();
        },
        error: () => {
        }
    });
}

function Startupdate() {
    jQuery.ajax({
        type: "POST",
        url: "startNode",
        data: {identity: identityPath, authKey: identityVal, address: addressVal, wallet: walletVal, storage: storageVal, emailval: emailiddataVal, directory: directoryVal, isajax: 1},
        success: (result) => {
             window.location.reload();
            // // log message
            $("iframe").contents().find("body").html("<p>" + result + "</p>");
        },
        error: (result) => {
            // log message
            $("iframe").contents().find("body").html("<p>" + result + "</p>");
        }
    });
}


jQuery(function () {
    var identitydata = jQuery("#identity_token").val();
    var identitypath = jQuery("#identity_path").val();
    var createAddress = jQuery("#host_address").val();
    var createWallet = jQuery("#wallet_address").val();
    var storageallocate = Number(jQuery("#storage_allocate").val());
    var emailiddata = jQuery("#email_address").val();
    var directoryAllocation = jQuery("#storage_directory").val();
    var regex = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
    var createVal = 0;
    var identityfile = $("#identityfile").text();
    var fileexists = $("#file_exists").text();

    function Identityfilecheck() {
        identityfile = $("#identityfile").text();
        if (identityfile === "false") {
            jQuery("#stopbtn").hide();
            jQuery("#startbtn").show();
            jQuery("#nodeonline").hide();
            jQuery("#nodeoffline").show();
            $(".editbtn").removeAttr("disabled");
            $(".editbtn").css("cursor", "pointer");
        }
    }

    function showstartbutton(createidentitydataval, createAddressvaldata, createWalletvaldata, storageallocatevaldata, emailAddressvaldata, directoryAllocationvaldata, ) {
        if (createidentitydataval === 1 && createAddressvaldata === 1 && createWalletvaldata === 1 && storageallocatevaldata === 1 && directoryAllocationvaldata === 1) {
            var identityfile = $("#identityfile").text();

            var identityfilecheck1 = new Identityfilecheck();
        } else {
            jQuery("#startbtn").hide();
            jQuery("#stopbtn").show();
            jQuery("#nodeoffline").hide();
            jQuery("#nodeonline").show();
            $(".editbtn").attr("disabled", "disabled");
            $(".editbtn").css("cursor", "not-allowed");
        }
    }

    // Create identity.
    function createidentifyToken(createidval, identitypath) {

        jQuery.ajax({
            type: "POST",
            url: "identity.php",
            data: {
                createidval,
                identitypath,
                identityString: createidval
            },
            success: (result) => {
                $("#identity_status").html("<b>Identity creation process is starting.</b><br><p>" + result + "</p>");
            },
            error: () => {
            }
        });

    }


// Read status from identity.php file.
    function readidentitystatus() {
        jQuery.ajax({
            type: "POST",
            url: "identity.php",
            data: {status: "status", },
            success: (result) => {
                var str1 = result;
                var str2 = "identity available";
                if (result === "identity available at /root/.local/share/storj/identity") {
                    $("#identity_status").html("<b>" + result + "</b>");
                    identitydataval = 1;
                } else {
                    $("#identity_status").html("<b>Identity creation process is running.</b><br><p>" + result + "</p>");
                }
            },
            error: () => {
            }
        });

        setInterval(function () {
            readidentitystatus();
        }, 5 * 60 * 1000);

    }



    jQuery("#stop_identity").click(function () {
        var stopprocess1 = new Stopprocess("identity.php");
    });

    $("#identity_path").change(function () {
        identityPath = $(this).val();
        if (identityPath === "" || identityPath === null) {
            identitydataval = 0;
            jQuery(".identity_path_msg").show();
            jQuery("#editidentitybtn").hide();
            jQuery("#identitybtn").show();
        } else {
            jQuery(".identity_path_msg").hide();
            jQuery("#editidentitybtn").hide();
            jQuery("#identitybtn").show();
            identitydataval = 1;
        }
        showstartbutton(identitydataval, createAddressval, createWalletval, storageallocateval, emailiddataval, directoryAllocationval);
    });

    function Identity() {
        if (identitypath === "") {
            jQuery(".identity_path_msg").show();
            jQuery("#editidentitybtn").hide();
            jQuery("#identitybtn").show();
            identitydataval = 0;
            identityText = "";
        } else {
            jQuery(".identity_path_msg").hide();
            jQuery("#identitybtn").hide();
            jQuery("#identity .close").trigger("click");
            jQuery("#editidentitybtn").show();
            if (identityfile === "false") {
                if (fileexists === "1") {
                    jQuery.ajax({
                        type: "POST",
                        url: "identity.php",
                        data: {fileexist: "file_exist"},
                        success: (result) => {
                            if (result === "1") {
                                $("#identity_status").html("The identity files don't exist at the path selected. Please create identity or copy the identity folder at the given path.");
                                createVal = 1;
                            } else {
                                $("#identity_status").html("Identity files exist.");
                                $("#create_identity").attr("disabled", true);
                                $("#create_identity").css("cursor", "not-allowed");
                                createVal = 0;
                            }
                        },
                        error: () => {

                        }
                    });

                } else {
                    $("#identity_status").html("Identity files exist.");
                    createVal = 0;
                }
            } else {
                readidentitystatus();

                
                $("#stop_identity").removeAttr("disabled");
                $("#stop_identity").css("cursor", "pointer");
            }

            identitydataval = 1;
            identityText = "<span class='identity_text'>Identity Generated: </span>";
        }

        jQuery("#idetityval").html("");
        identityVal = identitydata;
        identityPath = identitypath;
        jQuery("#idetityval").html(identityText + identitydata);
        showstartbutton(identitydataval, createAddressval, createWalletval, storageallocateval, emailiddataval, directoryAllocationval);

    }

    function Address() {
        if (createAddress === "") {
            jQuery(".host_token_msg").show();
            jQuery("#editexternalAddressbtn").hide();
            createAddressval = 0;
            addressText = "";
        } else {
            jQuery(".host_token_msg").hide();
            jQuery("#externalAddressbtn").hide();
            jQuery("#externalAddress .close").trigger("click");
            jQuery("#editexternalAddressbtn").show();
            createAddressval = 1;
            addressText = "<span class='address_text'></span>";
        }


        jQuery("#externalAddressval").html("");
        addressVal = createAddress;
        jQuery("#externalAddressval").html(addressText + createAddress);
        showstartbutton(identitydataval, createAddressval, createWalletval, storageallocateval, emailiddataval, directoryAllocationval);
    }


    function Wallet() {
        if (createWallet === "") {
            jQuery(".wallet_token_msg").show();
            jQuery("#editwallettbtn").hide();
            createWalletval = 0;
        } else {
            jQuery(".wallet_token_msg").hide();
            jQuery("#addwallettbtn").hide();
            jQuery("#walletAddress .close").trigger("click");
            jQuery("#editwallettbtn").show();
            createWalletval = 1;
        }


        jQuery("#wallettbtnval").html("");
        walletVal = createWallet;
        jQuery("#wallettbtnval").html(createWallet);
        showstartbutton(identitydataval, createAddressval, createWalletval, storageallocateval, emailiddataval, directoryAllocationval);
    }


    function Storage() {
        if (jQuery.isNumeric(storageallocate) && Number.isInteger(storageallocate) && storageallocate >= 500) {
            jQuery(".storage_token_msg").hide();
            jQuery("#addstoragebtn").hide();
            jQuery("#storageAllocation .close").trigger("click");
            jQuery("#editstoragebtn").show();
            storageallocateval = 1;
            storageText = "GB";
        } else if (storageallocate === "") {
            jQuery(".storage_token_msg").show();
            jQuery("#editstoragebtn").hide();
            storageallocateval = 0;
            storageText = "";
        } else {
            storageallocate = "";
            jQuery(".storage_token_msg").show();
            jQuery("#editstoragebtn").hide();
            storageallocateval = 0;
            storageText = "";
        }

        jQuery("#storagebtnval").html("");
        storageVal = storageallocate;
        jQuery("#storagebtnval").html(storageallocate + storageText);
        showstartbutton(identitydataval, createAddressval, createWalletval, storageallocateval, emailiddataval, directoryAllocationval);

    }


    function Email() {
        if (regex.test(emailiddata)) {
            jQuery(".email_token_msg").hide();
            jQuery("#emailAddressbtn").hide();
            jQuery("#emailAddress .close").trigger("click");
            jQuery("#editemailAddressbtn").show();
            emailiddataval = 1;
        } else {
            jQuery(".email_token_msg").show();
            jQuery("#editemailAddressbtn").hide();
            emailiddataval = 0;
        }
        jQuery("#emailAddressval").html("");
        emailiddataVal = emailiddata;
        jQuery("#emailAddressval").html(emailiddata);
        showstartbutton(identitydataval, createAddressval, createWalletval, storageallocateval, emailiddataval, directoryAllocationval);

    }

    function Directory() {
        if (directoryAllocation === "") {
            jQuery(".directory_token_msg").show();
            jQuery("#editdirectorybtn").hide();
            directoryAllocationval = 0;
        } else {
            jQuery(".directory_token_msg").hide();
            jQuery("#adddirectorybtn").hide();
            jQuery("#directory .close").trigger("click");
            jQuery("#editdirectorybtn").show();
            directoryAllocationval = 1;
        }


        jQuery("#directorybtnval").html("");
        directoryVal = directoryAllocation;
        jQuery("#directorybtnval").html(directoryAllocation);
        showstartbutton(identitydataval, createAddressval, createWalletval, storageallocateval, emailiddataval, directoryAllocationval);

    }
    var identity = new Identity();
    var address = new Address();
    var wallet = new Wallet();
    var storage = new Storage();
    var email = new Email();
    var directory = new Directory();


    if (identitydata === "" && createAddress === "" && createWallet === "" && storageallocate === "" && directoryAllocation === "" && emailiddata === "") {
        jQuery("#startbtn").hide();
        jQuery("#stopbtn").show();
        jQuery("#nodeoffline").hide();
        jQuery("#nodeonline").show();
        $(".editbtn").attr("disabled", "disabled");
        $(".editbtn").css("cursor", "not-allowed");
    } else {
        var identityfilecheck = new Identityfilecheck();
    }

    jQuery.ajax({
        type: "POST",
        url: "isstartajax",
        data: {isstartajax: 1},
        success: (resposnse) => {
            if (resposnse) {
                // log message
                $("iframe").contents().find("body").html("<p>" + resposnse + "</p>");
            }
        },
        error: (resposnse) => {
            // log message
            $("iframe").contents().find("body").html("<p>" + resposnse + "</p>");
        }
    });

    jQuery("#create_identity").click(function () {
        identitydata = jQuery("#identity_token").val();
        identitypath = jQuery("#identity_path").val();
        identityfile = $("#identityfile").text();
        fileexists = $("#file_exists").text();
        var identity1 = new Identity();

        if (createVal === 1) {
            createidentifyToken(identitydata, identitypath);
            readidentitystatus();
        }

    });


    jQuery("#create_address").click(function () {
        createAddress = jQuery("#host_address").val();
        var address1 = new Address();
    });

    jQuery("#create_address").click();

    jQuery("#create_wallet").click(function () {
        createWallet = jQuery("#wallet_address").val();
        var wallet1 = new Wallet();
    });

    jQuery("#allocate_storage").click(function () {
        storageallocate = Number(jQuery("#storage_allocate").val());
        var storage1 = new Storage();
    });


    jQuery("#create_emailaddress").click(function () {
        emailiddata = jQuery("#email_address").val();
        var email1 = new Email();

    });

    jQuery("#create_directory").click(function () {
        directoryAllocation = jQuery("#storage_directory").val();
        var directory1 = new Directory();
    });

    jQuery("#editidentitybtn button").click(function () {
        jQuery("#storjrows").hide();
    });
});




jQuery("#startbtn").click(function () {
    var startupdate = new Startupdate();
});

jQuery("#stopbtn").click(function () {
    var stopprocess = new Stopprocess("stopNode");
});

jQuery("#updatebtn").click(function () {
    jQuery.ajax({
        type: "POST",
        url: "updateNode",
        data: {identity: identityPath, authKey: identityVal, address: addressVal, wallet: walletVal, storage: storageVal, emailval: emailiddataVal, directory: directoryVal,isUpdateAjax: 1},
        success: () => {
            window.location.reload();
        },
        error: () => {
        }
    });
});


if (jQuery("#identity_token").val() === null || jQuery("#host_address").val() === "" || jQuery("#host_address").val() === null || jQuery("#wallet_address").val() === "" || jQuery("#wallet_address").val() === null || Number(jQuery("#storage_allocate").val()) === "" || Number(jQuery("#storage_allocate").val()) === null || jQuery("#email_address").val() === null || jQuery("#storage_directory").val() === "" || jQuery("#storage_directory").val() === null) {
    $("iframe").contents().find("body").html("<p></p>");
} else {
    jQuery.ajax({
        type: "POST",
        url: "checkRunningnode",
        data: {isrun: 1},
        success: (resposnse) => {
            if (resposnse) {
                // log message
                if (resposnse == 1) {
                    $(".editbtn").attr("disabled", true).css("cursor", "not-allowed");
                    $("#startbtn").hide();
                    $("#stopbtn").show();
                    $("#nodeoffline").hide();
                    $("#nodeonline").show();
                    $(".editbtn").attr("disabled", "disabled");
                    $(".editbtn").css("cursor", "not-allowed");
                } else if (resposnse == 0) {
                    $(".editbtn").attr("disabled", false).css("cursor", "pointer");
                    $("#stopbtn").hide();
                    $("#startbtn").show();
                    $("#nodeonline").hide();
                    $("#nodeoffline").show();
                    $(".editbtn").removeAttr("disabled");
                    $(".editbtn").css("cursor", "pointer");
                }

            }
        },
        error: (resposnse) => {
            // log message
            $("iframe").contents().find("body").html("<p>" + resposnse + "</p>");
        }
    });
}
$('#myonoffswitch').change(function () {
    var mode = $(this).prop('checked');
    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'setauthswitch',
        data: 'mode=' + mode,
        success: function (data)
        {
            var data = eval(data);
        }
    });
});
let debug = false;

const getFolders = debug
        ? async path => {
            if (path === '/') {
                return [
                    'test/',
                    'a/',
                    'b/',
                    'c/'
                ]
            }

            if (path === '/a/') {
                return [
                    'photos/',
                    'documents/'
                ]
            }

            if (path === '/a/photos/') {
                return [
                    'holiday/',
                    'mountains/'
                ]
            }

            return new Promise(resolve => {
            });
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
				><img :src="'img/wizard/folder.svg'" alt="Folder">{{file}}</li>
			</ul>

			<button class='file-browser-done' v-on:click="done">Select this directory</button>
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
                    if (!(el == event.target || el.contains(event.target) || event.target.className == "browse input-group-prepend" || event.target.className == "browse-svg" || event.target.className == "browse")) {
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
            if (this.loading === false) {
                if (this.path != "/") {
                    this.selectedPath = this.path + "/" + file;
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
        setpath(file) {
            if (this.path != "/") {
                this.path += '/' + file;
            } else {
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
        directoryBrowse: false
    },
    methods: {
        setIdentityTokenDirectory(selected) {
            if (selected != "outside") {
                $('#identity_path').val('');
                $('#identity_path').val(selected);
                this.directoryBrowse = false;
            } else {
                this.directoryBrowse = false;
            }

        },
    }
});

const app2 = new Vue({
    el: "#app2",
    data: {
        directoryBrowse: false
    },

    methods: {
        setStorageDirectory(selected) {
            if (selected != "outside") {
                $('#storage_directory').val('');
                $('#storage_directory').val(selected);
                this.directoryBrowse = false;
            } else {
                this.directoryBrowse = false;
            }

        },
    }
});
