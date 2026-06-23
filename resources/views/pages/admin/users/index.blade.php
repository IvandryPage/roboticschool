<!-- PB01 gakepake keknya-->
<h1>Manajemen Akun Pengguna</h1>

@if (session('success'))
    <p style="color: green">{{ session('success') }}</p>
@endif

<a href="{{ route('admin.users.create') }}">Tambah Akun</a>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>No HP</th>
            <th>Role</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($users as $user)
            <tr>
                <td>{{ $user->nama_lengkap }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->no_hp ?? '-' }}</td>
                <td>{{ $user->role->nama_role ?? '-' }}</td>
                <td>{{ $user->status_aktif ? 'Aktif' : 'Nonaktif' }}</td>
                <td>
                    <a href="{{ route('admin.users.edit', $user) }}">Edit</a>

                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Nonaktifkan akun ini?')">
                            Nonaktifkan
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6">Belum ada data pengguna.</td>
            </tr>
        @endforelse
    </tbody>
</table>