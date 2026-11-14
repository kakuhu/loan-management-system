<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class LegacyPageController extends Controller
{
    public function index()
    {
        return $this->show('index.php');
    }

    public function show(string $page = 'index.php')
    {
        $legacyBase = dirname(base_path()) . DIRECTORY_SEPARATOR . 'loan';
        $requestedPath = trim($page, '/');

        if ($requestedPath === '') {
            $requestedPath = 'index.php';
        }

        $candidates = [$requestedPath];

        if (!str_ends_with($requestedPath, '.php') && !str_ends_with($requestedPath, '.html')) {
            $candidates[] = $requestedPath . '.php';
            $candidates[] = $requestedPath . '/index.php';
        }

        foreach ($candidates as $candidate) {
            $fullPath = $legacyBase . DIRECTORY_SEPARATOR . $candidate;
            $realPath = realpath($fullPath);

            if ($realPath === false) {
                continue;
            }

            $baseNormalized = str_replace('\\', '/', $legacyBase);
            $realNormalized = str_replace('\\', '/', $realPath);

            if (!str_starts_with($realNormalized, $baseNormalized . '/')) {
                continue;
            }

            if (str_ends_with($realPath, '.php')) {
                ob_start();
                include $realPath;
                $contents = ob_get_clean();

                return response($contents, 200)
                    ->header('Content-Type', 'text/html; charset=UTF-8');
            }

            return response()->file($realPath);
        }

        abort(404, 'Legacy page not found.');
    }
}
