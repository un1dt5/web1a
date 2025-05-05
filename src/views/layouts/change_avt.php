<div class="modal fade" id="changeAvt">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Avatar</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-xl-12">
                        <div class="input-group">
                            <div class="custom-file">
                                <input ref="file-upload" type="file" class="custom-file-input" @change="uploadFile($event)" accept="image/png, image/jpeg">
                                <label ref="filename" class="custom-file-label">Choose file</label>
                            </div>
                        </div>
                        <div class="mt-1">
                            <p class="text-danger">{{ imgErr }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button ref="close-btn-upload" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button ref="btn-upload" type="button" class="btn btn-primary" disabled @click="changeAvt">Save changes</button>
            </div>
        </div>
    </div>
</div>