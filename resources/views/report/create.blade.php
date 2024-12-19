    @extends('templates.app')

    @section('content')
        <div class="container">
            <h1>Buat Laporan Baru</h1>
            <form method="POST" action="{{ route('report.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="province" class="form-label">Provinsi</label>
                    <select name="province" id="province" class="form-control" required>
                        <option value="">-- Pilih Provinsi --</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="regency" class="form-label">Kabupaten/Kota</label>
                    <select name="regency" id="regency" class="form-control" required>
                        <option value="">-- Pilih Kabupaten/Kota --</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="subdistrict" class="form-label">Kecamatan</label>
                    <select name="subdistrict" id="subdistrict" class="form-control" required>
                        <option value="">-- Pilih Kecamatan --</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="village" class="form-label">Desa/Kelurahan</label>
                    <select name="village" id="village" class="form-control" required>
                        <option value="">-- Pilih Desa/Kelurahan --</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Type</label>
                    <select name="type" id="type" class="form-control" required>
                        <option value="">-- Type --</option>
                        <option value="Kejahatan">-- Kejahatan --</option>
                        <option value="Pembangunan">-- Pembangunan --</option>
                        <option value="Sosial">-- Sosial --</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea name="description" id="description" class="form-control" rows="3" required>{{ old('description') }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="image" class="form-label text-muted fw-medium">Upload Image</label>
                    <input type="file" class="form-control form-control-lg border-0 bg-light" name="image"
                        id="image" accept="image/*"> <!-- Hanya menerima file gambar -->
                </div>

                <div class="mb-3">
                    <input type="checkbox" name="statement" id="statement" value="true" required>
                    <label for="statement">Dengan ini menyatakan setuju untuk upload</label>
                </div>
                

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    @endsection
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    @push('script')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
        const provinceSelect = document.getElementById('province');
        const regencySelect = document.getElementById('regency');
        const subdistrictSelect = document.getElementById('subdistrict');
        const villageSelect = document.getElementById('village');

        // Fetch Provinsi
        fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')
            .then(response => response.json())
            .then(data => {
                data.forEach(province => {
                    const option = document.createElement('option');
                    option.value = province.id;
                    option.textContent = province.name;
                    provinceSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching provinces:', error));

        // Fetch Kabupaten/Kota berdasarkan Provinsi
        provinceSelect.addEventListener('change', function() {
            const provinceId = this.value;
            regencySelect.innerHTML = '<option value="">-- Pilih Kabupaten/Kota --</option>';
            subdistrictSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
            villageSelect.innerHTML = '<option value="">-- Pilih Desa/Kelurahan --</option>';

            if (provinceId) {
                fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provinceId}.json`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(regency => {
                            const option = document.createElement('option');
                            option.value = regency.id;
                            option.textContent = regency.name;
                            regencySelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error fetching regencies:', error));
            }
        });

        // Fetch Kecamatan berdasarkan Kabupaten
        regencySelect.addEventListener('change', function() {
            const regencyId = this.value;
            subdistrictSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
            villageSelect.innerHTML = '<option value="">-- Pilih Desa/Kelurahan --</option>';

            if (regencyId) {
                fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${regencyId}.json`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(subdistrict => {
                            const option = document.createElement('option');
                            option.value = subdistrict.id;
                            option.textContent = subdistrict.name;
                            subdistrictSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error fetching subdistricts:', error));
            }
        });

        // Fetch Desa berdasarkan Kecamatan
        subdistrictSelect.addEventListener('change', function() {
            const subdistrictId = this.value;
            villageSelect.innerHTML = '<option value="">-- Pilih Desa/Kelurahan --</option>';

            if (subdistrictId) {
                fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${subdistrictId}.json`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(village => {
                            const option = document.createElement('option');
                            option.value = village.id;
                            option.textContent = village.name;
                            villageSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error fetching villages:', error));
            }
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("form");
    const checkbox = document.getElementById("statement");

    form.addEventListener("submit", function(event) {
        if (!checkbox.checked) {
            event.preventDefault();
            alert("Anda harus menyetujui persyaratan sebelum mengirim.");
        }
    });
});


        </script>
    @endpush