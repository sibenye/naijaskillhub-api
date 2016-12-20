<?php
/**
 * @package App\Utilities
 */
namespace App\Utilities;

use Illuminate\Support\Facades\Mail;

/**
 * NSHEmailDriver.
 * A mailgun api wrapper.
 *
 * @author silver.ibenye
 *
 */
class NSHEmailHandler
{

    /**
     *
     * @param string $toEmail
     * @param string $fromEmail
     * @param string $fromName
     * @param string $subject
     * @param string $content HTML content or ordinary text.
     * @param string $contentType
     * @return void
     */
    public function sendTransactional($subject, $content, $contentType = 'HTML', $toEmail = NULL,
            $fromEmail = NULL, $cc = NULL, $bcc = NULL)
    {
        $template_view = 'emails.template';
        $template_content = [
                'content' => $content
        ];

        if ($contentType == 'TEXT') {
            $template_content = [
                    'content' => nl2br($content)
            ];
        }

        Mail::send($template_view, $template_content,
                function ($message) use ($toEmail, $subject, $fromEmail, $cc, $bcc) {
                    $message->subject($subject);
                    if ($toEmail) {
                        $message->to($toEmail);
                    }
                    if ($fromEmail) {
                        $message->from($fromEmail);
                        $message->replyTo($fromEmail);
                    }
                    if ($cc) {
                        $message->cc($cc);
                    }
                    if ($bcc) {
                        $message->bcc($bcc);
                    }
                });
    }
}
