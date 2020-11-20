<?php

namespace OCA\Printer\Controller;

use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IRequest;
use Symfony\Component\Process\Process;

class PrinterController extends Controller
{
    protected $language;

    public function __construct($appName, IRequest $request)
    {
        parent::__construct($appName, $request);

        $this->language = \OC::$server->getL10N('printer');
    }

    /**
     * callback function to get md5 hash of a file.
     *
     * @NoAdminRequired
     *
     * @param (string) $sourcefile  - filename
     * @param (string) $orientation - Orientation of printed file
     */
    public function printfile($sourcefile, $orientation)
    {
        $filefullpath = \OC\Files\Filesystem::getLocalFile($sourcefile);

        $options = [
            'landscape' => [
                'lpr',
                $filefullpath,
            ],
            'portrait' => [
                'lpr',
                '-o',
                'orientation-requested=4',
                $filefullpath,
            ],
        ];

        $success = [
            'response' => 'success',
            'msg' => $this->language->t('Print succeeded!'),
        ];

        $error = [
            'response' => 'error',
            'msg' => $this->language->t('Print failed'),
        ];

        if (!isset($options[$orientation])) {
            return new JSONResponse($error);
        }

        $process = new Process($options[$orientation]);
        $process->run();

        if ($process->isSuccessful()) {
            return new JSONResponse($success);
        }

        return new JSONResponse($error);
    }
}
