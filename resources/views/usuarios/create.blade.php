<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            👤 Registrar Nuevo Empleado
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-8 border-t-4 border-indigo-600">
                <form action="{{ route('usuarios.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="name" value="Nombre(s)" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" required autofocus />
                        </div>
                        <div>
                            <x-input-label for="apellido" value="Apellidos" />
                            <x-text-input id="apellido" class="block mt-1 w-full" type="text" name="apellido" required />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="email" value="Correo Electrónico" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" required />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="rol" value="Rol en la Papelería" />
                            <select name="rol" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500">
                                <option value="Vendedor">Vendedor</option>
                                <option value="Administrador">Administrador</option>
                            </select>
                        </div>
                        <div class="mt-4">
                            <x-input-label for="password" value="Contraseña Temporal" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="password_confirmation" value="Confirmar Contraseña" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
                        </div>
                    </div>

                    <div class="flex justify-end mt-8 border-t pt-6">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg transition">
                            ✅ Dar de Alta Empleado
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>