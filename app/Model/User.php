<?php
App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
/**
 * User Model
 *
 */
class User extends AppModel {

/**
 * Use database config
 *
 * @var string
 */
	public $useDbConfig = 'fwtest';

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'id';

/**
 * Validation rules
 *
 * @var array
 */
        public $hasMany = array(
		'Profilemode' => array(
			'className' => 'Profilemode',
			'foreignKey' => 'user_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		));
public function delete($id = null, $cascade = true) {
$relatedImages = $this->find('all', array('conditions' => array('User.id' => $id)));
unlink('files/images/' . $relatedImages[0]['User']['image']);
parent::delete($id, $cascade);
return true;
}        
	
public function beforeSave($options = array()) {
    if (isset($this->data[$this->alias]['password'])) {
        $passwordHasher = new BlowfishPasswordHasher();
        $this->data[$this->alias]['password'] = $passwordHasher->hash(
            $this->data[$this->alias]['password']
        );
    }
    return true;}
}
