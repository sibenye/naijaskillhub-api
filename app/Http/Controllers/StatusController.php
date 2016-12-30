<?php
/**
 * @package App\Http\Controllers
 */
namespace App\Http\Controllers;

class StatusController extends Controller
{

    public function status()
    {
        return $this->response('API OK');
    }
}