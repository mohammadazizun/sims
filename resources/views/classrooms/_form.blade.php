<div class="row">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="form-group">
            <label for="name">Nama Kelas</label>
            <input id="name" name="name" class="form-control" required value="{{ old('name', $classroom->name ?? '') }}">
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="form-group">
            <label for="grade">Tingkat</label>
            <input id="grade" name="grade" class="form-control" value="{{ old('grade', $classroom->grade ?? '') }}">
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="form-group">
            <label for="major">Jurusan</label>
            <input id="major" name="major" class="form-control" value="{{ old('major', $classroom->major ?? '') }}">
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="form-group">
            <label for="academic_year">Tahun Ajaran</label>
            <input id="academic_year" name="academic_year" class="form-control" value="{{ old('academic_year', $classroom->academic_year ?? '') }}">
        </div>
    </div>
</div>
<div class="form-group">
    <label for="description">Deskripsi</label>
    <textarea id="description" name="description" class="form-control" rows="3">{{ old('description', $classroom->description ?? '') }}</textarea>
</div>
