<?php
if (!(isset($_SESSION['is_login']) && $_SESSION['is_login'] == true)) {
    header('Location: /login');
    exit();
}
$is_student = $_SESSION['type'] === 'student' ? true : false;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Profile</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.jpg">
    <link rel="stylesheet" href="assets/vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'layouts/preloader.php'; ?>
    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">
        <?php include 'layouts/header.php'; ?>
        <?php include 'layouts/sidebar.php'; ?>
        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body" id="app">
            <!-- row -->
            <div class="container-fluid">
                <div class="row justify-content-md-center">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Avatar</h4>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-column align-items-center text-center">
                                    <img data-holder-rendered="true" class="rounded-circle" style="object-position: center; object-fit: cover; max-width: 100%; width: 150px; height: 150px;" :src="avt" />
                                    <span class="text-dark">{{ fullname }}</span>
                                    <span>{{ email }}</span>
                                </div>
                                <div v-if="isOwnProfile" class="d-flex flex-column align-items-center text-center mt-2">
                                    <button type="button" class="btn btn-outline-danger btn-block" data-toggle="modal" data-target="#changeAvt">Edit Avatar</button>
                                    <?php include 'layouts/change_avt.php'; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Infomation</h4>
                            </div>
                            <div class="card-body">
                                <form>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Username</label>
                                        <div class="col-sm-9">
                                            <input v-model="username" class="form-control" placeholder="Username" :disabled="!isOwnProfile || <?php echo $is_student ? 'true' : 'false'; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Fullname</label>
                                        <div class="col-sm-9">
                                            <input v-model="fullname" class="form-control" placeholder="Fullname" :disabled="!isOwnProfile || <?php echo $is_student ? 'true' : 'false'; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Email</label>
                                        <div class="col-sm-9">
                                            <input v-model="email" class="form-control" placeholder="Email" :disabled="!isOwnProfile">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Phone Number</label>
                                        <div class="col-sm-9">
                                            <input v-model="phone" class="form-control" placeholder="Phone Number" :disabled="!isOwnProfile">
                                        </div>
                                    </div>
                                    <div class="row" v-if="isOwnProfile">
                                        <div class="col-md-6">
                                            <button type="button" class="btn btn-primary btn-block" @click="save">Save</button>
                                        </div>
                                        <div class="col-md-6">
                                            <button type="button" class="btn btn-secondary btn-block" data-toggle="modal" data-target="#changePassword">
                                                Change password
                                            </button>
                                            <?php include 'layouts/edit_profile.php'; ?>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header">
                        <h4 class="card-title">Messages</h4>
                    </div>
                    <div class="card-body">
                        <div v-if="isOwnProfile === false">
                            <form @submit.prevent="sendMessage">
                                <div class="form-group">
                                    <textarea v-model="newMessage" class="form-control" placeholder="Leave a message..." rows="3"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Send</button>
                            </form>
                        </div>

                        <div v-if="messages.length > 0" class="mt-3">
                            <ul class="list-group">
                                <li v-for="(message, index) in messages" :key="message.id" class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ message.from_username }}</strong>: {{ message.content }}
                                        <small class="text-muted d-block">{{ message.created_at }}</small>
                                    </div>
                                    <div v-if="message.from_username === currentUser">
                                        <button class="btn btn-sm btn-warning mr-2" @click="editMessage(index)">Edit</button>
                                        <button class="btn btn-sm btn-danger" @click="deleteMessage(message.id)">Delete</button>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div v-else class="text-muted">No messages yet.</div>
                    </div>
                </div>
            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->
        <?php include 'layouts/footer.php'; ?>
    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!-- Required vendors -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/3.2.31/vue.global.prod.min.js"></script>
    <script src="assets/vendor/global/global.min.js"></script>
    <script src="assets/js/quixnav-init.js"></script>
    <script src="assets/js/custom.min.js"></script>
    <!-- Owl Carousel -->
    <script src="assets/vendor/owl-carousel/js/owl.carousel.min.js"></script>
    <!-- Counter Up -->
    <script src="assets/vendor/jqvmap/js/jquery.vmap.min.js"></script>
    <script src="assets/vendor/jqvmap/js/jquery.vmap.usa.js"></script>
    <script src="assets/vendor/jquery.counterup/jquery.counterup.min.js"></script>
    <script src="assets/vendor/sweetalert2/dist/sweetalert2.min.js"></script>
    <script>
        const App = {
            data() {
                return {
                    currentUser: '<?php echo $_SESSION["username"]; ?>',
                    username: '<?php echo isset($_GET["username"]) ? $_GET["username"] : $_SESSION["username"]; ?>',
                    fullname: '',
                    avt: '',
                    email: '',
                    phone: '',
                    old_password: '',
                    new_password: '',
                    confirm_password: '',
                    imgUpload: null,
                    imgErr: '',
                    isOwnProfile: <?php echo isset($_GET["username"]) && $_GET["username"] !== $_SESSION["username"] ? 'false' : 'true'; ?>,
                    newMessage: '',
                    messages: []
                }
            },
            async mounted() {
                try {
                    const response = await fetch(`/api/get_user_profile?username=${this.username}`);
                    const result = await response.json();

                    if (result.status) {
                        const user = result.data;
                        this.fullname = user.fullname;
                        this.avt = user.avatar || '/uploads/default.jpg';
                        this.email = user.email;
                        this.phone = user.phone;
                    } else {
                        console.error('API Error:', result);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: result.msg || 'Failed to load user profile'
                        });
                    }
                } catch (err) {
                    console.error('Fetch Error:', err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load user profile'
                    });
                }
                this.fetchMessages();
            },
            methods: {
                save() {
                    fetch('/api/edit', {
                            method: 'POST',
                            body: JSON.stringify({
                                <?php
                                echo $is_student ? '"phone": this.phone, "email": this.email' : '"username": this.username, "fullname": this.fullname, "phone": this.phone, "email": this.email';
                                ?>
                            }),
                            headers: {
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(r => r.json())
                        .then(r => {
                            if (r.status) {
                                $('#userModal').modal('hide');
                                Swal.fire('Success', r.msg, 'success');
                            } else {
                                Swal.fire('Error', r.msg, 'error');
                            }
                        })
                        .catch(err => {
                            Swal.fire('Error', 'An error occurred while saving', 'error');
                        });
                },
                changePassword() {
                    fetch('api/change_password', {
                            method: 'POST',
                            body: JSON.stringify({
                                old_password: this.old_password,
                                password: this.new_password
                            }),
                            headers: {
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(r => r.json())
                        .then(r => {
                            if (r.status) {
                                this.$refs['close-modal'].click();
                                swal(r.msg, '', 'success');
                                this.new_password = '';
                                this.old_password = '';
                                this.confirm_password = '';
                            } else {
                                sweetAlert('Oops...', r.msg, 'error');
                            }
                        })
                        .catch(err => {
                            sweetAlert('Oops...', err, 'error');
                        });
                },
                uploadFile(e) {
                    this.$refs.filename.innerText = e.target.files[0].name
                    if (e.target.files[0].size > 1024 * 1024 * 5) {
                        this.$refs['btn-upload'].disabled = true
                        this.imgErr = 'File size should not be larger than 5MB'
                        return
                    }
                    this.imgUpload = e.target.files[0]
                    this.$refs['btn-upload'].disabled = false
                    this.imgErr = ''
                },
                changeAvt() {
                    let formData = new FormData()
                    formData.append('file', this.imgUpload)
                    fetch('/api/upload', {
                            method: 'POST',
                            body: formData
                        })
                        .then(r => r.json())
                        .then(r => {
                            if (r.status) {
                                this.afterUpload()
                                this.avt = r.url
                                swal(r.msg, '', 'success')
                            } else {
                                this.imgErr = r.msg
                                this.$refs['btn-upload'].disabled = true
                            }
                        })
                        .catch(err => this.imgErr = err)
                },
                afterUpload() {
                    this.imgUpload = null
                    this.$refs['close-btn-upload'].click()
                    this.$refs['btn-upload'].disabled = true
                    this.$refs['filename'].innerText = 'Choose file'
                    this.$refs['file-upload'].value = ''
                },
                async fetchMessages() {
                    try {
                        const response = await fetch(`/api/messages?username=${this.username}`);
                        const result = await response.json();
                        if (result.status) {
                            this.messages = result.data;
                        } else {
                            console.error('Failed to fetch messages:', result.msg);
                        }
                    } catch (err) {
                        console.error('Error fetching messages:', err);
                    }
                },
                async sendMessage() {
                    try {
                        const response = await fetch('/api/messages/create', {
                            method: 'POST',
                            body: JSON.stringify({
                                to_username: this.username,
                                content: this.newMessage
                            }),
                            headers: {
                                'Content-Type': 'application/json'
                            }
                        });
                        const result = await response.json();
                        if (result.status) {
                            this.newMessage = '';
                            this.fetchMessages();
                            Swal.fire('Success', 'Message sent successfully', 'success');
                        } else {
                            Swal.fire('Error', result.msg, 'error');
                        }
                    } catch (err) {
                        Swal.fire('Error', 'Failed to send message', 'error');
                    }
                },
                async deleteMessage(id) {
                    try {
                        const response = await fetch('/api/messages/delete', {
                            method: 'POST',
                            body: JSON.stringify({
                                id
                            }),
                            headers: {
                                'Content-Type': 'application/json'
                            }
                        });
                        const result = await response.json();
                        if (result.status) {
                            this.fetchMessages();
                            Swal.fire('Success', 'Message deleted successfully', 'success');
                        } else {
                            Swal.fire('Error', result.msg, 'error');
                        }
                    } catch (err) {
                        Swal.fire('Error', 'Failed to delete message', 'error');
                    }
                },
                editMessage(index) {
                    const message = this.messages[index];
                    const newContent = prompt('Edit your message:', message.content);
                    if (newContent !== null) {
                        fetch('/api/messages/update', {
                                method: 'POST',
                                body: JSON.stringify({
                                    id: message.id,
                                    content: newContent
                                }),
                                headers: {
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(r => r.json())
                            .then(r => {
                                if (r.status) {
                                    this.fetchMessages();
                                    Swal.fire('Success', 'Message updated successfully', 'success');
                                } else {
                                    Swal.fire('Error', r.msg, 'error');
                                }
                            })
                            .catch(err => Swal.fire('Error', 'Failed to update message', 'error'));
                    }
                }
            },
            watch: {
                confirm_password(val) {
                    if (val == this.new_password) {
                        this.$refs.confirm.className = 'form-control'
                        this.$refs['btn-change'].disabled = false
                        return
                    }
                    this.$refs.confirm.className = 'form-control is-invalid'
                    this.$refs['btn-change'].disabled = true
                }
            }
        }
        Vue.createApp(App).mount('#app')
    </script>
</body>

</html>