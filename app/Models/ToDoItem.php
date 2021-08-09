<?php

namespace App\Models;

use App\Enums\ReminderUnit;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ToDoItem extends Model
{
    use HasFactory;

    protected $guarded = ['created_by'];

    protected $casts = [
        'due_date' => 'date'
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(Reminder::class);
    }

    public function markAsComplete(): void
    {
        $this->update(['completed_at' => now()]);
    }

    public function allowsReminder(): bool
    {
        return ! empty($this->due_date);
    }

    public function isComplete(): bool
    {
        return ! empty($this->completed_at);
    }

    public function isNotComplete(): bool
    {
        return ! $this->isComplete();
    }

    public function generateAttachmentPath(): string
    {
        return "attachments/to_do_items/{$this->id}";
        // return "public/attachments/to_do_items/{$this->id}";
    }

    public function generateAttachmentName(string $extension): string
    {
        return Str::slug($this->title) . '-' . Str::random(10) . ".{$extension}";
    }

    public function getAttachmentUrlAttribute()
    {
        // return public_path($this->attachment_path);
        return url($this->attachment_path);
        // return asset($this->attachment_path);
    }

    public function generateReminderDate(string $unit, int $duration)
    {
        $carbonMethod = ReminderUnit::CarbonMethod($unit);
        return optional($this->due_date)->{$carbonMethod}($duration);
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
