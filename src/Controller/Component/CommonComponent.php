<?php

uses('Component', 'Controller');

class CommonComponent extends Component {

    public function __construct() {
        $this->User = ClassRegistry::init('User');
    }
    public $components = array(
        'Session',
        'Auth' => array(
            'loginAction' => array('controller' => 'Users', 'action' => 'login', 'home'),
            'loginRedirect' => array('controller' => 'Posts', 'action' => 'index'), 
            'logoutRedirect' => array('controller' => 'Posts', 'action' => 'home') ) );

    /**
     * generate 10 digit random key
     * 
     * @return type
     */
    public function randomkey() {
        $ranKey = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
        return $ranKey;
    }

    /**
     *   Validate data: match sercretkey and mobile number.
     *   @input secret_key,mobile_number
     *   @output status 
     *  100: mobile number not exist,
     *  101: mobile number is matched,
     *  201: mobile number exist in db, 
     *  202: Secret key is not matched)
     */
    public function validateData($data) {
        $response = array();
        /* Match secret key  with database */
        $matchedSecretKeyCount = $this->Setting->checkSecretKeyExistOrNot($data);
        if ($matchedSecretKeyCount > 0) {
            /* Check mobile number is already exist or not */
            $matchedMobileNoCount = $this->User->checkMobileNoExistOrNot($data);

            /* if mobile number not exist in database send status 100 and if exist then 101 */
            if ($matchedMobileNoCount < 1) {
                $response['status'] = 100;
            } else {
                $response['status'] = 101;
            }//Matched Mobile Number
        } else {
            $response['status'] = 102;
            $response['response'] = "Secret key is not matched.";
        }//valid Secret key

        return $response;
    }
}

?>
