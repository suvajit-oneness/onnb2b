<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\SettingsInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
class AboutController extends Controller
{
    public function __construct(SettingsInterface $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;
    }
     /**
     * This method is for show settings
     * @return \Illuminate\Http\JsonResponse
     */
    public function about(Request $request): JsonResponse
    {

        return response()->json([
            $data = $this->settingsRepository->listAll()
            ]);
    }

    /**
     * This method is for show settings details
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {

        return response()->json([
            $data = $this->settingsRepository->listById($id)
            ]);
    }
}
