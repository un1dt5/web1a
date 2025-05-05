<?php
if (isset($_SESSION['is_login']) && $_SESSION['is_login'] == true) {
    header('Location: /');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Log in</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.jpg">
    <link href="assets/css/style.css" rel="stylesheet">

</head>

<body class="h-100" id="app">
    <div class="authincation h-100">
        <div class="container-fluid h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
                                    <h4 class="text-center mb-4">Log in</h4>
                                    <form @submit.prevent="login">
                                        <div class="form-group">
                                            <label><strong>Username</strong></label>
                                            <input type="username" class="form-control" v-model="username" autofocus>
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Password</strong></label>
                                            <input type="password" class="form-control" v-model="password">
                                        </div>
                                        <div class="form-row d-flex justify-content-between mt-4 mb-2">
                                            <div class="form-group">
                                                <a href="javascript:void(0)" data-toggle="modal" data-target="#forgot">Forgot Password?</a>
                                                <div class="modal fade" id="forgot">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Forgot password</h5>
                                                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button class="btn btn-primary btn-block">Log in</button>
                                        </div>
                                    </form>
                                    <div class="text-center text-danger">
                                        <span>{{ msg }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/3.2.31/vue.global.prod.min.js"></script>
    <script src="assets/vendor/global/global.min.js"></script>
    <script src="assets/js/quixnav-init.js"></script>
    <script src="assets/js/custom.min.js"></script>
    <script>
        const App = {
            data() {
                return {
                    username: '',
                    password: '',
                    msg: ''
                }
            },
            methods: {
                login() {
                    fetch('/api/login', {
                            method: 'POST',
                            body: JSON.stringify({
                                username: this.username,
                                password: this.password
                            }),
                            headers: {
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(r => r.json())
                        .then(r => r.auth && (location.href = '/'))
                        .then(r => !r && (this.msg = 'Username or password is incorrect!'))
                }
            }
        }
        Vue.createApp(App).mount('#app')
    </script>
</body>

</html>