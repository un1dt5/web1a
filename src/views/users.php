<?php
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] != true) {
    header('Location: /login');
    exit();
}

$is_admin = $_SESSION['type'] === 'admin' ? true : false;
$is_teacher = $_SESSION['type'] === 'teacher' ? true : false;
$is_student = $_SESSION['type'] === 'student' ? true : false;
$current_username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Users Management</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.jpg">
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
                <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="welcome-text">
                            <h4>Hi <?php echo htmlspecialchars($_SESSION['fullname']); ?>, welcome back!</h4>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Users List</h4>
                                <button v-if="isAdmin || isTeacher" class="btn btn-primary" @click="showAddUserModal">Add New User</button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-responsive-sm">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Avatar</th>
                                                <th>Username</th>
                                                <th>Fullname</th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                                <th>Type</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(item, i) in users">
                                                <th>{{ i+1 }}</th>
                                                <td><img :src="item.avatar || '/uploads/default.jpg'" class="rounded-circle" width="35" height="35"></td>
                                                <td>{{ item.username }}</td>
                                                <td>{{ item.fullname }}</td>
                                                <td>{{ item.phone }}</td>
                                                <td>{{ item.email }}</td>
                                                <td>{{ item.type }}</td>
                                                <td>
                                                    <a :href="`/profile?username=${item.username}`" class="btn btn-sm btn-info">View Profile</a>
                                                    <button v-if="(isAdmin && (item.type !== 'admin' || currentUsername === item.username))"
                                                        @click="editUser(item)" class="btn btn-sm btn-warning ml-1">Edit</button>
                                                    <button v-if="(isAdmin && item.username !== currentUsername && (item.type === 'student' || item.type === 'teacher'))"
                                                        @click="deleteUser(item.username)" class="btn btn-sm btn-danger ml-1">Delete</button>

                                                    <button v-if="(isTeacher && item.type === 'student')"
                                                        @click="editUser(item)" class="btn btn-sm btn-warning ml-1">Edit</button>
                                                    <button v-if="(isTeacher && item.type === 'student')"
                                                        @click="deleteUser(item.username)" class="btn btn-sm btn-danger ml-1">Delete</button>

                                                    <button v-if="(isStudent && item.username === currentUsername)"
                                                        @click="editUser(item)" class="btn btn-sm btn-warning ml-1">Edit</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="userModal">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{ isEditing ? 'Edit User' : 'Add New User' }}</h5>
                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form @submit.prevent="saveUser">
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input type="text" class="form-control" v-model="currentUser.username" :disabled="isStudent && currentUser.username === currentUsername">
                                    </div>
                                    <div class="form-group" v-if="!isEditing">
                                        <label>Password</label>
                                        <input type="password" class="form-control" v-model="currentUser.password">
                                    </div>
                                    <div class="form-group">
                                        <label>Fullname</label>
                                        <input type="text" class="form-control" v-model="currentUser.fullname" :disabled="isStudent && currentUser.username === currentUsername">
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" class="form-control" v-model="currentUser.email">
                                    </div>
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="text" class="form-control" v-model="currentUser.phone">
                                    </div>
                                    <div class="form-group" v-if="isAdmin">
                                        <label>User Type</label>
                                        <select class="form-control" v-model="currentUser.type">
                                            <option value="student">Student</option>
                                            <option value="teacher">Teacher</option>
                                            <option value="admin">Admin</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Avatar</label>
                                        <input type="file" class="form-control" @change="handleFileUpload" accept="image/*">
                                        <div v-if="imagePreview" class="mt-2">
                                            <img :src="imagePreview" alt="Preview" style="max-width: 100px; max-height: 100px;">
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/3.2.31/vue.global.prod.min.js"></script>
    <script src="assets/vendor/global/global.min.js"></script>
    <script src="assets/js/quixnav-init.js"></script>
    <script src="assets/js/custom.min.js"></script>
    <script src="assets/vendor/sweetalert2/dist/sweetalert2.min.js"></script>

    <script>
        const App = {
            data() {
                return {
                    users: [],
                    isAdmin: <?php echo $is_admin ? 'true' : 'false'; ?>,
                    isTeacher: <?php echo $is_teacher ? 'true' : 'false'; ?>,
                    isStudent: <?php echo $is_student ? 'true' : 'false'; ?>,
                    currentUsername: '<?php echo $current_username; ?>',
                    isEditing: false,
                    selectedFile: null,
                    imagePreview: null,
                    currentUser: {
                        username: '',
                        password: '',
                        fullname: '',
                        email: '',
                        phone: '',
                        type: 'student',
                        avatar: ''
                    }
                }
            },
            methods: {
                loadUsers() {
                    fetch('/api/users')
                        .then(r => r.json())
                        .then(r => {
                            if (r.status) {
                                this.users = r.data;
                            } else {
                                Swal.fire('Error', r.msg, 'error');
                            }
                        })
                        .catch(err => {
                            Swal.fire('Error', 'Failed to load users', 'error');
                        });
                },
                handleFileUpload(event) {
                    this.selectedFile = event.target.files[0];
                    if (this.selectedFile) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.imagePreview = e.target.result;
                        };
                        reader.readAsDataURL(this.selectedFile);
                    }
                },
                showAddUserModal() {
                    this.isEditing = false;
                    this.currentUser = {
                        username: '',
                        password: '',
                        fullname: '',
                        email: '',
                        phone: '',
                        type: 'student',
                        avatar: ''
                    };
                    this.imagePreview = null;
                    $('#userModal').modal('show');
                },
                editUser(user) {
                    this.isEditing = true;
                    this.currentUser = {
                        ...user
                    };
                    this.imagePreview = user.avatar;
                    $('#userModal').modal('show');
                },
                async saveUser() {
                    try {
                        const updateData = {
                            ...this.currentUser
                        };

                        if (this.selectedFile) {
                            const formData = new FormData();
                            formData.append('file', this.selectedFile);
                            if (this.isEditing) {
                                formData.append('target_id', this.currentUser.id);
                            }

                            const uploadResponse = await fetch('/api/upload', {
                                method: 'POST',
                                body: formData
                            });
                            const uploadResult = await uploadResponse.json();

                            if (uploadResult.status) {
                                updateData.avatar = uploadResult.url;
                            } else {
                                Swal.fire('Upload Error', uploadResult.msg, 'error');
                                return;
                            }
                        }

                        const response = await fetch(this.isEditing ? '/api/update_user' : '/api/add_user', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(updateData)
                        });

                        const result = await response.json();
                        if (result.status) {
                            $('#userModal').modal('hide');
                            await this.loadUsers();
                            Swal.fire('Success', result.msg, 'success');
                            this.selectedFile = null;
                            this.imagePreview = null;
                        } else {
                            Swal.fire('Error', result.msg, 'error');
                        }
                    } catch (err) {
                        console.error('Save Error:', err);
                        Swal.fire('Error', 'An error occurred while saving', 'error');
                    }
                },
                deleteUser(username) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`/api/delete_user?username=${username}`)
                                .then(r => r.json())
                                .then(r => {
                                    if (r.status) {
                                        this.loadUsers();
                                        Swal.fire('Deleted!', r.msg, 'success');
                                    } else {
                                        Swal.fire('Error', r.msg, 'error');
                                    }
                                })
                                .catch(err => {
                                    Swal.fire('Error', 'An error occurred while deleting', 'error');
                                });
                        }
                    });
                }
            },
            mounted() {
                this.loadUsers();
            }
        };
        Vue.createApp(App).mount('#app');
    </script>
</body>

</html>