<?php

namespace OCA\Printer\Controller;

use OC_Util;
use OCA\Printer\Config;
use OCA\Printer\Service\Printer;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IRequest;
use OCP\IUserSession;
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

    /**
     * @var Config
     */
    protected $config;

    public function __construct(string $appName, IRequest $request, Printer $printer, Config $config)
    {
        parent::__construct($appName, $request);

        OC_Util::setupFS();

        $this->language = \OC::$server->getL10N('printer');
        $this->printer = $printer;
        $this->config = $config;

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

        $notAllowed = [
            'response' => 'error',
            'msg' => $this->language->t('User not allowed'),
        ];

        $user = \OC::$server[IUserSession::class]->getUser();

        if (!$user || $this->config->isDisabledForUser($user)) {
            return new JSONResponse($notAllowed);
        }

        if (!$this->printer->isValidOrientation($orientation)) {
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
