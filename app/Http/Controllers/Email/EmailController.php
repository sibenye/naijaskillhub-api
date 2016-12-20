<?php
/**
 * @package App\Http\Controllers\Email
 */
namespace App\Http\Controllers\Email;

use App\Http\Controllers\Controller;
use App\Services\Email\EmailSendService;
use App\Mappers\EmailTransactionalSendRequestMapper;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

/**
 * Email Controller
 *
 * @author silver.ibenye
 *
 */
class EmailController extends Controller
{
    private $emailSendService;
    private $emailTransactionalSendRequestMapper;

    public function __construct(Request $request, EmailSendService $emailSendService,
            EmailTransactionalSendRequestMapper $emailTransactionalSendRequestMapper)
    {
        parent::__construct($request);
        $this->emailSendService = $emailSendService;
        $this->emailTransactionalSendRequestMapper = $emailTransactionalSendRequestMapper;
    }

    /**
     *
     * @return Response
     */
    public function sendTransactional()
    {
        $request = $this->emailTransactionalSendRequestMapper->map($this->request->all());

        $this->validateRequest($request->getValidationRules());

        $this->emailSendService->sendTransactional($request);

        return $this->response();
    }
}
