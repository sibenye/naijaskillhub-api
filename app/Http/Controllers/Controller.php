<?php
namespace App\Http\Controllers;

use App\Models\Responses\NSHResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{

    /**
     * @var Request
     */
    protected $request;

    public function __construct(Request $request) // Dependency injection
    {
        $this->request = $request;
    }

    public function response($content = NULL)
    {
        $nsh_response = new NSHResponse();
        $nsh_response->setResponse($content);
        return $nsh_response->render();
    }

    public function validateRequest($validationRules)
    {
        $this->validate($this->request, $validationRules);
    }
}
