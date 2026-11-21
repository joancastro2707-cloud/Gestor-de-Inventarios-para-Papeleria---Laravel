<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Ingresa el código de 6 dígitos generado por tu aplicación autenticadora para completar el inicio de sesión.
    </div>

    <form method="POST" action="{{ route('2fa.challenge.store') }}">
        @csrf
        <div>
            <x-input-label for="code" value="Código de verificación (TOTP)" />
            <x-text-input id="code" class="block mt-1 w-full" type="text" name="code" inputmode="numeric" maxlength="6" required autofocus />
            <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>Verificar e ingresar</x-primary-button>
        </div>
    </form>
</x-guest-layout>