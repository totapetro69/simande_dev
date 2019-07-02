 <?php defined('BASEPATH') OR exit('No direct script access allowed');
 
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

class Rest_result extends REST_Controller {
    function __construct($config='rest')
    {
        // Construct the parent class
        parent::__construct($config);

    }
    function post_result($result){
		if($result){
            $this->response($result,REST_Controller::HTTP_OK);
        }else{
            $this->response($result,REST_Controller::HTTP_BAD_REQUEST);
        }
	}
}
?>