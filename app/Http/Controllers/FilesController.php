<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileStoreRequest;
use App\Http\Requests\FileUpdateRequest;
use App\Http\Requests\FileUploadRequest;
use App\Jobs\LintContent;
use App\Jobs\ProcessFile;
use App\Models\File;
use App\Models\Version;
use App\Support\Linters;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\View\Factory;
use Illuminate\View\View;

/**
 * Class FilesController.
 *
 * @author annejan@badge.team
 */
class FilesController extends Controller
{
    /**
     * Show file content, public method ツ.
     *
     * @param File $file
     *
     * @return Application|Factory|View
     */
    public function show(File $file)
    {
        return view('files.show')
            ->with('file', $file);
    }

    /**
     * Download file content, public method ツ what could go wrong..
     *
     * @param File $file
     *
     * @return Response
     */
    public function download(File $file): Response
    {
        return response($file->content)
            ->header('Cache-Control', 'no-cache private')
            ->header('Content-Description', 'File Transfer')
            ->header('Content-Type', $file->mime)
            ->header('Content-length', (string) strlen($file->content))
            ->header('Content-Disposition', 'attachment; filename='.$file->name)
            ->header('Content-Transfer-Encoding', 'binary');
    }
}
