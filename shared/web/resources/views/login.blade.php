@extends('layouts.master')
@section('content')
<div id="app">
    <div class="container">
        <div class="row">
            <div class="col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-4 offset-lg-4 mt-5 text-center">
                <form v-on:submit.prevent="authenticate">
                    <div class="head mb-4">
                        <img src="{{ url('img/icon-node.svg') }}" alt="Storj - Storage Node">
                    </div>
                    <h1 class="title mb-4">Log In</h1>
                    <p class="description text-muted mb-4">Please enter your QNAP username and password</p>
                    <span class="error-msg invalid-feedback" id="wrongdetails">Invalid Username or Password</span>

                    <div class="form-group text-left">
                        <input type="text" class="form-control" name="username" id="username" placeholder="Username" required="" v-model="username" v-bind:class="{ invalid: !usernameValid }">
                        <!-- <span class="error-msg" v-if="!usernameValid">Please enter Username</span> -->
                    </div>

                    <div class="form-group text-left">
                        <input type="password"  class="form-control" name="password" id="password" placeholder="Password" required="" v-model="password" v-bind:class="{ invalid: !passwordValid }">
                        <!-- <span class="error-msg" v-if="!passwordValid">Please enter Password</span> -->
                    </div>

                    <button class="btn btn-primary btn-block mt-2" name="login" id="login"  v-bind:disabled="!isComplete">Log In</button>

                    <div class="loader" v-if="loading"></div>
                </form>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script type="text/javascript" src="{{ url('js/login.js') }}"></script>
@endpush

@stop
