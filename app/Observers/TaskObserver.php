<?php

namespace App\Observers;

use App\Models\Task;
use Filament\Notifications\Notification;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        Notification::make()
        ->title('New task has been assigned: ' . $task->name)
        ->sendToDatabase($task->user);
    }


}
