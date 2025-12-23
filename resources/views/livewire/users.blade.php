<div class="max-w-md mx-auto space-y-4">

    <h2 class="text-xl font-bold">Create User</h2>

    @if (session()->has('success'))
        <div class="text-green-600">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="save" class="max-w-md mx-auto space-y-4">

        <!-- Name -->
        <div>
            <input
                type="text"
                wire:model.live="name"
                placeholder="Name"
                class="w-full border rounded p-2"
            >
            @error('name')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <input
                type="email"
                wire:model.live="email"
                placeholder="Email"
                class="w-full border rounded p-2"
            >
            @error('email')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <input
                type="password"
                wire:model.live="password"
                placeholder="Password"
                class="w-full border rounded p-2"
            >
            @error('password')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <input
                type="password"
                wire:model.live="password_confirmation"
                placeholder="Confirm Password"
                class="w-full border rounded p-2"
            >
        </div>

        <!-- Avatar -->
        <div>
            <input
                type="file"
                wire:model.live="avatar"
                accept="image/png,image/jpg,image/jpeg"
            >
            @error('avatar')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Preview Avatar -->
        @if ($avatar)
            <div class="space-y-1">
                <p class="text-sm text-gray-500">Preview Avatar:</p>
                <img
                    src="{{ $avatar->temporaryUrl() }}"
                    style="width:96px;height:96px;object-fit:cover;border-radius:50%;border:1px solid #ccc;"
                >
            </div>
        @endif

        <!-- Submit -->
        <button
            type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded w-full"
        >
            Save
        </button>

    </form>

    <hr>

    <ul>
        @foreach ($users as $user)
        <li>{{ $user->name }}</li>
        
        @endforeach
    </ul>

</div>
