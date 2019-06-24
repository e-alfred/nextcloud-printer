<?php
namespace OCA\Printer\Controller;

use OCP\AppFramework\Controller;
use OCP\IRequest;
use OC\Files\Filesystem;
use OCP\AppFramework\Http\JSONResponse;


class PrinterController extends Controller {

		protected $language;

		public function __construct($appName, IRequest $request) {

				parent::__construct($appName, $request);

				// get i10n
				$this->language = \OC::$server->getL10N('printer');

		}

		/**
		 * callback function to get md5 hash of a file
		 * @NoAdminRequired
		 * @param (string) $sourcefile - filename
		 * @param (string) $orientation - Orientation of printed file
		 */
	  public function printfile($sourcefile, $orientation) {
	  		if($orientation === "landscape") {
					$filefullpath = \OC\Files\Filesystem::getLocalFile($sourcefile);
					exec('lpr "' . $filefullpath . '"');
	  			return new JSONResponse(
							array(
									'response' => 'success',
									'msg' => $this->language->t('Print succeeded!')
							)
					);
	  		}

				if($orientation === "portrait"){
					$filefullpath = \OC\Files\Filesystem::getLocalFile($sourcefile);
					exec('lpr -o orientation-requested=4 "' . $filefullpath . '"');
						return new JSONResponse(
								array(
										'response' => 'success',
										'msg' => $this->language->t('Print succeeded!')
								)
						);
				} else {
						return new JSONResponse(
								array(
										'response' => 'error',
										'msg' => $this->language->t('Print failed')
								)
						);
				};
  }
}
