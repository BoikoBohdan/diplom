<?php

namespace App\Http\Controllers\API\Chat;

use App\{ChatRoom,
    Http\Controllers\Controller,
    Http\Requests\API\Chat\CreateChatRoomRequest,
    Http\Requests\API\Chat\SendFileRequest,
    Http\Requests\API\Chat\SendMessageRequest,
    Services\ChatService};

class ChatController extends Controller
{
    /**
     * Chat service
     *
     * @var ChatService
     */
    protected $service;

    /**
     * Constructor
     *
     * @param ChatService $service
     */
    public function __construct (ChatService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Get(path="/chat/user-list",
     *   tags={"Chat"},
     *   description="Get user list for chat",
     *   summary="Get list of drivers for admin or admins for driver in chat",
     *
     *   @OA\Response(
     *     response="200",
     *     description="Success",
     *     @OA\JsonContent(
     *              @OA\Property(
     *                  type="integer",
     *                  property="id",
     *                  example="12"
     *              ),
     *              @OA\Property(
     *                  type="string",
     *                  property="full_name",
     *                  example="Developer Vlad"
     *              ),
     *              @OA\Property(
     *                  type="string",
     *                  property="image",
     *                  example="path to image"
     *              ),
     *              )
     *          )
     *   ),
     * @OA\Response(
     *         response="401",
     *         description="Error",
     *     @OA\JsonContent(
     *          @OA\Property(
     *               type="string",
     *               property="message",
     *          )
     *      )
     *   ),
     * @OA\Response(
     *         response="500",
     *         description="Error",
     *     @OA\JsonContent(
     *          @OA\Property(
     *               type="string",
     *               property="message",
     *          )
     *      )
     *   ),
     * security={{"bearerAuth":{}}}
     * )
     */
    public function getUsers ()
    {
        return $this->service->getUsers();
    }

    /**
     * @OA\Get(path="/chat/room-list",
     *   tags={"Chat"},
     *   description="Get room list for chat",
     *   summary="Get list of available rooms for user",
     *
     *   @OA\Response(
     *     response="200",
     *     description="Success",
     *     @OA\JsonContent(
     *              @OA\Property(
     *                  type="integer",
     *                  property="id",
     *                  example="12"
     *              ),
     *              @OA\Property(
     *                  type="array",
     *                  property="users",
     *                  @OA\Items(
     *                      type="integer",
     *                      example="12"
     *                  ),
     *              ),
     *              @OA\Property(
     *                  type="object",
     *                  property="last_message",
     *                   @OA\Property(
     *                      type="integer",
     *                      property="id",
     *                      example="1"
     *                  ),
     *                  @OA\Property(
     *                      type="string",
     *                      property="message",
     *                      example="Hello World"
     *                  ),
     *                  @OA\Property(
     *                      type="object",
     *                      property="created_at",
     *                  @OA\Property(
     *                      type="string",
     *                      property="date",
     *                      example="Some date"
     *                  ),
     *                  @OA\Property(
     *                      type="string",
     *                      property="timezone",
     *                      example="UTC"
     *                  ),
     *                  ),
     *              ),
     *              )
     *          )
     *   ),
     * security={{"bearerAuth":{}}}
     * )
     */
    public function getRooms ()
    {
        return $this->service->getRoomList();
    }

    /**
     * @OA\Get(path="/chat/history/{room}",
     *   tags={"Chat"},
     *   description="Get message history of selected room",
     *   summary="Get message history of selected room",
     * @OA\Parameter (
     *         required=true,
     *         parameter="room",
     *         name="room",
     *         in="path",
     *         @OA\Schema (
     *             type="integer"
     *         )
     *     ),
     *   @OA\Response(
     *     response="200",
     *     description="Success",
     *     @OA\JsonContent(
     *              @OA\Property(
     *                  type="integer",
     *                  property="id",
     *                  example="12"
     *              ),
     *                   @OA\Property(
     *                      type="string",
     *                      property="message",
     *                      example="Hello world!!!"
     *                  ),
     *                  @OA\Property(
     *                      type="integer",
     *                      property="sender_id",
     *                      example="5"
     *                  ),
     *                  @OA\Property(
     *                      type="object",
     *                      property="created_at",
     *                  @OA\Property(
     *                      type="string",
     *                      property="date",
     *                      example="Some date"
     *                  ),
     *                  @OA\Property(
     *                      type="string",
     *                      property="timezone",
     *                      example="UTC"
     *                  ),
     *              ),
     *              )
     *          )
     *   ),
     * security={{"bearerAuth":{}}}
     * )
     */
    public function getMessageHistory (ChatRoom $room)
    {
        return $this->service->getMessageHistory($room);
    }

