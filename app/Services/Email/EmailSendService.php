<?php
/**
 * @package App\Services\Email
 */
namespace App\Services\Email;

use App\Models\Requests\EmailTransactionalSendRequest;
use App\Utilities\NSHEmailHandler;

/**
 * EmailSend Service.
 *
 * @author silver.ibenye
 *
 */
class EmailSendService
{
    private $emailHandler;

    /**
     *
     * @param NSHEmailHandler $emailHandler
     */
    public function __construct(NSHEmailHandler $emailHandler)
    {
        $this->emailHandler = $emailHandler;
    }

    /**
     *
     * @param EmailTransactionalSendRequest $request
     * @return void
     */
    public function sendTransactional(EmailTransactionalSendRequest $request)
    {
        $this->emailHandler->sendTransactional($request->getSubject(), $request->getContent(),
                $request->getContentType(), $request->getTo(), $request->getFrom(),
                $request->getCc(), $request->getBcc());
    }
}
