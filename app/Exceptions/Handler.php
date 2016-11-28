<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use App\Models\Responses\NSHResponse;
use phpDocumentor\Reflection\Types\String_;

class Handler extends ExceptionHandler {
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
            AuthorizationException::class,
            HttpException::class,
            ModelNotFoundException::class,
            ValidationException::class
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e) {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e) {
        $status = 'Error. ' . $e->getMessage();
        Switch (get_class($e)) {
            case 'Illuminate\Database\Eloquent\ModelNotFoundException' :
                $errorResponse = new NSHResponse(404, $status,
                    'Resource Not Found');
                return $errorResponse->render();
                break;
            case 'Illuminate\Validation\ValidationException' :
                $validationMessage = $e->getResponse();
                if (! is_string($e->getResponse())) {
                    $validationMessage = $e->getResponse()->getContent();
                }
                $errorResponse = new NSHResponse(400, $status,
                    $validationMessage);
                return $errorResponse->render();
                break;
            default :
                return parent::render($request, $e);
        }
    }

}
