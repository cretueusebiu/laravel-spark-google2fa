<spark-enable-two-factor-auth :user="user" inline-template>
  <div class="card card-default">
    <div class="card-header">{{__('Two-Factor Authentication')}}</div>

    <div class="card-body">
      <div class="alert alert-info">
        {!! __('In order to use two-factor authentication, you must install install :authyLink or :authenticatorLink on your smartphone.', [
          'authyLink' => '<b><a href="https://authy.com/download/" target="_blank">Authy</a></b>',
          'authenticatorLink' => '<b><a href="https://support.google.com/accounts/answer/1066447" target="_blank">Google Authenticator</a></b>',
        ]) !!}
      </div>

      <button @click="generate" type="button" class="btn btn-primary" :disabled="generating">
        {{__('Set up two-factor authentication')}}
      </button>
    </div>

    {{-- Verification Modal --}}
    <div class="modal" ref="modal" tabindex="-1" role="dialog" data-backdrop="static">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ __('Connect your application') }}
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            {{ __('Scan the image below with the two-factor authentication app on your phone.') }}

            <div class="text-center mb-2">
              <img :src="qrcode">
            </div>

            <div v-if="showSecret" class="mb-3">
              {!! __('Select manual entry on your app and enter: :code', ['code' => '@{{ secret }}']) !!}
            </div>
            <div v-else class="mb-3">
              {!! __('Having trouble scanning the code? :linkOpen Try this instead. :linkClose', [
                'linkOpen' => '<a @click.prevent="showSecret = true" href="#">',
                'linkClose' => '</a>',
              ]) !!}
            </div>

            <form @submit.prevent="enable" class="d-flex">
              <input v-model="form.code" type="text" name="code" class="form-control"
                :class="{ 'is-invalid': form.errors.has('code') }" autocomplete="off"
                maxlength="6" required placeholder="{{ __('Enter the code from the app') }}">

              <button type="submit" class="btn btn-primary ml-3" :disabled="form.code.length < 6 || form.busy">
                {{ __('Enable') }}
              </button>
            </form>

            <div v-if="form.errors.has('code')" class="invalid-feedback d-block">
              @{{ form.errors.get('code') }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</spark-enable-two-factor-auth>