    /**
     * @OA\Post(path="/chat/create-message",
     *   tags={"Chat"},
     *   description="Create new chat message",
     *   summary="Create new chat message",
     *     @OA\RequestBody(
     *         required=true,
     *      @OA\MediaType(
     *           mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="reciever_id",
     *                     type="integer",
     *                     example="12"
     *                 ),
     *                  @OA\Property(
     *                     property="message",
     *                     type="string",
     *                     example="hello world!!!"
     *                 ),
     *                  @OA\Property(
     *                     property="chat_room_id",
     *                     type="integer",
     *                     example="3"
     *                 ),
     *                 required={"message", "chat_room_id", "reciever_id"},
     *             )
     *       )
     * ),
     *   @OA\Response(
     *     response="200",
     *     description="Success",
     *     @OA\JsonContent(
     *          @OA\Property(
     *               type="string",
     *               property="message",
     *          )
     *      )
     *   ),
     *   @OA\Response(
     *         response="401",
     *         description="Error",
     *     @OA\JsonContent(
     *          @OA\Property(
     *               type="string",
     *               property="message",
     *          )
     *      )
     *   ),
     *   @OA\Response(
     *         response="500",
     *         description="Error",
     *     @OA\JsonContent(
     *          @OA\Property(
     *               type="string",
     *               property="message",
     *          )
     *      )
     *   ),
     *   security={{"bearerAuth":{}}}
     * )
     */
    public function storeMessage (SendMessageRequest $request)
    {
        return $this->service->storeMessage($request->all());
    }

    /**
     * @OA\Post(path="/chat/create-message-with-file",
     *   tags={"Chat"},
     *   description="Create new chat message with file",
     *   summary="Create new chat message with file",
     *     @OA\RequestBody(
     *         required=true,
     *      @OA\MediaType(
     *           mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                  @OA\Property(
     *                     property="file",
     *                     type="string",
     *                     example="base64 encoded image"
     *                 ),
     *                  @OA\Property(
     *                     property="chat_room_id",
     *                     type="integer",
     *                     example="3"
     *                 ),
     *                 required={"file", "chat_room_id"},
     *             )
     *       )
     * ),
     *   @OA\Response(
     *     response="200",
     *     description="Success",
     *     @OA\JsonContent(
     *          @OA\Property(
     *               type="string",
     *               property="message",
     *          )
     *      )
     *   ),
     *   @OA\Response(
     *         response="401",
     *         description="Error",
     *     @OA\JsonContent(
     *          @OA\Property(
     *               type="string",
     *               property="message",
     *          )
     *      )
     *   ),
     *   @OA\Response(
     *         response="500",
     *         description="Error",
     *     @OA\JsonContent(
     *          @OA\Property(
     *               type="string",
     *               property="message",
     *          )
     *      )
     *   ),
     *   security={{"bearerAuth":{}}}
     * )
     */
    public function storeFile (SendFileRequest $request)
    {
        return $this->service->storeFile($request->only('file', 'chat_room_id'));
    }

    /**
     * @OA\Post(path="/chat/create-room",
     *   tags={"Chat"},
     *   description="Create new chat room",
     *   summary="Create new chat room",
     *     @OA\RequestBody(
     *         required=true,
     *      @OA\MediaType(
     *           mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                  @OA\Property(
     *                     property="reciever_id",
     *                     type="integer",
     *                     example="3"
     *                 ),
     *                 required={"file", "chat_room_id"},
     *             )
     *       )
     * ),
     *   @OA\Response(
     *     response="200",
     *     description="Success",
     *     @OA\JsonContent(
     *          @OA\Property(
     *               type="string",
     *               property="message",
     *          )
     *      )
     *   ),
     *   @OA\Response(
     *         response="401",
     *         description="Error",
     *     @OA\JsonContent(
     *          @OA\Property(
     *               type="string",
     *               property="message",
     *          )
     *      )
     *   ),
     *   @OA\Response(
     *         response="500",
     *         description="Error",
     *     @OA\JsonContent(
     *          @OA\Property(
     *               type="string",
     *               property="message",
     *          )
     *      )
     *   ),
     *   security={{"bearerAuth":{}}}
     * )
     */
    public function createChatRoom (CreateChatRoomRequest $request)
    {
        $this->service->setChatRoom($request->reciever_id);

        return $this->isSuccess();
    }
}
