/* global Vue, SparkForm, $, axios, Bus */

Vue.component('spark-enable-two-factor-auth', {
  props: ['user'],

  data: () => ({
      qrcode: '',
      secret: '',
      generating: false,
      showSecret: false,
      form: new SparkForm({ code: '' })
  }),

  mounted () {
    $(this.$refs.modal).on('hidden.bs.modal', () => {
      this.form.code = ''
      this.form.resetStatus()
    })
  },

  methods: {
    /**
     * Generate qr code.
     */
    generate () {
      this.generating = true

      axios.post('/settings/two-factor-auth-generate')
        .then(({ data }) => {
          this.generating = false
          this.showVerification(data)
        })
    },

    /**
     * Show the verification modal.
     *
     * @param {Object} { qrcode, secret }
     */
    showVerification ({ qrcode, secret }) {
      this.qrcode = qrcode
      this.secret = secret

      $(this.$refs.modal).modal('show')
    },

    /**
     * Enable two factor authentication.
     */
    enable () {
      Spark.post('/settings/two-factor-auth-google', this.form)
        .then(resetCode => {
          $(this.$refs.modal).modal('hide')

          this.$parent.$emit('receivedTwoFactorResetCode', resetCode)

          Bus.$emit('updateUser')
        })
    }
  }
})
