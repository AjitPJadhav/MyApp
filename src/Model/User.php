<?php

uses('AppModel', 'Model');

/**
 *  User
 */
class User extends AppModel {

    /**
     * Containable
     * @var type 
     */
    public $actsAs = array('Containable', 'Common');

    /**
     * Constructor for this Model
     */
    public function __construct() {
        parent::__construct();
        $this->commonComponentObj = new CommonComponent();
    }
    /*
     *  Check Mobile No Is Verified Or Not
     */

    public function getUsersData($data) {
        $mobileNo = isset($data['User']['mobile']) && !empty($data['User']['mobile']) ? $data['User']['mobile'] : '';
        try {
            return $this->find('first', array(
                        'fields' => array('User.id', 'User.verified','User.mobile'),
                        'conditions' => array(
                            'User.mobile' => $mobileNo
                        )
            ));
        } catch (Exception $ex) {
            return '';
        }
    }
/*
     *  Save or update users data
     */

    public function saveData($userData) {
        if (!empty($userData) && is_array($userData)) {
            try {
                /* Set $userId variable */
                $userId = isset($userData['User']['id']) && $userData['User']['id'] > 0 ? $userData['User']['id'] : 0;
                if ($userId < 1) {
                    /* If user id is not present then add new record */
                    $this->create();
                }
                $this->recursive = -1;
                return $this->saveAll($userData);
            } catch (Exception $ex) {
                error_log($ex->getMessage());
            }
        }
        return false;
    }
}
