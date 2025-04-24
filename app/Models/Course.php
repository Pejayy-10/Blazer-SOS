<?php namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; // If using Majors

class Course extends Model {
    use HasFactory;
    protected $fillable = ['college_id', 'name', 'abbreviation'];

    public function college(): BelongsTo {
        return $this->belongsTo(College::class);
    }

     public function majors(): HasMany {
        return $this->hasMany(Major::class);
    }

    // Optional: Relationship back to profiles that selected this course
    public function yearbookProfiles(): HasMany {
        return $this->hasMany(YearbookProfile::class);
    }
}