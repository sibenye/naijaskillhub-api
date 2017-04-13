<?php
/**
 * @package App\Http\Controllers
 */
namespace App\Http\Controllers;

use App\Mappers\FileUploadRequestMapper;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

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
    public function uploadUserPortfolioImage()
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

        $response = $this->service->uploadUserPortfolioAudio($userId, $postRequest);

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
        $request = [
                'file' => $this->request->input('file'),
                'contentType' => $this->request->input('uploadContentType'),
                'caption' => $this->request->input('caption')
        ];
        return $this->fileUploadRequestMapper->map($request);
    }
}
