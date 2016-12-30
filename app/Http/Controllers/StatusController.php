<?php
/**
 * @package App\Http\Controllers
 */
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class StatusController extends Controller
{

    public function status()
    {
        $this->testDb();
        return $this->response('API OK');
    }

    private function testDb()
    {
        $db = DB::connection()->getPdo();

        if (empty($db)) {
            throw new \PDOException();
        }
    }
}