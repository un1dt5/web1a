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
    <title>Exercises</title>
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
                <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="welcome-text">
                            <h4>Hi <?php echo htmlspecialchars($_SESSION['fullname']); ?>, welcome back!</h4>
                        </div>
                    </div>
                </div>
                <?php if (!$is_student) { ?>
                    <div class="row">
                        <div class="col-xl-12 mb-3">
                            <div class="float-right">
                                <button class="btn btn-outline-primary" data-toggle="modal" data-target="#createExercise">Create exercise</button>
                                <?php include 'layouts/create_exercise.php'; ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="row">
                    <div v-for="(item, i) in list" class="col-xl-6">
                        <div class="card text-white bg-dark">
                            <div class="card-header">
                                <h5 class="card-title text-white">{{ item.title }}</h5>
                            </div>
                            <div class="card-body mb-0">
                                <p class="card-text">{{ item.description }}</p>
                                <a :href="item.url" class="btn btn-light btn-card text-dark">{{ item.url.split('/').slice(-1)[0] }}</a>
                            </div>
                            <div class="card-footer d-sm-flex justify-content-between">Created {{ item.created }}
                                <?php if (!$is_student) { ?>
                                    <div>
                                        <button data-toggle="modal" :data-target="`#modal_submited${i}`" class="btn btn-primary mr-3">{{ item.submited.length }} solved</button>
                                        <?php include 'layouts/submited_exercises.php' ?>
                                        <button @click="() => deleteItem(item.id)" class="btn btn-primary">Delete</button>
                                    </div>
                                <?php } else { ?>
                                    <button :ref="item.id" @click="() => btnUpload(i)" class="btn btn-primary">
                                        {{ item.submited ? item.submited.url.split('/').slice(-1)[0] : `Upload solve` }}
                                    </button>
                                    <input @change="(e) => uploadSolve(item.id, e)" ref="upload_sol" type="file" style="display:none;" accept="application/pdf, image/png, image/jpeg, text/plain, .docx" /><?php } ?>
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
                    list: [],
                    title: '',
                    desc: '',
                    file: null,
                    fileErr: ''
                }
            },
            methods: {
                uploadFile(e) {
                    this.$refs.filename.innerText = e.target.files[0].name
                    if (e.target.files[0].size > 1024 * 1024 * 20) {
                        this.$refs['btn-save'].disabled = true
                        this.fileErr = 'File size should not be larger than 20MB'
                        return
                    }
                    this.file = e.target.files[0]
                    this.$refs['btn-save'].disabled = false
                    this.fileErr = ''
                },
                save() {
                    let formData = new FormData()
                    formData.append('file', this.file)
                    formData.append('title', this.title)
                    formData.append('desc', this.desc)
                    fetch('/api/create_exercise', {
                            method: 'POST',
                            body: formData
                        })
                        .then(r => r.json())
                        .then(r => {
                            if (r.status) {
                                this.afterSave()
                                swal('Exercises created!', '', 'success')
                                this.showAll()
                            } else {
                                this.fileErr = r.msg
                            }
                        })
                        .catch(err => this.fileErr = err)
                },
                showAll() {
                    fetch('/api/exercises')
                        .then(r => r.json())
                        .then(r => {
                            if (r.status) {
                                this.list = r.data
                            } else {
                                sweetAlert('Oops...', r.msg, 'error')
                            }
                        })
                        .catch(err => {
                            sweetAlert('Oops...', '' + err, 'error')
                        })
                },
                <?php if (!$is_student) { ?>
                    deleteItem(id) {
                        fetch(`/api/delete_exercise?id=${id}`)
                            .then(r => r.json())
                            .then(r => {
                                if (r.status) {
                                    swal('Exercise deleted!', '', 'success')
                                    this.showAll()
                                } else {
                                    sweetAlert('Oops...', r.msg, 'error')
                                }
                            })
                            .catch(err => {
                                sweetAlert('Oops...', '' + err, 'error')
                            })
                    },
                <?php } else { ?>
                    btnUpload(i) {
                        this.$refs['upload_sol'][i].click()
                    },
                    uploadSolve(id, e) {
                        if (e.target.files[0].size > 1024 * 1024 * 20) {
                            sweetAlert('Oops...', 'File size should not be larger than 20MB', 'error')
                            return
                        }
                        let formData = new FormData()
                        formData.append('file', e.target.files[0])
                        formData.append('id', id)
                        formData.append('type', 'exercise')
                        fetch('/api/submit', {
                                method: 'POST',
                                body: formData
                            })
                            .then(r => r.json())
                            .then(r => {
                                if (r.status) {
                                    swal('Submited!', '', 'success')
                                    this.$refs[`${id}`][0].innerText = r.url.split('/').slice(-1)[0]
                                } else {
                                    sweetAlert('Oops...', '' + r.msg, 'error')
                                }
                            })
                            .catch(err => {
                                sweetAlert('Oops...', '' + err, 'error')
                            })
                    },
                <?php } ?>
                afterSave() {
                    this.$refs['close-modal'].click()
                    this.$refs['btn-save'].disabled = true
                    this.$refs['filename'].innerText = 'Choose file'
                    this.$refs['file-upload'].value = ''
                    this.fileErr = ''
                    this.title = ''
                    this.desc = ''
                }
            },
            mounted() {
                this.showAll()
            }
        }
        Vue.createApp(App).mount('#app')
    </script>
</body>

</html>