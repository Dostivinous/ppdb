@extends('app')

@section('content')
    <div class="min-h-screen text-gray-800 antialiased px-4 py-6 flex flex-col justify-center sm:py-12">
        <div class="relative py-3 sm:max-w-xl mx-auto text-center">
            <span class="text-2xl font-bold">Form Pendaftaran</span>

            <div class="relative mt-4 bg-white shadow-md sm:rounded-lg text-left">
                <div class="h-2 bg-indigo-400 rounded-t-md"></div>
                <div class="py-6 px-8">
                    @if (session('success'))
                        <div class="mb-4 text-green-600 font-semibold">
                            {{ session('success') }}
                        </div>
                    @endif
                    <form action="{{ route('pendaftaran.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Nama Peserta Didik -->
                            <div class="form-control col-span-2">
                                <label for="nama_peserta_didik" class="block font-semibold">Nama Calon Peserta Didik</label>
                                <input type="text" name="nama_peserta_didik" id="nama_peserta_didik"
                                    class="border w-full px-3 py-2 mt-2 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-600"
                                    placeholder="Masukkan nama lengkap" required>
                            </div>

                            <!-- Nama Ayah -->
                            <div class="form-control">
                                <label class="block font-semibold" for="nama_ayah">Nama Ayah</label>
                                <input type="text" id="nama_ayah" name="nama_ayah" placeholder="Masukkan nama ayah"
                                    class="border w-full px-3 py-2 mt-2 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-600"
                                    required>
                            </div>

                            <!-- Nama Ibu -->
                            <div class="form-control">
                                <label class="block font-semibold" for="nama_ibu">Nama Ibu</label>
                                <input type="text" id="nama_ibu" name="nama_ibu" placeholder="Masukkan nama ibu"
                                    class="border w-full px-3 py-2 mt-2 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-600"
                                    required>
                            </div>

                            <!-- Asal Sekolah -->
                            <div class="form-control col-span-2">
                                <label class="block font-semibold" for="asal_sekolah">Asal Sekolah</label>
                                <input type="text" id="asal_sekolah" name="asal_sekolah"
                                    placeholder="Masukkan asal sekolah"
                                    class="border w-full px-3 py-2 mt-2 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-600"
                                    required>
                            </div>

                            <!-- Nomor Telepon Peserta -->
                            <div class="form-control">
                                <label class="block font-semibold" for="nomor_telp_peserta">Nomor Telepon Peserta</label>
                                <input type="text" id="nomor_telp_peserta" name="nomor_telp_peserta"
                                    placeholder="Masukkan nomor telepon peserta"
                                    class="border w-full px-3 py-2 mt-2 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-600"
                                    required>
                            </div>

                            <!-- Nomor Telepon Ayah -->
                            <div class="form-control">
                                <label class="block font-semibold" for="nomor_telp_ayah">Nomor Telepon Ayah</label>
                                <input type="text" id="nomor_telp_ayah" name="nomor_telp_ayah"
                                    placeholder="Masukkan nomor telepon ayah"
                                    class="border w-full px-3 py-2 mt-2 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-600"
                                    required>
                            </div>

                            <!-- Nomor Telepon Ibu -->
                            <div class="form-control col-span-2">
                                <label class="block font-semibold" for="nomor_telp_ibu">Nomor Telepon Ibu</label>
                                <input type="text" id="nomor_telp_ibu" name="nomor_telp_ibu"
                                    placeholder="Masukkan nomor telepon ibu"
                                    class="border w-full px-3 py-2 mt-2 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-600"
                                    required>
                            </div>

                        </div>

                        <!-- Alamat Rumah -->
                        <div class="form-control">
                            <label class="block font-semibold" for="alamat_rumah">Alamat Rumah</label>
                            <textarea id="alamat_rumah" name="alamat_rumah"
                                class="border w-full px-3 py-2 mt-2 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-600"
                                placeholder="Masukkan alamat lengkap" rows="3" required></textarea>
                        </div>

                        <!-- Tanggal Pendaftaran -->
                        <div class="form-control">
                            <label class="block font-semibold" for="tanggal_pendaftaran">Tanggal Pendaftaran</label>
                            <input type="text" id="tanggal_pendaftaran" name="tanggal_pendaftaran"
                                value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}"
                                class="border w-full px-3 py-2 mt-2 rounded-md bg-gray-100 focus:outline-none" readonly>
                        </div>


                        <div class="flex justify-center">
                            <button type="submit"
                                class="bg-indigo-500 text-white py-2 px-6 rounded-lg hover:bg-indigo-600">Kirim</button>
                        </div>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection
