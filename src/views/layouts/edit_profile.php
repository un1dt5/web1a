<div class="modal fade" id="changePassword">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Password</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-sm-5 col-form-label">Old password</label>
                    <div class="col-sm-12">
                        <input type="password" v-model="old_password" class="form-control" placeholder="">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-5 col-form-label">New password</label>
                    <div class="col-sm-12">
                        <input type="password" v-model="new_password" class="form-control" placeholder="">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-5 col-form-label">Confirm new password</label>
                    <div class="col-sm-12">
                        <input ref="confirm" type="password" v-model="confirm_password" class="form-control" placeholder="">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button ref="close-modal" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button ref="btn-change" type="button" class="btn btn-primary" disabled @click="changePassword">Save changes</button>
            </div>
        </div>
    </div>
</div>