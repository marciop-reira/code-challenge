<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Task extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'completed_at'
    ];

    /**
     * @var string[]
     */
    protected $appends = [
        'is_completed'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'completed_at' => 'datetime'
    ];

    public function __construct(array $attributes = [], bool $withId = false)
    {
        parent::__construct($attributes);

        if ($withId) {
            $this->id = Str::orderedUuid()->toString();
        }
    }

    /**
     * @return Attribute
     */
    protected function isCompleted(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => !is_null($this->completed_at),
        );
    }
}
