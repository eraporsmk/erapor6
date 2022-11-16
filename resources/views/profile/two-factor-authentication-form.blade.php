<x-jet-action-section>
  <x-slot name="title">
    {{ __('Otentikasi Dua Faktor') }}
  </x-slot>

  <x-slot name="description">
    {{ __('Tambahkan keamanan tambahan ke akun Anda menggunakan otentikasi dua faktor.') }}
  </x-slot>

  <x-slot name="content">
    <h6 class="fw-bolder">
      @if ($this->enabled)
        @if ($showingConfirmation)
          {{ __('Anda mengaktifkan otentikasi dua faktor.') }}
        @else
          {{ __('Anda telah mengaktifkan otentikasi dua faktor.') }}
        @endif
      @else
        {{ __('Anda belum mengaktifkan otentikasi dua faktor.') }}
      @endif
    </h6>

    <p class="card-text">
      {{ __('Saat otentikasi dua faktor diaktifkan, Anda akan dimintai token acak yang aman selama otentikasi. Anda dapat mengambil token ini dari aplikasi Google Authenticator ponsel Anda.') }}
    </p>

    @if ($this->enabled)
      @if ($showingQrCode)
        <p class="card-text mt-2">
          @if ($showingConfirmation)
            {{ __('Pindai kode QR berikut menggunakan aplikasi autentikator ponsel Anda dan konfirmasikan dengan kode OTP yang dihasilkan.') }}
          @else
            {{ __('Otentikasi dua faktor sekarang diaktifkan. Pindai kode QR berikut menggunakan aplikasi autentikator ponsel Anda.') }}
          @endif
        </p>

        <div class="mt-2">
          {!! $this->user->twoFactorQrCodeSvg() !!}
        </div>

        <div class="mt-4">
            <p class="font-semibold">
              {{ __('Kunci Pengaturan') }}: {{ decrypt($this->user->two_factor_secret) }}
            </p>
        </div>

        @if ($showingConfirmation)
          <div class="mt-2">
            <x-jet-label for="code" value="{{ __('Kode') }}" />
            <x-jet-input id="code" class="d-block mt-3 w-100" type="text" inputmode="numeric" name="code" autofocus autocomplete="one-time-code"
                wire:model.defer="code"
                wire:keydown.enter="confirmTwoFactorAuthentication" />
            <x-jet-input-error for="code" class="mt-3" />
          </div>
        @endif
      @endif

      @if ($showingRecoveryCodes)
        <p class="card-text mt-2">
          {{ __('Simpan kode pemulihan ini di pengelola kata sandi yang aman. Mereka dapat digunakan untuk memulihkan akses ke akun Anda jika perangkat otentikasi dua faktor Anda hilang.') }}
        </p>

        <div class="bg-light rounded p-2">
          @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
            <div>{{ $code }}</div>
          @endforeach
        </div>
      @endif
    @endif

    <div class="mt-2">
      @if (!$this->enabled)
        <x-jet-confirms-password wire:then="enableTwoFactorAuthentication">
          <x-jet-button type="button" wire:loading.attr="disabled">
            {{ __('Aktifkan') }}
          </x-jet-button>
        </x-jet-confirms-password>
      @else
        @if ($showingRecoveryCodes)
          <x-jet-confirms-password wire:then="regenerateRecoveryCodes">
            <x-jet-secondary-button class="me-1">
              {{ __('Buat Ulang Kode Pemulihan') }}
            </x-jet-secondary-button>
          </x-jet-confirms-password>
        @elseif ($showingConfirmation)
          <x-jet-confirms-password wire:then="confirmTwoFactorAuthentication">
            <x-jet-button type="button" wire:loading.attr="disabled">
              {{ __('Konfirmasi') }}
            </x-jet-button>
          </x-jet-confirms-password>
        @else
          <x-jet-confirms-password wire:then="showRecoveryCodes">
            <x-jet-secondary-button class="me-1">
              {{ __('Tampilkan Kode Pemulihan') }}
            </x-jet-secondary-button>
          </x-jet-confirms-password>
        @endif

        <x-jet-confirms-password wire:then="disableTwoFactorAuthentication">
          <x-jet-danger-button wire:loading.attr="disabled">
            {{ __('Non Aktifkan') }}
          </x-jet-danger-button>
        </x-jet-confirms-password>
      @endif
    </div>
  </x-slot>
</x-jet-action-section>
