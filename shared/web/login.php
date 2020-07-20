<head>
    <link href="resources/css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="resources/css/login.css" rel="stylesheet">
</head> 
<body>
    <div id="app">
        
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <form class="box">
                            <div class="head"><img src="resources/img/wizard/step-1-head.png"></div>
                            <h1 class="title">Login</h1>
                            <p class="text-muted">Please enter your login and password!</p> 
                            <span class="error-msg" id="wrongdetails">Invalid Username or Password</span>
                            <input type="text" name="username" id="username" placeholder="Username" required="" v-model="username" v-bind:class="{ invalid: !usernameValid }"> 
                            <span class="error-msg" v-if="!usernameValid">Please enter Username</span>
                            <input type="password" name="password" id="password" placeholder="Password" required="" v-model="password" v-bind:class="{ invalid: !passwordValid }">
                            <span class="error-msg" v-if="!passwordValid">Please enter Password</span>
                            <input type="button" name="login" id="login" v-on:click="authenticate" v-bind:disabled="!isComplete" value="Login">
                            <div class="loader" v-if="loading"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="resources/js/vue.js"></script>
    <script src="resources/js/axios.min.js"></script>
    <script src="resources/js/login.js"></script>
</body>