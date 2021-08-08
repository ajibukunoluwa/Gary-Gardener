<?php

namespace App\Models;

use App\Enums\ReminderUnit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reminder extends Model
{
    use HasFactory;

    protected $guards = [];

    public function toDoItem(): BelongsTo
    {
        return $this->belongsTo(ToDoItem::class);
    }

    public function getRemindAt()
    {
        $carbonMethod = ReminderUnit::CarbonMethod($this->unit);
        return $this->toDoItem->due_date->{$carbonMethod}($this->duration);
    }

    protected static function booted()
    {
        parent::booted();

        static::creating(function ($reminder) {
            $reminder->remind_at = $reminder->getRemindAt();
        });

        static::updating(function ($reminder) {
            $reminder->remind_at = $reminder->getRemindAt();
        });
    }
}
