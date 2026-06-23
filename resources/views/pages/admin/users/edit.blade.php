<!-- PB01 gakepake keknya-->
<h1>Edit Akun Pengguna</h1>

<a href="{{ route('admin.users.index') }}">Kembali</a>

@if ($errors->any())
    <ul style="color:red">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form action="{{ route('admin.users.update', $user) }}" method="POST">
    @csrf
    @method('PUT')

    <p>
        <label>Nama Lengkap</label><br>
        <input
            type="text"
            name="nama_lengkap"
            value="{{ old('nama_lengkap', $user->nama_lengkap) }}"
        >
    </p>

    <p>
        <label>Email</label><br>
        <input
            type="email"
            name="email"
            value="{{ old('email', $user->email) }}"
        >
    </p>

    <p>
        <label>No HP</label><br>
        <input
            type="text"
            name="no_hp"
            value="{{ old('no_hp', $user->no_hp) }}"
        >
    </p>

    <p>
        <label>Role</label><br>
        <select name="role_id">
            @foreach($roles as $role)
                <option
                    value="{{ $role->id }}"
                    @selected($role->id == $user->role_id)
                >
                    {{ $role->nama_role }}
                </option>
            @endforeach
        </select>
    </p>

    <p>
        <label>Password Baru (opsional)</label><br>
        <input type="password" name="password">
    </p>

    <p>
        <label>
            <input
                type="checkbox"
                name="status_aktif"
                value="1"
                {{ $user->status_aktif ? 'checked' : '' }}
            >
            Aktif
        </label>
    </p>

    <button type="submit">
        Update
    </button>
</form>