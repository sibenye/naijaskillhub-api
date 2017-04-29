<?php
namespace App\Exceptions;

use App\Models\Responses\NSHResponse;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
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
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        Switch (get_class($e)) {
            case 'Illuminate\Auth\AuthenticationException' :
                $errorResponse = new NSHResponse(401, 101, $e->getMessage());
                return $errorResponse->render();
                break;
            case 'Illuminate\Auth\Access\AuthorizationException' :
                $errorResponse = new NSHResponse(401, 102, $e->getMessage());
                return $errorResponse->render();
                break;
            case 'Illuminate\Database\Eloquent\ModelNotFoundException' :
                $errorResponse = new NSHResponse(404, 100, $e->getMessage());
                return $errorResponse->render();
                break;
            case 'Illuminate\Validation\ValidationException' :
                $validationMessage = $e->getResponse();
                if (is_object($e->getResponse()) &&
                         get_class($e->getResponse()) == 'Illuminate\Http\JsonResponse') {
                    $validationMessage = $e->getResponse()->getContent();
                }

                $errorResponse = new NSHResponse(400, 111, $validationMessage);
                return $errorResponse->render();
                break;
            case 'PDOException' :
                $errorResponse = new NSHResponse(500, 190, $e->getMessage());
                return $errorResponse->render();
                break;
            default :
                return parent::render($request, $e);
        }
    }
}
