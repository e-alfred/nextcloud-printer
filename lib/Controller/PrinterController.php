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
		 * @param (string) $source - filename
		 * @param (string) $orientation - Orientation of printed file
		 */
	  public function printfile($source, $orientation) {
	  		if($orientation == "landscape") {
					shell_exec('lpr ' . $source);
	  			return new JSONResponse(
							array(
									'response' => 'success',
									'msg' => $this->language->t('Print succeeded!')
							)
					);
	  		}

				if($orientation == "portrait"){
					shell_exec('lpr -o orientation-requested=4 ' . $source);
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
