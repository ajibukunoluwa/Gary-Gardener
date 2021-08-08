<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Models\ToDoItem;
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

        $path = $request->file('attachment')->store($toDoItem->getAttachmentFolderPath());
        $toDoItem->update(['attachment' => $path]);

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
        $query = ToDoItem::query();

        $query->when($request->complete, function ($query) {
            return $query->whereDone();
        })->when($request->incomplete, function ($query) {
            return $query->whereNotDone();
        });

        $toDoItems = $query->latest('due_date')->paginate($request->per_page ?? 30);

        return json(ToDoItemResource::collection($toDoItems), 'Items gotten');
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
    public function markAsDone(ToDoItem $toDoItem)
    {
        if ($toDoItem->creator->isNot(auth()->user())) {
            abortJson('Not your to-do item');
        }

        $toDoItem->markAsDone();

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

        // Handle when an image is also sent
        if (! empty($request->file('attachment'))) {
            $path = $request->file('attachment')->store($toDoItem->getAttachmentFolderPath());
        }

        // $toDoItem->update([
        //     'title' => $request->title ?? $toDoItem->title,
        //     'body' => $request->body ?? $toDoItem->body,
        //     'due_date' => $request->due_date ?? $toDoItem->due_date,
        //     'attachment' => $path ?? $toDoItem->path,
        // ]);

        $inputs = $request->except('attachment');
        $inputs['attachment'] = $path ?? $toDoItem->path;

        $toDoItem->update($inputs);

        $toDoItem->reminders()->update(); // The Reminder boot method will update the `remind_at` columns

        // foreach ($toDoItem->reminders as $reminder) {
        //     $reminder->update();
        // }

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
