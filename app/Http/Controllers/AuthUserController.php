<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthUserController extends Controller
{
    /**
     * @OA\Schema(
     *    schema="RegisterRequest",
     *    @OA\Property(
     *        property="name",
     *        type="string",
     *        description="User Name",
     *        nullable=false,
     *
     *    ),
     *    @OA\Property(
     *        property="email",
     *        type="string",
     *        description="User EMail",
     *        nullable=false,
     *        format="email"
     *    ),
     *
     *  *    @OA\Property(
     *        property="password",
     *        type="string",
     *        description="User Password",
     *        nullable=false,
     *        example="password"
     *    ),
     * )
     *
     * @OA\Post(
     *     path="/api/user/register",
     *     tags={"users"},
     *     summary="Authorize user",
     *     description="register user",
     *     operationId="register",
     *     @OA\RequestBody(
     *        @OA\JsonContent(ref="#/components/schemas/RegisterRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Authentication successful",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                property="token",
     *                type="string",
     *                description="JWT authorization token",
     *                example="1|fSPJ2AR0TU0dLB6aiYgtSGHkPnFTfBdh4ltISiSo",
     *             ),
     *             @OA\Property(
     *                property="type",
     *                type="string",
     *                description="Token type",
     *                example="bearer",
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */

    public function register()
    {
        return Http::post('http://host.docker.internal:81/api/user/register', [

            "name" => request('name'),
            "email" => request('email'),
            "password" => request('password')

        ])->throw()->json();
    }



    /**
     * @OA\Schema(
     *    schema="LoginRequest",
     *    @OA\Property(
     *        property="email",
     *        type="string",
     *        description="User EMail",
     *        nullable=false,
     *        format="email"
     *    ),
     *    @OA\Property(
     *        property="password",
     *        type="string",
     *        description="User Password",
     *        nullable=false,
     *        example="password"
     *    ),
     * )
     *
     * @OA\Post(
     *     path="/api/user/login",
     *     tags={"users"},
     *     summary="Authorize user",
     *     description="Authorizes user by its email and password",
     *     operationId="login",
     *     @OA\RequestBody(
     *        @OA\JsonContent(ref="#/components/schemas/LoginRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Authentication successful",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                property="token",
     *                type="string",
     *                description="Sanctum authorization token",
     *                example="1|fSPJ2AR0TU0dLB6aiYgtSGHkPnFTfBdh4ltISiSo",
     *             ),
     *             @OA\Property(
     *                property="type",
     *                type="string",
     *                description="Token type",
     *                example="bearer",
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */

    public function login()
    {

        return Http::post('http://host.docker.internal:81/api/user/login', [

            "email" => request('email'),
            "password" => request('password')

        ])->throw()->json()['token'];
    }



    public function getUserId()
    {
        $token = $this->login();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('http://host.docker.internal:81/api/users')->json()['id'];

        return $response;
    }
}
