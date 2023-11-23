<?php

namespace App\Http\Controllers;

use App\Http\Resources\NoteResource;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $notes = Note::all();

        if (empty($notes)) {
            return $this->sendError('No Notes Listed yet');
        }

        return $this->sendResponse(NoteResource::collection($notes), 'notes was retreived successfully');
    }


    public function usersNote(Note $note, string $id)
    {
        $this->authorize('view', $note);

        $notes = Note::query()->where('user_id', $id)->get();
        return $this->sendResponse(
            NoteResource::collection($notes),
            'Notes was Retrieved Successfully'
        );
    }
}
