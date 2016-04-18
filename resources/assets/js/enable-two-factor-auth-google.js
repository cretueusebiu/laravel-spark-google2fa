module.exports = {
    props: ['user'],

    /**
     * The component's data.
     */
    data() {
        return {
            qrCodeUrl: '',
            form: new SparkForm({}),
            verifyForm: new SparkForm({code: ''})
        }
    },

    methods: {
        /**
         * Enable two-factor authentication for the user.
         */
        enable() {
            Spark.post('/settings/two-factor-auth-generate', this.form)
                .then((url) => {
                    this.qrCodeUrl = url;

                    $('#modal-show-verify-qr-code').modal('show')
                    .on('hidden.bs.modal', () => {
                        this.verifyForm.resetStatus();
                        this.verifyForm.code = '';
                    });
                });
        },

        /**
         * Verify the code.
         */
        verify() {
            Spark.post('/settings/two-factor-auth', this.verifyForm)
                .then((code) => {
                    $('#modal-show-verify-qr-code').modal('hide');

                    this.$dispatch('receivedTwoFactorResetCode', code);
                    this.$dispatch('updateUser');
                });
        }
    }
};
