<!-- filepath: c:\laragon\www\eretribusi\resources\views\companies\index.blade.php -->
<x-layouts.app :title="'Daftar Perusahaan'">
    <section class="mb-4">
        <span class="text-xl font-semibold text-black">Data Perusahaan</span>
    </section>
    <div class="overflow-x-auto rounded-lg shadow">
        <table class="table w-full">
            <thead class="bg-gray-100 text-black">
                <tr>
                    <th>
                        <label>
                            <input type="checkbox" class="checkbox checkbox-success" />
                        </label>
                    </th>
                    <th>Nama Perusahaan</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- row 1 -->
                <tr>
                    <th>
                        <label>
                            <input type="checkbox" class="checkbox checkbox-success" />
                        </label>
                    </th>
                    <td class="text-black">
                        <div class="flex items-center gap-3">
                            <div class="avatar">
                                <div class="mask mask-squircle w-12 h-12">
                                    <img src="https://ui-avatars.com/api/?name=PT+Maju+Jaya" alt="PT Maju Jaya" />
                                </div>
                            </div>
                            <div>
                                <div class="font-bold">PT Maju Jaya</div>
                            </div>
                        </div>
                    </td>
                    <td class="text-black" >majujaya@email.com</td>
                    <td class="text-black" >08123456789</td>
                    <td class="text-black">Jl. Merdeka No. 1</td>
                    <td>
                        <a href="#" class="btn btn-xs btn-info">Detail</a>
                        <a href="#" class="btn btn-xs btn-warning">Edit</a>
                    </td>
                </tr>
                <!-- row 2 -->
                <tr>
                    <th>
                        <label>
                            <input type="checkbox" class="checkbox checkbox-success" />
                        </label>
                    </th>
                    <td class="text-black">
                        <div class="flex items-center gap-3">
                            <div class="avatar">
                                <div class="mask mask-squircle w-12 h-12">
                                    <img src="https://ui-avatars.com/api/?name=CV+Sukses+Selalu" alt="CV Sukses Selalu" />
                                </div>
                            </div>
                            <div>
                                <div class="font-bold">CV Sukses Selalu</div>
                            </div>
                        </div>
                    </td>
                    <td class="text-black">uksesselalu@email.com</td>
                    <td class="text-black">082233445566</td>
                    <td class="text-black">Jl. Sudirman No. 10</td>
                    <td>
                        <a href="#" class="btn btn-xs btn-info">Detail</a>
                        <a href="#" class="btn btn-xs btn-warning">Edit</a>
                    </td>
                </tr>
                <!-- row 3 -->
                <tr>
                    <th>
                        <label>
                            <input type="checkbox" class="checkbox checkbox-success" />
                        </label>
                    </th>
                    <td class="text-black">
                        <div class="flex items-center gap-3">
                            <div class="avatar">
                                <div class="mask mask-squircle w-12 h-12">
                                    <img src="https://ui-avatars.com/api/?name=UD+Makmur+Sentosa" alt="UD Makmur Sentosa" />
                                </div>
                            </div>
                            <div>
                                <div class="font-bold">UD Makmur Sentosa</div>
                            </div>
                        </div>
                    </td>
                    <td class="text-black">makmursentosa@email.com</td>
                    <td class="text-black">085677889900</td>
                    <td class="text-black">Jl. Diponegoro No. 5</td>
                    <td>
                        <a href="#" class="btn btn-xs btn-info">Detail</a>
                        <a href="#" class="btn btn-xs btn-warning">Edit</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        <!-- Paginate dummy -->
        <nav>
            <ul class="pagination flex space-x-2">
                <li><a href="#" class="px-3 py-1 bg-gray-200 rounded">1</a></li>
                <li><a href="#" class="px-3 py-1 hover:bg-gray-100 rounded">2</a></li>
                <li><a href="#" class="px-3 py-1 hover:bg-gray-100 rounded">3</a></li>
            </ul>
        </nav>
    </div>
</x-layouts.app>