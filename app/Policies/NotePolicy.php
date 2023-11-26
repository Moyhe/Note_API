<?php

namespace App\Policies;

use App\Http\Controllers\AuthUserController;
use App\Models\Note;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class NotePolicy
{

    /**
     * Determine whether the user can view the model.
     */
    public function view(AuthUserController $user, Note $note): bool
    {
        return $user->getUserId() == $note->user_id;
    }
}
