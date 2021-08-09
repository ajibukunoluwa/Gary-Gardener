<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Models\ToDoItem;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\ToDoItemResource;
use App\Http\Requests\ListToDoItemRequest;
use App\Http\Requests\StoreToDoItemRequest;
use App\Http\Requests\UpdateToDoItemRequest;

class ToDoItemController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\StoreToDoItemRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreToDoItemRequest $request)
    {
        $toDoItem = auth()->user()->toDoItems()->create([
            'title' => $request->title,
            'body' => $request->body,
            'due_date' => $request->due_date,
        ]);

        $file = $request->file('attachment');
        $path = $file->storePubliclyAs(
            $toDoItem->generateAttachmentPath(), $toDoItem->generateAttachmentName($file->extension())
        );

        $toDoItem->update(['attachment_path' => $path]);

        return json(new ToDoItemResource($toDoItem), 'Item created');
    }

    /**
     * List resources.
     *
     * @param  App\Http\Requests\ListToDoItemRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function list(ListToDoItemRequest $request)
    {
        $query = auth()->user()->toDoItems();

        $query->when($request->has('complete'), function ($query) {
            return $query->whereDone();
        })->when($request->has('incomplete'), function ($query) {
            return $query->whereNotDone();
        });

        $toDoItems = $query->orderBy('due_date', 'asc')->paginate($request->per_page ?? 30);

        return ToDoItemResource::collection($toDoItems);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ToDoItem  $toDoItem
     * @return \Illuminate\Http\Response
     */
    public function show(ToDoItem $toDoItem)
    {
        return json(new ToDoItemResource($toDoItem), 'Item gotten');
    }

    /**
     * Mark resource as done.
     *
     * @param  \App\Models\ToDoItem  $toDoItem
     * @return \Illuminate\Http\Response
     */
    public function markAsComplete(ToDoItem $toDoItem)
    {
        if ($toDoItem->creator->isNot(auth()->user())) {
            abortJson('Not your to-do item');
        }

        if ($toDoItem->isNotComplete()) {
            $toDoItem->markAsComplete();
        }

        return json(new ToDoItemResource($toDoItem), 'Item marked as done');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\UpdateToDoItemRequest  $request
     * @param  \App\Models\ToDoItem  $toDoItem
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateToDoItemRequest $request, ToDoItem $toDoItem)
    {
        if ($toDoItem->creator->isNot(auth()->user())) {
            abortJson('Not your to-do item');
        }

        if ($file = $request->file('attachment')) {
            $path = $file->storePubliclyAs(
                $toDoItem->generateAttachmentPath(), $toDoItem->generateAttachmentName($file->extension())
            );
        }

        $inputs = $request->except('attachment');
        $inputs['attachment_path'] = $path ?? $toDoItem->attachment_path;

        $toDoItem->update($inputs);

        foreach ($toDoItem->reminders as $reminder) {
            $reminder->save(); // The Reminder boot method will update the `remind_at` columns
        }

        return json(new ToDoItemResource($toDoItem), 'Item updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ToDoItem  $toDoItem
     * @return \Illuminate\Http\Response
     */
    public function delete(ToDoItem $toDoItem)
    {
        if ($toDoItem->creator->isNot(auth()->user())) {
            abortJson('Not your to-do item');
        }

        $toDoItem->delete();

        return json([], 'Item deleted');
    }
}
