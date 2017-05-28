<?php
namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use Rap2hpoutre\LaravelLogViewer\LaravelLogViewer;

class LogViewerController extends Controller
{
    protected $request;

    public function __construct ()
    {
        $this->request = $this->getIlluminateRequest();
        parent::__construct();
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     */
    public function index($request, $response, $args)
    {
        if ($this->request->input('l')) {
            LaravelLogViewer::setFile(base64_decode($this->request->input('l')));
        }

        if ($this->request->input('dl')) {
            return $this->download(LaravelLogViewer::pathToLogFile(base64_decode($this->request->input('dl'))));
        } elseif ($this->request->has('del')) {
            app('files')->delete(LaravelLogViewer::pathToLogFile(base64_decode($this->request->input('del'))));
            // return $this->redirect($this->request->url());
            return $response->withRedirect($this->request->url());
        } elseif ($this->request->has('delall')) {
            foreach(LaravelLogViewer::getFiles(true) as $file){
                app('files')->delete(LaravelLogViewer::pathToLogFile($file));
            }
            // return $this->redirect($this->request->url());
            return $response->withRedirect($this->request->url());
        }

        $response->write(app('view')->make('laravel-log-viewer.log', [
            'logs' => LaravelLogViewer::all(),
            'files' => LaravelLogViewer::getFiles(true),
            'current_file' => LaravelLogViewer::getFileName()
        ]));
    }

    private function redirect($to)
    {
        if (function_exists('redirect')) {
            return redirect($to);
        }

        return app('redirect')->to($to);
    }

    private function download($data)
    {
        if (function_exists('response')) {
            return response()->download($data);
        }

        // For laravel 4.2
        return app('\Illuminate\Support\Facades\Response')->download($data);
    }
}
