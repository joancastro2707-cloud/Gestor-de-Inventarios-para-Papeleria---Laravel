<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Seguridad: Autenticación en Dos Pasos (2FA)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8 bg-white p-6 rounded-lg shadow">
            @if (session('status'))
                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                    {{ session('status') }}
                </div>
            @endif

            @if (! $enabled)
                <p class="mb-4 text-sm text-gray-600">
                    Escanea este código QR con Google Authenticator o Authy e ingresa el código de 6 dígitos para activar 2FA:
                </p>

                <div class="flex justify-center my-4">
                    <img src="data:image/svg+xml;base64,{{ $qrBase64 }}" alt="Código QR 2FA" class="border p-2 rounded">
                </div>

                <p class="text-xs text-center text-gray-500 mb-6">
                    Clave manual: <span class="font-mono font-bold">{{ $secret }}</span>
                </p>

                <form method="POST" action="{{ route('2fa.confirm') }}">
                    @csrf
                    <div>
                        <x-input-label for="code" value="Código de 6 dígitos" />
                        <x-text-input id="code" name="code" type="text" inputmode="numeric" maxlength="6" class="mt-1 block w-full" required autofocus />
                        <x-input-error :messages="$errors->get('code')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-primary-button>Activar 2FA</x-primary-button>
                    </div>
                </form>
            @else
                <p class="mb-4 text-sm text-green-700 font-semibold">
                    La autenticación en dos pasos (2FA) está ACTIVA en tu cuenta.
                </p>

                <form method="POST" action="{{ route('2fa.disable') }}">
                    @csrf
                    @method('DELETE')
                    <div>
                        <x-input-label for="password" value="Confirma tu contraseña para desactivar 2FA" />
                        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-danger-button>Desactivar 2FA</x-danger-button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>