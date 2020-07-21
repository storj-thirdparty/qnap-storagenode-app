<!DOCTYPE html>
<head>
    <link href="resources/css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="resources/css/login.css" rel="stylesheet">
</head>
<body>
    <div id="app">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-4 offset-lg-4 mt-5 text-center">
                  <form v-on:submit.prevent="authenticate">
                      <img class="head" src="resources/img/wizard/step-1-head.png">
                      <h1 class="title mb-4">Login</h1>
                      <p class="description text-muted mb-4">Please enter your username and password</p>
                      <span class="error-msg invalid-feedback" id="wrongdetails">Invalid Username or Password</span>

                      <div class="form-group text-left">
                        <input type="text" class="form-control" name="username" id="username" placeholder="Username" required="" v-model="username" v-bind:class="{ invalid: !usernameValid }">
                        <!-- <span class="error-msg" v-if="!usernameValid">Please enter Username</span> -->
                      </div>

                      <div class="form-group text-left">
                        <input type="password"  class="form-control" name="password" id="password" placeholder="Password" required="" v-model="password" v-bind:class="{ invalid: !passwordValid }">
                        <!-- <span class="error-msg" v-if="!passwordValid">Please enter Password</span> -->
                      </div>

                      <button class="btn btn-primary btn-block mt-2" name="login" id="login" v-bind:disabled="!isComplete">Log In</button>
<!--                      <input type="button" name="login" id="login" v-on:click="authenticate" v-bind:disabled="!isComplete" value="Login">-->
                      <div class="loader" v-if="loading"></div>
                  </form>
              </div>
            </div>
        </div>
    </div>
    <script src="resources/js/vue.js"></script>
    <script src="resources/js/axios.min.js"></script>
    <script src="resources/js/login.js"></script>
</body>
