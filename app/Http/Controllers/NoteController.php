<?php

namespace App\Http\Controllers;

use App\Http\Resources\NoteResource;
use App\Models\Note;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class NoteController extends BaseController
{

    /**
     *
     * @OA\Get(
     *     path="/api/notes/",
     *     tags={"notes"},
     *     summary="List Notes",
     *     operationId="list_Notes",
     *     description="Returns a list Notes",
     *     @OA\Response(
     *         response=200,
     *         description="List of Notes",
     *
     *           @OA\JsonContent(
     *              @OA\Property(
     *                  property="title",
     *                  description="Note Title",
     *                  type="string",
     *                  nullable="false",
     *                  example="work"
     *              ),
     *              @OA\Property(
     *                  property="content",
     *                  description="content description",
     *                  type="string",
     *                  nullable="false",
     *                  example="how to manage you time"
     *              ),
     *              @OA\Property(
     *                  property="user_id",
     *                  description="user id",
     *                  type="string",
     *                  nullable="true",
     *                  example="1"
     *              ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function index()
    {
        $notes = Note::all();

        if (empty($notes)) {
            return $this->sendError('No Notes Listed yet');
        }

        return $this->sendResponse(
            NoteResource::collection($notes),
            'notes was retreived successfully'
        );
    }

    /**
     *
     * @OA\Get(
     *     path="/api/notes/user/{user}",
     *     tags={"notes"},
     *     summary="List Notes of users",
     *     operationId="list_Notes_of_user",
     *     @OA\Parameter(
     *         name="user",
     *         description="User ID",
     *         in="path",
     *         required=true,
     *         example="1"
     *     ),
     *     description="Returns a list Notes of a user",
     *     @OA\Response(
     *         response=200,
     *         description="List of Notes",
     *
     *           @OA\JsonContent(
     *              @OA\Property(
     *                  property="title",
     *                  description="Note Title",
     *                  type="string",
     *                  nullable="false",
     *                  example="work"
     *              ),
     *              @OA\Property(
     *                  property="content",
     *                  description="content description",
     *                  type="string",
     *                  nullable="false",
     *                  example="how to manage you time"
     *              ),
     *              @OA\Property(
     *                  property="user_id",
     *                  description="user id",
     *                  type="string",
     *                  nullable="true",
     *                  example="1"
     *              ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */

    public function usersNote(Note $note, string $id)
    {
        $this->authorize('view', $note);

        $notes = Note::query()->where('user_id', $id)->get();
        return $this->sendResponse(
            NoteResource::collection($notes),
            'Notes was Retrieved Successfully'
        );
    }


    /**
     * @OA\Schema(
     *    schema="StoreRequest",
     *              @OA\Property(
     *                  property="title",
     *                  description="Note Title",
     *                  type="string",
     *                  nullable="false",
     *                  example="work"
     *              ),
     *              @OA\Property(
     *                  property="content",
     *                  description="content description",
     *                  type="string",
     *                  nullable="false",
     *                  example="how to manage you time"
     *              ),
     *              @OA\Property(
     *                  property="user_id",
     *                  description="user id",
     *                  type="string",
     *                  nullable="true",
     *                  example="1"
     *              ),
     * )
     *
     * @OA\Post(
     *     path="/api/notes",
     *     tags={"notes"},
     *     summary="create a note",
     *     description="crate a note ",
     *     operationId="store",
     *     @OA\RequestBody(
     *        @OA\JsonContent(ref="#/components/schemas/StoreRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Authentication successful",

     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */

    public function store(Request $request, AuthUserController $auth)
    {


        $data = $request->all();
        $validator = Validator::make($data, [

            'title' => 'required',
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validattion Error', $validator->errors());
        }

        $data['user_id'] = $auth->getUserId();
        $notes = Note::create($data);
        return $this->sendResponse(new NoteResource($notes), 'Note Created Successfully');
    }


    /**
     *
     * @OA\Get(
     *     path="/api/notes/{note}",
     *     tags={"notes"},
     *     summary="List one Note",
     *     operationId="list_one_Note",
     *      @OA\Parameter(
     *         name="note",
     *         description="Note ID",
     *         in="path",
     *         required=true,
     *         example="1"
     *     ),
     *     description="Returns one note",
     *     @OA\Response(
     *         response=200,
     *         description="list on note",
     *
     *           @OA\JsonContent(
     *              @OA\Property(
     *                  property="title",
     *                  description="Note Title",
     *                  type="string",
     *                  nullable="false",
     *                  example="work"
     *              ),
     *              @OA\Property(
     *                  property="content",
     *                  description="content description",
     *                  type="string",
     *                  nullable="false",
     *                  example="how to manage you time"
     *              ),
     *              @OA\Property(
     *                  property="user_id",
     *                  description="user id",
     *                  type="string",
     *                  nullable="true",
     *                  example="1"
     *              ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function show(string $note)
    {
        $this->authorize('view', $note);

        $note = Note::find($note);

        if (is_null($note)) {
            return $this->sendError('Note was note found');
        }

        return $this->sendResponse(new NoteResource($note), 'Note Found successfully');
    }


    /**
     * @OA\Schema(
     *    schema="UpdateRequest",
     *              @OA\Property(
     *                  property="title",
     *                  description="Note Title",
     *                  type="string",
     *                  nullable="false",
     *                  example="work"
     *              ),
     *              @OA\Property(
     *                  property="content",
     *                  description="content description",
     *                  type="string",
     *                  nullable="false",
     *                  example="how to manage you time"
     *              ),
     *              @OA\Property(
     *                  property="user_id",
     *                  description="user id",
     *                  type="string",
     *                  nullable="true",
     *                  example="1"
     *              ),
     * )
     *
     * @OA\Put(
     *     path="/api/notes/{note}",
     *     tags={"notes"},
     *     summary="upate a note",
     *     description="update a note ",
     *     operationId="update_note",
     *     @OA\Parameter(
     *         name="note",
     *         description="Note ID",
     *         in="path",
     *         required=true,
     *         example="1"
     *     ),
     *     @OA\RequestBody(
     *        @OA\JsonContent(ref="#/components/schemas/UpdateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Authentication successful",

     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */

    public function update(Request $request, string $note)
    {
        $this->authorize('view', $note);

        $note = Note::find($note);

        if (is_null($note)) {
            return $this->sendError('Note was note found');
        }

        $data = $request->all();
        $validator = Validator::make($data, [

            'title' => 'required',
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validattion Error', $validator->errors());
        }

        $note->update($data);

        return $this->sendResponse(new NoteResource($note), 'Note updated successfullu');
    }


    /**
     * @OA\Schema(
     *    schema="DeleteRequest",
     * )
     *
     * @OA\Delete(
     *     path="/api/notes/{note}",
     *     tags={"notes"},
     *     summary="delete a note",
     *     description="update a note ",
     *     operationId="Delete_note",
     *     @OA\Parameter(
     *         name="note",
     *         description="Note ID",
     *         in="path",
     *         required=true,
     *         example="1"
     *     ),
     *     @OA\RequestBody(
     *        @OA\JsonContent(ref="#/components/schemas/DeleteRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Authentication successful",

     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */


    public function destroy(string $note)
    {
        // $this->authorize('view', $note);

        $note = Note::find($note);

        if (is_null($note)) {
            return $this->sendError('Note was note found');
        }

        $note->delete();

        return $this->sendResponse(new NoteResource($note), 'Note Deleted successfullu');
    }
}
