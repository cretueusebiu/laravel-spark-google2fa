<div class="modal fade" id="modal-show-verify-qr-code" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button " class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                <h4 class="modal-title">
                    Two-Factor Authentication Verify
                </h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="form-group">
                            <div class="text-center">
                                <img v-bind:src="qrCodeUrl">
                            </div>
                        </div>

                        <div class="form-group" :class="{'has-error': verifyForm.errors.has('code')}">
                            <input type="code" class="form-control" name="code" v-model="verifyForm.code" placeholder="Code">
                            <span class="help-block" v-show="verifyForm.errors.has('code')">
                                @{{ verifyForm.errors.get('code') }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="h5 text-center">
                    Scan the barcode with the <strong>Google Authenticator</strong> app and then enter the code.
                </div>
            </div>

            <div class="modal-footer">
                <div class="pull-left">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>

                <button type="submit" class="btn btn-primary"
                        :disabled="verifyForm.busy"
                        @click.prevent="verify"
                >
                    <span v-if="verifyForm.busy">
                        <i class="fa fa-btn fa-spinner fa-spin"></i>Verifying
                    </span>
                    <span v-else>Verify</span>
                </button>
            </div>
        </div>
    </div>
</div>
