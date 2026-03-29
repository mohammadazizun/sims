<div class="row">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="form-group">
            <label for="nisn">NISN</label>
            <input id="nisn" name="nisn" class="form-control" value="{{ old('nisn', $student->nisn ?? '') }}">
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="form-group">
            <label for="nis">NIS</label>
            <input id="nis" name="nis" class="form-control" value="{{ old('nis', $student->nis ?? '') }}">
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="form-group">
            <label for="nik">NIK</label>
            <input id="nik" name="nik" class="form-control" value="{{ old('nik', $student->nik ?? '') }}">
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="form-group">
            <label for="full_name">Nama Lengkap</label>
            <input id="full_name" name="full_name" required class="form-control" value="{{ old('full_name', $student->full_name ?? '') }}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="form-group">
            <label for="classroom_id">Kelas</label>
            <select id="classroom_id" name="classroom_id" class="form-control">
                <option value="">Pilih kelas</option>
                @foreach($classrooms as $classroom)
                    <option value="{{ $classroom->id }}" {{ old('classroom_id', $student->classroom_id ?? '') == $classroom->id ? 'selected' : '' }}>{{ $classroom->name }}{{ $classroom->grade ? ' - '.$classroom->grade : '' }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="form-group">
            <label for="major">Jurusan</label>
            <input id="major" name="major" class="form-control" value="{{ old('major', $student->major ?? '') }}" placeholder="Masukkan jurusan siswa">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 mb-3">
        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status" class="form-control">
                <option value="">Pilih status</option>
                <option value="Aktif" {{ old('status', $student->status ?? '') === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="Mutasi" {{ old('status', $student->status ?? '') === 'Mutasi' ? 'selected' : '' }}>Mutasi</option>
                <option value="Lulus" {{ old('status', $student->status ?? '') === 'Lulus' ? 'selected' : '' }}>Lulus</option>
            </select>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 mb-3">
        <div class="form-group">
            <label for="gender">Jenis Kelamin</label>
            <select id="gender" name="gender" class="form-control">
                <option value="">Pilih</option>
                <option value="L" {{ old('gender', $student->gender ?? '') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                <option value="P" {{ old('gender', $student->gender ?? '') === 'P' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="form-group">
            <label for="birth_place">Tempat Lahir</label>
            <input id="birth_place" name="birth_place" class="form-control" value="{{ old('birth_place', $student->birth_place ?? '') }}">
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="form-group">
            <label for="birth_date">Tanggal Lahir</label>
            <input type="date" id="birth_date" name="birth_date" class="form-control" value="{{ old('birth_date', optional($student->birth_date)->format('Y-m-d') ?? '') }}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 mb-3">
        <div class="form-group">
            <label for="address">Alamat</label>
            <textarea id="address" name="address" class="form-control" rows="3">{{ old('address', $student->address ?? '') }}</textarea>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-md-12 mb-3">
        <div class="form-group">
            <label for="photo">Foto Siswa</label>
            <input type="file" id="photo" name="photo" class="form-control-file">
            <small class="form-text text-muted">Unggah foto siswa (jpeg, png, jpg, gif).</small>
        </div>
    </div>
    @if(!empty($student->photo_path))
        <div class="col-lg-6 col-md-12 mb-3">
            <div class="form-group">
                <label>Preview Foto Saat Ini</label>
                <div>
                    <img src="{{ Storage::url($student->photo_path) }}" alt="Foto {{ $student->full_name }}" class="img-fluid rounded" style="max-height: 180px;">
                </div>
            </div>
        </div>
    @endif
</div>

<div class="row">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="form-group">
            <label for="dusun">Dusun</label>
            <input id="dusun" name="dusun" class="form-control" value="{{ old('dusun', $student->dusun ?? '') }}">
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="form-group">
            <label for="residence_type">Jenis Tinggal</label>
            <input id="residence_type" name="residence_type" class="form-control" value="{{ old('residence_type', $student->residence_type ?? '') }}">
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="form-group">
            <label for="transportation">Alat Transportasi</label>
            <input id="transportation" name="transportation" class="form-control" value="{{ old('transportation', $student->transportation ?? '') }}">
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="form-group">
            <label for="parent_phone">HP Orang Tua</label>
            <input id="parent_phone" name="parent_phone" class="form-control" value="{{ old('parent_phone', $student->parent_phone ?? '') }}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="form-group">
            <label for="rt">RT</label>
            <input id="rt" name="rt" class="form-control" value="{{ old('rt', $student->rt ?? '') }}">
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="form-group">
            <label for="rw">RW</label>
            <input id="rw" name="rw" class="form-control" value="{{ old('rw', $student->rw ?? '') }}">
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="form-group">
            <label for="village">Kelurahan</label>
            <input id="village" name="village" class="form-control" value="{{ old('village', $student->village ?? '') }}">
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="form-group">
            <label for="district">Kecamatan</label>
            <input id="district" name="district" class="form-control" value="{{ old('district', $student->district ?? '') }}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="form-group">
            <label for="city">Kabupaten/Kota</label>
            <input id="city" name="city" class="form-control" value="{{ old('city', $student->city ?? '') }}">
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="form-group">
            <label for="province">Provinsi</label>
            <input id="province" name="province" class="form-control" value="{{ old('province', $student->province ?? '') }}">
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="form-group">
            <label for="postal_code">Kode Pos</label>
            <input id="postal_code" name="postal_code" class="form-control" value="{{ old('postal_code', $student->postal_code ?? '') }}">
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="form-group">
            <label for="phone">Telepon / HP</label>
            <input id="phone" name="phone" class="form-control" value="{{ old('phone', $student->phone ?? '') }}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" name="email" class="form-control" value="{{ old('email', $student->email ?? '') }}">
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="form-group">
            <label for="family_card_number">No. KK</label>
            <input id="family_card_number" name="family_card_number" class="form-control" value="{{ old('family_card_number', $student->family_card_number ?? '') }}">
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="form-group">
            <label for="child_order">Anak Ke-</label>
            <input id="child_order" name="child_order" class="form-control" value="{{ old('child_order', $student->child_order ?? '') }}">
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="form-group">
            <label for="blood_type">Golongan Darah</label>
            <input id="blood_type" name="blood_type" class="form-control" value="{{ old('blood_type', $student->blood_type ?? '') }}">
        </div>
    </div>
</div>

<div class="card card-outline card-info">
    <div class="card-header">
        <h3 class="card-title">Data Orang Tua / Wali</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="form-group">
                    <label for="father_name">Nama Ayah</label>
                    <input id="father_name" name="father_name" class="form-control" value="{{ old('father_name', $student->father_name ?? '') }}">
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="form-group">
                    <label for="father_nik">NIK Ayah</label>
                    <input id="father_nik" name="father_nik" class="form-control" value="{{ old('father_nik', $student->father_nik ?? '') }}">
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="form-group">
                    <label for="mother_name">Nama Ibu</label>
                    <input id="mother_name" name="mother_name" class="form-control" value="{{ old('mother_name', $student->mother_name ?? '') }}">
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="form-group">
                    <label for="mother_nik">NIK Ibu</label>
                    <input id="mother_nik" name="mother_nik" class="form-control" value="{{ old('mother_nik', $student->mother_nik ?? '') }}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="form-group">
                    <label for="father_occupation">Pekerjaan Ayah</label>
                    <input id="father_occupation" name="father_occupation" class="form-control" value="{{ old('father_occupation', $student->father_occupation ?? '') }}">
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="form-group">
                    <label for="mother_occupation">Pekerjaan Ibu</label>
                    <input id="mother_occupation" name="mother_occupation" class="form-control" value="{{ old('mother_occupation', $student->mother_occupation ?? '') }}">
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="form-group">
                    <label for="guardian_name">Nama Wali</label>
                    <input id="guardian_name" name="guardian_name" class="form-control" value="{{ old('guardian_name', $student->guardian_name ?? '') }}">
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="form-group">
                    <label for="guardian_nik">NIK Wali</label>
                    <input id="guardian_nik" name="guardian_nik" class="form-control" value="{{ old('guardian_nik', $student->guardian_nik ?? '') }}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-12 mb-3">
                <div class="form-group">
                    <label for="guardian_occupation">Pekerjaan Wali</label>
                    <input id="guardian_occupation" name="guardian_occupation" class="form-control" value="{{ old('guardian_occupation', $student->guardian_occupation ?? '') }}">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card card-outline card-info">
    <div class="card-header">
        <h3 class="card-title">Data Sekolah</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="form-group">
                    <label for="previous_school">Sekolah Asal</label>
                    <input id="previous_school" name="previous_school" class="form-control" value="{{ old('previous_school', $student->previous_school ?? '') }}">
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="form-group">
                    <label for="graduation_year">Tahun Lulus</label>
                    <input id="graduation_year" name="graduation_year" class="form-control" value="{{ old('graduation_year', $student->graduation_year ?? '') }}">
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="form-group">
                    <label for="entry_date">Tanggal Masuk</label>
                    <input type="date" id="entry_date" name="entry_date" class="form-control" value="{{ old('entry_date', optional($student->entry_date)->format('Y-m-d') ?? '') }}">
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="form-group">
                    <label for="status">Status Siswa</label>
                    <input id="status" name="status" class="form-control" value="{{ old('status', $student->status ?? '') }}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-12 mb-3">
                <div class="form-group">
                    <label for="assistance_type">Jenis Bantuan</label>
                    <input id="assistance_type" name="assistance_type" class="form-control" value="{{ old('assistance_type', $student->assistance_type ?? '') }}">
                </div>
            </div>
            <div class="col-lg-6 col-md-12 mb-3">
                <div class="form-group">
                    <label for="assistance_number">Nomor Bantuan</label>
                    <input id="assistance_number" name="assistance_number" class="form-control" value="{{ old('assistance_number', $student->assistance_number ?? '') }}">
                </div>
            </div>
        </div>
    </div>
</div>
