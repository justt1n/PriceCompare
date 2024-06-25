<x-app-layout>
    <section class="pl-80 pr-64 py-16">
        <div class="overflow-x-auto">
            @if(Session::has('message'))
            <p class="alert {{ Session::get('alert-type') }}">{{ Session::get('message') }}</p>
            @endif
            <div class="w-full py-2 border rounded-xl bg-base-300 mb-4">
                <h1 class="text-3xl my-4 px-4">Admin Page</h1>
            </div>
            <table class="table">
                <!-- head -->
                <thead>
                    <tr class="text-lg">
                        <th><button class="btn bg-blue-300 hover:bg-red-500" onclick="my_modal_add.showModal()"> Add
                                User</button>
                            <dialog id="my_modal_add" class="modal">
                                <div class="modal-box">
                                    <x-guest-layout>
                                        <form method="POST" action="{{ route('admin.register') }}">
                                            @csrf

                                            <!-- Name -->
                                            <div>
                                                <x-input-label for="name" :value="__('Name')" />
                                                <x-text-input id="name" class="block mt-1 w-full" type="text"
                                                    name="name" :value="old('name')" required autofocus
                                                    autocomplete="name" />
                                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                            </div>

                                            <!-- Email Address -->
                                            <div class="mt-4">
                                                <x-input-label for="email" :value="__('Email')" />
                                                <x-text-input id="email" class="block mt-1 w-full" type="email"
                                                    name="email" :value="old('email')" required
                                                    autocomplete="username" />
                                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                            </div>
                                            <!-- Role -->
                                            <div class="mt-4">
                                                <x-input-label for="role" :value="__('Role')" />
                                                <select id="role" class="block mt-1 w-full" name="role" required
                                                    autofocus autocomplete="role">
                                                    <option value="admin" {{ 'role'=='admin' ? 'selected' : '' }}>
                                                        Admin</option>
                                                    <option value="superAdmin" {{ 'role'=='superAdmin'
                                                        ? 'selected' : '' }}>Super Admin</option>
                                                </select>
                                                <x-input-error :messages="$errors->get('role')" class="mt-2" />
                                            </div>
                                            <!-- Password -->
                                            <div class="mt-4">
                                                <x-input-label for="password" :value="__('Password')" />

                                                <x-text-input id="password" class="block mt-1 w-full" type="password"
                                                    name="password" required autocomplete="new-password" />

                                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                            </div>

                                            <!-- Confirm Password -->
                                            <div class="mt-4">
                                                <x-input-label for="password_confirmation"
                                                    :value="__('Confirm Password')" />

                                                <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                                    type="password" name="password_confirmation" required
                                                    autocomplete="new-password" />

                                                <x-input-error :messages="$errors->get('password_confirmation')"
                                                    class="mt-2" />
                                            </div>

                                            <div class="flex items-center justify-end mt-4">
                                                <x-primary-button class="ms-4">
                                                    {{ __('Register') }}
                                                </x-primary-button>
                                            </div>
                                        </form>
                                    </x-guest-layout>

                                </div>
                                <form method="dialog" class="modal-backdrop">
                                    <button>close</button>
                                </form>
                            </dialog>
                        </th>
                        <th>Name</th>
                        <th>Email</th>
                        @if(auth()->user()->role == 'superAdmin')
                        <th></th>
                        <th></th>
                        @endif
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- body -->
                    @foreach ($users as $user)
                    <tr class="hover">
                        <th>{{ $loop->index + 1}}</th>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>

                        @if(auth()->user()->role == 'superAdmin' && auth()->user()->id != $user->id)
                        <td>
                            <form action="{{ route('admin.updateRole', $user->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <select
                                    class="dropdown dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52"
                                    name="role" onchange="this.form.submit()">
                                    <li>
                                        <option value="admin" {{ $user->role == "admin" ? 'selected' : '' }}>Admin</option>
                                    </li>
                                    <li>
                                        <option value="superAdmin" {{ $user->role == "superAdmin" ? 'selected' : '' }}>Super
                                            Admin</option>
                                    </li>
                                </select>
                            </form>
                        </td>
                        <td>
                            <button class="btn btn-error" onclick="my_modal_{{$user->id}}.showModal()">Delete</button>
                            <dialog id="my_modal_{{$user->id}}" class="modal modal-bottom sm:modal-middle ">
                                <div class="modal-box ">
                                    <h3 class="font-bold text-lg">Delete User</h3>
                                    <p class="py-4">Do you want to delete {{$user->name}}?</p>
                                    <div class="modal-action">
                                        <form action="{{ route('admin.delete', $user->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-error" type="submit">Yes</button>
                                        </form>
                                        <form action="">
                                            <button class="btn btn-error" type="submit">Close</button>
                                        </form>
                                    </div>
                                </div>
                            </dialog>
                        </td>
                        @else
                        <td></td>
                        <td></td>
                        @endif
                        <td id='{{$user->id}}'>
                            @if ($user->role == "superAdmin")
                            Super Admin
                            @else
                            Admin
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <script>
        // Your JavaScript code goes here
        function myFunction(id) {
            fetch('/users/' + id + '/role', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        role: 1 // Replace with the actual role
                    })
                })
                .then(response => response.json())
                .then(data => console.log(data));
        }
        </script>
    </section>
</x-app-layout>