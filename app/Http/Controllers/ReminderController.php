<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Models\ToDoItem;
use Illuminate\Http\Request;

class ReminderController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ToDoItem $toDoItem)
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
