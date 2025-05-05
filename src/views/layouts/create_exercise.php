<div class="modal fade" id="createExercise">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Exercise</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-sm-5 col-form-label">Title</label>
                    <div class="col-sm-12">
                        <input v-model="title" class="form-control" placeholder="">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-5 col-form-label">Description</label>
                    <div class="col-sm-12">
                        <input v-model="desc" class="form-control" placeholder="">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-xl-12 col-form-label">Upload file (png, jpg, pdf, docx, txt)</label>
                    <div class="col-sm-12">
                        <div class="custom-file">
                            <input ref="file-upload" type="file" class="custom-file-input" @change="uploadFile($event)" accept="application/pdf, image/png, image/jpeg, text/plain, .docx">
                            <label ref="filename" class="custom-file-label">Choose file</label>
                        </div>
                        <div class="mt-1 text-center">
                            <p class="text-danger">{{ fileErr }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button ref="close-modal" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button ref="btn-save" type="button" class="btn btn-primary" disabled @click="save">Save and create</button>
            </div>
        </div>
    </div>
</div>