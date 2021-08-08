<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ToDoItem extends Model
{
    use HasFactory;

    protected $guarded = ['created_by'];

    protected $casts = [
        'due_date' => 'datetime'
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(Reminder::class);
    }

    public function markAsDone(): void
    {
        $this->update(['completed_at' => now()]);
    }

    public function getAttachmentFolderPath(): string
    {
        return "attachments/to_do_items/{$this->id}";
    }

    // Scopes
    public function scopeWhereDone($query)
    {
        return $query->whereNotNull('completed_at');
    }

    public function scopeWhereNotDone($query)
    {
        return $query->whereNull('completed_at');
    }
}
