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
    protected $http_status;

    /**
     *
     * @var string
     */
    protected $message;

    public function __toString()
    {
        return json_encode(get_object_vars($this));
    }

    public function __construct($http_status = 200, $status = 'success', $message = NULL, $content = NULL)
    {
        $this->response = $content;
        $this->http_status = $http_status;
        $this->status = $status;
        $this->message = $message;
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
}