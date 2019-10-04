<?php
namespace OCA\Cuckoo\Controller;

use OCP\AppFramework\Controller;
use OCP\IRequest;
use OC\Files\Filesystem;
use OCP\AppFramework\Http\JSONResponse;


class CuckooController extends Controller {

		protected $language;

		public function __construct($appName, IRequest $request) {

				parent::__construct($appName, $request);

				// get i10n
				$this->language = \OC::$server->getL10N('cuckoo');

		}


	  public function send($source) {

		// ## Set yout sandbox's api url for file submit
		// eg: http://YOU_HOST:PORT/tasks/create/file
		$cuckoo_api_url = 'http://_PLEASE_EDIT_HERE_:_AND_HERE_WITH_PORT_/tasks/create/file';

		$myfile = Filesystem::getLocalFile($source);

		// initialise the curl request
		$request = curl_init($cuckoo_api_url);

		$cfile = curl_file_create($myfile);
		$post_file=array('file' => $cfile);
//		$post_file = '';

		// send a file
		curl_setopt($request, CURLOPT_POST, true);
		curl_setopt($request, CURLOPT_POSTFIELDS,$post_file);

		// output the response
		curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
		$result =  curl_exec($request);

		// close the session
		curl_close($request);
		return $result;

 	  }

		/**
		 * callback function to get md5 hash of a file
		 * @NoAdminRequired
		 * @param (string) $source - filename
		 * @param (string) $type - hash algorithm type
		 */
	  public function check($source, $type) {
	  		if(!$this->checkAlgorithmType($type)) {
	  			return new JSONResponse(
							array(
									'response' => 'error',
									'msg' => $this->language->t('The algorithm type "%s" is not a valid or supported algorithm type.', array($type))
							)
					);
	  		}

				if($hash = $this->getHash($source, $type)){
						return new JSONResponse(
								array(
										'response' => 'success',
										'msg' => $hash
								)
						);
				} else {
						return new JSONResponse(
								array(
										'response' => 'error',
										'msg' => $this->language->t('File not found.')
								)
						);
				};

	  }

	  protected function getHash($source, $type) {

	  	if($info = Filesystem::getLocalFile($source)) {
	  			return hash_file($type, $info);
	  	}

	  	return false;
	  }

	  protected function checkAlgorithmType($type) {
	  	$list_algos = hash_algos();
	  	return in_array($type, $this->getAllowedAlgorithmTypes()) && in_array($type, $list_algos);
	  }

	  protected function getAllowedAlgorithmTypes() {
	  	return array(
				'md5',
				'sha1',
				'sha256',
				'sha384',
				'sha512',
				'crc32'
			);
		}
}

