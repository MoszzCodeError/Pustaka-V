@extends('layouts.admin')

@section('title', 'Kelola Pengguna Sistem')

@section('content')
<div x-data="{ 
    isEdit: false,
    formTitle: 'Tambah Pengguna Baru',
    formAction: '{{ route('admin.users.store') }}',
    user: { id: '', name: '', email: '', role: 'siswa', nis_nip: '', kelas: '' },
    filterRole: 'all',
    filterKelas: 'all',
    searchQuery: '',
    
    resetForm() {
        this.isEdit = false;
        this.formTitle = 'Tambah Pengguna Baru';
        this.formAction = '{{ route('admin.users.store') }}';
        this.user = { id: '', name: '', email: '', role: 'siswa', nis_nip: '', kelas: '' };
    },
    
    editUser(u) {
        this.isEdit = true;
        this.formTitle = 'Edit Data Pengguna';
        this.formAction = '{{ url('/admin/users') }}/' + u.id;
        this.user = { id: u.id, name: u.name, email: u.email, role: u.role, nis_nip: u.nis_nip || '', kelas: u.kelas || '' };
    }
}">

    <!-- ALERT NOTIFIKASI SUCCESS -->
    @if(session('success'))
        <div class="mb-6 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 p-4 rounded-xl text-xs flex items-center justify-between">
            <span>✨ {{ session('success') }}</span>
        </div>
    @endif

    <!-- ALERT NOTIFIKASI ERROR SYSTEM -->
    @if(session('error'))
        <div class="mb-6 bg-red-500/10 border border-red-500/30 text-red-400 p-4 rounded-xl text-xs flex items-center justify-between">
            <span>⚠️ {{ session('error') }}</span>
        </div>
    @endif

    <!-- ALERT NOTIFIKASI ERROR VALIDASI FORM -->
    @if($errors->any())
        <div class="mb-6 bg-red-500/10 border border-red-500/30 text-red-400 p-4 rounded-xl text-xs space-y-1">
            @foreach($errors->all() as $err)
                <div>⚠️ {{ $err }}</div>
            @endforeach
        </div>
    @endif

    <!-- GRID DUA KOLOM SIDE-BY-SIDE -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- KOLOM KIRI: DAFTAR USER & FILTER (SPAN 8) -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- BARIS FILTER & PENCARIAN -->
            <div class="bg-[#111827] p-4 rounded-2xl border border-gray-800 flex flex-wrap gap-4 items-center justify-between">
                
                <!-- FILTER ROLE -->
                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold text-gray-400">Role:</span>
                    <select x-model="filterRole" class="bg-[#0B0F19] border border-gray-800 text-xs text-white rounded-xl px-3 py-2 focus:outline-none focus:border-[#FF5500]">
                        <option value="all">Semua Role</option>
                        <option value="siswa">Siswa</option>
                        <option value="admin">Admin / Petugas</option>
                        <option value="super_admin">Super Admin</option>
                    </select>
                </div>


                <!-- PENCARIAN NAMA / NIS -->
                <div class="flex-1 min-w-[200px]">
                    <input type="text" x-model="searchQuery" placeholder="Cari Nama, Email, NIP/NIS..." class="w-full bg-[#0B0F19] border border-gray-800 text-xs text-white rounded-xl px-3 py-2 focus:outline-none focus:border-[#FF5500]">
                </div>
            </div>

            <!-- TABEL DAFTAR PENGGUNA -->
            <div class="bg-[#111827] rounded-2xl border border-gray-800 overflow-hidden shadow-xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-gray-900/80 text-gray-400 border-b border-gray-800 uppercase text-[10px] tracking-wider">
                            <tr>
                                <th class="p-4">Pengguna</th>
                                <th class="p-4">NIS / NIP</th>
                                <th class="p-4">Kelas</th>
                                <th class="p-4">Role</th>
                                <th class="p-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800/60 text-gray-300">
                            @forelse($users as $u)
                                <tr class="hover:bg-gray-800/30 transition"
                                    x-show="(filterRole === 'all' || filterRole === '{{ $u->role }}') && 
                                            (filterKelas === 'all' || filterKelas === '{{ $u->kelas }}') &&
                                            (searchQuery === '' || '{{ strtolower(addslashes($u->name)) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower(addslashes($u->email)) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower(addslashes($u->nis_nip ?? '')) }}'.includes(searchQuery.toLowerCase()))">
                                    <td class="p-4">
                                        <div class="font-bold text-white">{{ $u->name }}</div>
                                        <div class="text-[10px] text-gray-500">{{ $u->email }}</div>
                                    </td>
                                    <td class="p-4 font-mono text-gray-400">{{ $u->nis_nip ?? '-' }}</td>
                                    <td class="p-4 font-medium">{{ $u->kelas ?? '-' }}</td>
                                    <td class="p-4">
                                        @if($u->role === 'super_admin')
                                            <span class="px-2.5 py-1 bg-purple-500/10 text-purple-400 border border-purple-500/20 rounded-full text-[10px] font-bold">Super Admin</span>
                                        @elseif($u->role === 'admin')
                                            <span class="px-2.5 py-1 bg-blue-500/10 text-blue-400 border border-blue-500/20 rounded-full text-[10px] font-bold">Admin</span>
                                        @else
                                            <span class="px-2.5 py-1 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-full text-[10px] font-bold">Siswa</span>
                                        @endif
                                    </td>
                                    <td class="p-4 flex items-center justify-center gap-2">
                                        <button @click="editUser({{ json_encode($u) }})" class="px-3 py-1.5 bg-[#FF5500]/10 border border-[#FF5500]/30 hover:bg-[#FF5500] text-[#FF5500] hover:text-white rounded-lg text-[11px] font-semibold transition">
                                            Edit
                                        </button>
                                        @if($u->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1.5 bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/30 rounded-lg text-[11px] font-semibold transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-6 text-center text-gray-500">Belum ada data pengguna.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">{{ $users->links() }}</div>
        </div>

        <!-- KOLOM KANAN: FORM TAMBAH / EDIT AKUN (SPAN 4) -->
        <div class="lg:col-span-4 bg-[#111827] p-6 rounded-3xl border border-gray-800 shadow-xl space-y-4 sticky top-6">
            <div class="flex items-center justify-between border-b border-gray-800 pb-4">
                <h3 class="text-base font-bold text-white" x-text="formTitle"></h3>
                <button type="button" x-show="isEdit" @click="resetForm()" class="text-[11px] text-[#FF5500] hover:underline font-semibold">
                    + Form Baru
                </button>
            </div>

            <form :action="formAction" method="POST" class="space-y-4 text-xs">
                @csrf
                <template x-if="isEdit">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="text-gray-400 block mb-1 font-semibold">Nama Lengkap</label>
                    <input type="text" name="name" x-model="user.name" placeholder="Masukkan nama..." required class="w-full bg-[#0B0F19] border border-gray-800 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#FF5500]">
                </div>

                <div>
                    <label class="text-gray-400 block mb-1 font-semibold">Email</label>
                    <input type="email" name="email" x-model="user.email" placeholder="contoh@smkn5surakarta.sch.id" required class="w-full bg-[#0B0F19] border border-gray-800 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#FF5500]">
                </div>

                <div>
                    <label class="text-gray-400 block mb-1 font-semibold">
                        Password <span x-show="isEdit" class="text-[10px] text-gray-500 font-normal">(Kosongkan jika tidak diganti)</span>
                    </label>
                    <input type="password" name="password" :required="!isEdit" placeholder="••••••••" class="w-full bg-[#0B0F19] border border-gray-800 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#FF5500]">
                </div>

                <div>
                    <label class="text-gray-400 block mb-1 font-semibold">Role / Hak Akses</label>
                    <select name="role" x-model="user.role" required class="w-full bg-[#0B0F19] border border-gray-800 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#FF5500]">
                        <option value="siswa">Siswa</option>
                        <option value="admin">Admin / Petugas</option>
                        <option value="super_admin">Super Admin</option>
                    </select>
                </div>

                <div>
                    <label class="text-gray-400 block mb-1 font-semibold">NIS / NIP</label>
                    <input type="text" name="nis_nip" x-model="user.nis_nip" placeholder="Nomor NIS atau NIP..." class="w-full bg-[#0B0F19] border border-gray-800 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#FF5500]">
                </div>

                <div>
                    <label class="text-gray-400 block mb-1 font-semibold">Kelas / Unit Work</label>
                    <input type="text" name="kelas" x-model="user.kelas" placeholder="Contoh: XII PPLG 1" class="w-full bg-[#0B0F19] border border-gray-800 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#FF5500]">
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="button" x-show="isEdit" @click="resetForm()" class="w-1/2 py-3 bg-gray-800 hover:bg-gray-700 text-white rounded-xl font-semibold transition">
                        Batal
                    </button>
                    <button type="submit" :class="isEdit ? 'w-1/2' : 'w-full'" class="py-3 bg-[#FF5500] hover:bg-orange-600 text-white rounded-xl font-bold transition shadow-lg shadow-orange-500/20">
                        <span x-text="isEdit ? 'Update Akun' : 'Simpan Akun Baru'"></span>
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection