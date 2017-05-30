<?php
namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use Rap2hpoutre\LaravelLogViewer\LaravelLogViewer;

class LogViewerController extends Controller
{
    protected $request;

    public function __construct($container)
    {
        $this->request = $this->getIlluminateRequest();
        parent::__construct($container);
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
            // return $this->download(LaravelLogViewer::pathToLogFile(base64_decode($this->request->input('dl'))));
            $fileName = base64_decode($this->request->input('dl'));
            $filePath = LaravelLogViewer::pathToLogFile($fileName);
            $fp = fopen($filePath, "r");
            $fileSize = filesize($filePath);
            //下载文件需要用到的头
            $response = $response->withAddedHeader("Content-type", "text/html;charset=utf-8")
                ->withAddedHeader("Content-type", "application/octet-stream")
                ->withAddedHeader("Accept-Ranges", "bytes")
                ->withAddedHeader("Accept-Length:", $fileSize)
                ->withAddedHeader("Content-Disposition", "attachment; filename=$fileName");
            $buffer = 1024;
            $fileCount = 0;
            //向浏览器返回数据
            while (!feof($fp) && $fileCount < $fileSize) {
                $fileCon = fread($fp, $buffer);
                $fileCount += $buffer;
                $response->write($fileCon);
            }
            fclose($fp);
            return $response;
        } elseif ($this->request->has('del')) {
            app('files')->delete(LaravelLogViewer::pathToLogFile(base64_decode($this->request->input('del'))));
            // return $this->redirect($this->request->url());
            return $response->withRedirect($this->request->url());
        } elseif ($this->request->has('delall')) {
            foreach (LaravelLogViewer::getFiles(true) as $file) {
                app('files')->delete(LaravelLogViewer::pathToLogFile($file));
            }
            // return $this->redirect($this->request->url());
            return $response->withRedirect($this->request->url());
        }

        $response->write(app('view')->make('laravel-log-viewer.log', [
            'logs'         => LaravelLogViewer::all(),
            'files'        => LaravelLogViewer::getFiles(true),
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
