<?php
/**
 * @package App\Http\Controllers
 */
namespace App\Http\Controllers;

use App\Services\UploadService;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

/**
 * Upload Controller.
 * Receives image, video and file upload requests.
 *
 * @author silver.ibenye
 *
 */
class UploadController extends Controller
{
    private $uploadService;

    /**
     *
     * @param UploadService $uploadService
     */
    public function __construct(Request $request, UploadService $uploadService)
    {
        parent::__construct($request);
        $this->uploadService = $uploadService;
    }

    /**
     *
     * @return Response
     */
    public function uploadImage()
    {
        $image = $this->request->file("image");
        $meatdata = $this->uploadService->uploadImage($image);

        return $this->response($meatdata);
    }
}
