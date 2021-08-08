@component('mail::message')
# Reminder 🔔

Hey there, This is a reminder about the item below

@component('mail::panel')
**{{ $reminder->toDoItem->title }}**

*{{ $reminder->toDoItem->body }}*

*Due on {{ $reminder->toDoItem->due_date->toDateString()}}*
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
