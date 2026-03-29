<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'nisn',
        'nis',
        'nik',
        'full_name',
        'gender',
        'birth_place',
        'birth_date',
        'religion',
        'blood_type',
        'address',
        'dusun',
        'rt',
        'rw',
        'village',
        'district',
        'city',
        'province',
        'postal_code',
        'residence_type',
        'transportation',
        'phone',
        'parent_phone',
        'email',
        'family_card_number',
        'child_order',
        'father_name',
        'father_nik',
        'father_occupation',
        'mother_name',
        'mother_nik',
        'mother_occupation',
        'guardian_name',
        'guardian_nik',
        'guardian_occupation',
        'previous_school',
        'graduation_year',
        'entry_date',
        'status',
        'major',
        'assistance_type',
        'assistance_number',
        'classroom_id',
        'photo_path',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'entry_date' => 'date',
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
