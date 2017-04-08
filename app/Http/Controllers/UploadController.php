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
        $postRequest = $this->mapFileUploadRequest();

        $userId = Auth::user()->id;

        $response = $this->service->uploadUserProfileImage($userId, $postRequest);

        return $this->response($response);
    }

    /**
     *
     * @return Response
     */
    public function uploadUserPorfolioImage()
    {
        $postRequest = $this->mapFileUploadRequest();

        $userId = Auth::user()->id;

        $response = $this->service->uploadUserPortfolioImage($userId, $postRequest);

        return $this->response($response);
    }

    /**
     *
     * @return Response
     */
    public function uploadUserPortfolioAudio()
    {
        $postRequest = $this->mapFileUploadRequest();

        $userId = Auth::user()->id;

        $location = $this->request->header('Location');

        $response = $this->service->uploadUserPortfolioAudio($userId, $location, $postRequest);

        return $this->response($response);
    }

    /**
     *
     * @return Response
     */
    public function validateFileUpload()
    {
        $postRequest = $this->mapFileUploadRequest();

        $this->service->validateUploadRequest($postRequest);

        return $this->response();
    }

    /**
     *
     * @return \App\Models\Requests\FileUploadRequest
     */
    private function mapFileUploadRequest()
    {
        $contentType = $this->request->header('Upload-Content-Type');
        if (empty($contentType)) {
            $contentType = $this->request->header('Content-Type');
        }
        $request = [
                'file' => $this->request->input('file'),
                'contentType' => $contentType,
                'caption' => $this->request->header('Upload-Caption')
        ];
        return $this->fileUploadRequestMapper->map($request);
    }
}
