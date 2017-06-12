<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Event;

class EventPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view the event.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Event  $event
     * @return mixed
     */
    public function view(User $user, Event $event)
    {
        return $user->can('view', $event->campaign);
    }

    /**
     * Determine whether the user can manage the event. (delete/update)
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Event  $event
     * @return mixed
     */
    public function manage(User $user, Event $event)
    {
        return $user->id === $event->user_id
            || $user->can('manage', $event->campaign);
    }
}