<spark-enable-two-factor-auth :user="user" inline-template>
    <div class="panel panel-default">
        <div class="panel-heading">Two-Factor Authentication</div>

        <div class="panel-body">
            <!-- Information Message -->
            <div class="alert alert-info">
                In order to use two-factor authentication, you <strong>must</strong> install the
                <strong><a href="https://support.google.com/accounts/answer/1066447?hl=en" target="_blank">Google Authenticator</a></strong> application
                on your smartphone. Google Authenticator is available for <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en" target="_blank">iOS</a> and <a href="https://itunes.apple.com/en/app/google-authenticator/id388497605?mt=8" target="_blank">Android</a>.
            </div>

            <form class="form-horizontal" role="form">
                <!-- Enable Button -->
                <div class="form-group">
                    <div class="col-md-offset-4 col-md-6">
                        <button type="submit" class="btn btn-primary"
                                @click.prevent="enable"
                                :disabled="form.busy">

                            <span v-if="form.busy">
                                <i class="fa fa-btn fa-spinner fa-spin"></i>Enabling
                            </span>

                            <span v-else>
                                Enable
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('google2fa::verify-qr-code-modal')
</spark-enable-two-factor-auth>
