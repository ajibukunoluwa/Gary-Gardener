<?php

namespace App\Console\Commands;

use App\Models\Reminder;
use App\Mail\ReminderMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendRemindersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is responsible for sending reminders to to-do items creators';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Reminder::whereRemindAt(date('Y-m-d'))->with(['toDoItem', 'toDoItem.creator'])->chunk(100, function($reminders)
        {
            foreach ($reminders as $reminder)
            {
                Mail::to($reminder->toDoItem->creator->email)
                    ->send(new ReminderMail($reminder));
            }
        });
    }
}
