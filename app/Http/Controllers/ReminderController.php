<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Models\ToDoItem;
use Illuminate\Http\Request;
use App\Http\Requests\StoreReminderRequest;

class ReminderController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\StoreReminderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreReminderRequest $request, ToDoItem $toDoItem)
    {
        if ($toDoItem->creator->isNot(auth()->user())) {
            abortJson('Not your to-do item');
        }

        $toDoItem->reminders()->create([
            'unit' => $request->unit,
            'duration' => $request->duration,
        ]);

        return json($toDoItem->reminder, 'Reminder created');
    }

}
