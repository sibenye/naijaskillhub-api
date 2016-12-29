<?php
namespace App\Models\Responses;

use Illuminate\Http\Response;

class NSHResponse
{

    /**
     *
     * @var mixed
     */
    protected $response;

    /**
     *
     * @var string
     */
    protected $status;

    /**
     *
     * @var integer
     */
    protected $code;

    /**
     *
     * @var integer
     */
    protected $http_status;

    /**
     *
     * @var string
     */
    protected $message;

    /**
     *
     * @var string
     */
    protected $messageDetail;

    public function __toString()
    {
        return json_encode(get_object_vars($this));
    }

    public function __construct($http_status = 200, $code = 0, $messageDetail = NULL, $content = NULL)
    {
        $this->response = $content;
        $this->http_status = $http_status;
        $this->code = $code;
        $this->message = NSHCodedMessages::messages [$code];
        $this->messageDetail = $messageDetail;
        $this->status = $code == 0 ? 'success' : 'error';
    }

    public function render()
    {
        return response($this, $this->http_status,
                [
                        'Content-Type' => 'application/json'
                ]);
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse($response)
    {
        $this->response = $response;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function getHttpStatus()
    {
        return $this->http_status;
    }

    public function setHttpStatus($http_status)
    {
        $this->http_status = $http_status;
        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return integer
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param integer $code
     * @return void
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getMessageDetail()
    {
        return $this->messageDetail;
    }

    /**
     * @param  $messageDetail
     * @return void
     */
    public function setMessageDetail($messageDetail)
    {
        $this->messageDetail = $messageDetail;
    }
}