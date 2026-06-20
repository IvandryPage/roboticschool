<!-- PB01 -->
<h1>Tambah Akun Pengguna</h1>

<a href="{{ route('admin.users.index') }}">Kembali</a>

@if ($errors->any())
    <ul style="color: red">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form action="{{ route('admin.users.store') }}" method="POST">
    @csrf

    <p>
        <label>Nama Lengkap</label><br>
        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}">
    </p>

    <p>
        <label>Email</label><br>
        <input type="email" name="email" value="{{ old('email') }}">
    </p>

    <p>
        <label>No HP</label><br>
        <input type="text" name="no_hp" value="{{ old('no_hp') }}">
    </p>

    <p>
        <label>Role</label><br>
        <select name="role_id">
            <option value="">-- Pilih Role --</option>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}">{{ $role->nama_role }}</option>
            @endforeach
        </select>
    </p>

    <p>
        <label>Password</label><br>
        <input type="password" name="password">
    </p>

    <p>
        <label>
            <input type="checkbox" name="status_aktif" value="1" checked>
            Aktif
        </label>
    </p>

    <button type="submit">Simpan</button>
</form>