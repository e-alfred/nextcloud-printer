<?php

namespace OCA\Printer\Controller;

use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IRequest;
use Symfony\Component\Process\Process;
use OCA\Printer\Service\Printer;
use Symfony\Component\Process\Exception\ProcessFailedException;

class PrinterController extends Controller
{
    /**
     * @var OC\L10N\LazyL10N
     */
    protected $language;

    /**
     * @var Printer
     */
    protected $printer;

    public function __construct(string $appName, IRequest $request, Printer $printer)
    {
        parent::__construct($appName, $request);

        $this->language = \OC::$server->getL10N('printer');
        $this->printer = $printer;
    }

    /**
     * @NoAdminRequired
     */
    public function printfile(string $sourcefile, string $orientation): JSONResponse
    {
        $file = \OC\Files\Filesystem::getLocalFile($sourcefile);

        $success = [
            'response' => 'success',
            'msg' => $this->language->t('Print succeeded!'),
        ];

        $error = [
            'response' => 'error',
            'msg' => $this->language->t('Print failed'),
        ];

        if (!$this->printer->isValidOrirentation($orientation)) {
            return new JSONResponse($error);
        }

        try {
            $this->printer->print($file, $orientation);

            return new JSONResponse($success);
        } catch (ProcessFailedException $exception) {
            return new JSONResponse($error);
        }
    }
}
