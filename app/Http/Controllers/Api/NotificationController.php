<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Interfaces\NotificationInterface;
class NotificationController extends Controller
{
    private NotificationInterface $notificationRepository;

    public function __construct(NotificationInterface $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    /**
     * This method is for show notification list
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        return response()->json([
        $data = $this->notificationRepository->getAlllist()
        ]);

    }
    /**
     * This method is for create notification
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->only([
            'name', 'description', 'image_path', 'slug'
        ]);

        return response()->json(
            [
                'data' => $this->notificationRepository->create($data)
            ],
            Response::HTTP_CREATED
        );
    }

    /**
     * This method is for show notification details
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        $Id = $request->route('id');

        return response()->json([
            'data' => $this->notificationRepository->getNotificationById($Id)
        ]);
    }
    /**
     * This method is for category update
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $Id = $request->route('id');
        $newDetails = $request->only([
             'name','description', 'image_path', 'slug'
        ]);

        return response()->json([
            'data' => $this->notificationRepository->update($Id, $newDetails)
        ]);
    }
    /**
     * This method is for category delete
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        $id = $request->route('id');
        $this->notificationRepository->delete($id);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

}
