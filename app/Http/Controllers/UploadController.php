<?php
/**
 * @package App\Http\Controllers
 */
namespace App\Http\Controllers;

use App\Services\UploadService;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mappers\FileUploadRequestMapper;

/**
 * Upload Controller.
 * Receives image, video and file upload requests.
 *
 * @author silver.ibenye
 *
 */
class UploadController extends Controller
{
    private $service;

    /**
     *
     * @var FileUploadRequestMapper
     */
    private $fileUploadRequestMapper;

    /**
     *
     * @param Request $request
     * @param UploadService $uploadService
     * @param FileUploadRequestMapper $fileUploadRequestMapper
     */
    public function __construct(Request $request, UploadService $uploadService,
            FileUploadRequestMapper $fileUploadRequestMapper)
    {
        parent::__construct($request);
        $this->service = $uploadService;
        $this->fileUploadRequestMapper = $fileUploadRequestMapper;
    }

    /**
     *
     * @param integer $userId
     * @return Reponse
     */
    public function uploadUserProfileImage()
    {
        $userId = Auth::user()->id;
        $request = [
                'file' => $this->request->getContent(),
                'contentType' => $this->request->header('Content-Type'),
                'contentLength' => $this->request->header('Content-Length')
        ];
        $postRequest = $this->fileUploadRequestMapper->map($request);

        $response = $this->service->uploadUserProfileImage($userId, $postRequest);

        return $this->response($response);
    }
}
