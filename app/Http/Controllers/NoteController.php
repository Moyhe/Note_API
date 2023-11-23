<?php

namespace App\Http\Controllers;

use App\Http\Resources\NoteResource;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
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
}
