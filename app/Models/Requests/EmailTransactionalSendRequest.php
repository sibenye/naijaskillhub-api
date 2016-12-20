<?php
/**
 * @package App\Models\Requests
 */
namespace App\Models\Requests;

/**
 * EmailTransactionalSend Request.
 *
 * @author silver.ibenye
 *
 */
class EmailTransactionalSendRequest implements IPostRequest
{
    /**
     *
     * @var string
     */
    private $from;

    /**
     *
     * @var string
     */
    private $to;

    /**
     *
     * @var string
     */
    private $subject;

    /**
     *Content Type: TEXT OR HTML
     *
     * @var string
     */
    private $contentType = 'HTML';

    /**
     *
     * @var string
     */
    private $content;

    /**
     *
     * @var string
     */
    private $cc;

    /**
     *
     * @var string
     */
    private $bcc;

    /**
     * {@inheritDoc}
     * @see \App\Models\Requests\IPostRequest::buildModelAttributes()
     */
    public function buildModelAttributes()
    {
        // unimplemented. Not needed.
    }

    /**
     * {@inheritDoc}
     * @see \App\Models\Requests\IPostRequest::getValidationRules()
     */
    public function getValidationRules()
    {
        return [
                'from' => 'max:85',
                'to' => 'max:85',
                'subject' => 'required|max:85',
                'content' => 'required',
                'contentType' => 'in:TEXT,HTML'
        ];
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param  $from
     * @return void
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param  $to
     * @return void
     */
    public function setTo($to)
    {
        $this->to = $to;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param  $subject
     * @return void
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @param  $contentType
     * @return void
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param  $content
     * @return void
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getCc()
    {
        return $this->cc;
    }

    /**
     * @param  $cc
     * @return void
     */
    public function setCc($cc)
    {
        $this->cc = $cc;
    }

    /**
     * @return string
     */
    public function getBcc()
    {
        return $this->bcc;
    }

    /**
     * @param  $bcc
     * @return void
     */
    public function setBcc($bcc)
    {
        $this->bcc = $bcc;
    }
}
