<?php
App::uses('AppController', 'Controller');
/**
 * Profilemodes Controller
 *
 * @property Post $Post
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class ProfilemodesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');

        public function beforeFilter(){
            parent::beforeFilter();
            $this->Auth->allow(array('profile_mode','app_profile_mode','app_viewprofile_mode','aap_position','app_profilemode_delete'));
        }
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Post->recursive = 0;
		$this->set('profilemodes', $this->Paginator->paginate());
	}
       

	public function profile_mode() {
//            debug($this->request->data);
//            exit;
            if ($this->request->is('post')) {
                if($this->Profilemode->save($this->request->data)){
                       $this->Session->setFlash(__('Profile has been set'));
                       $this->redirect(array('controller'=>'users','action'=>'index'));
                }else{
                       $this->Session->setFlash(__('please try again'));
                        $this->redirect(array('controller'=>'users','action'=>'profile_mode'));
                }
                
            }

	} 
      	public function app_profile_mode() {
            configure::write('debug',0);
            $this->layout='ajax'; 
//            debug($this->request->data);
//            exit;
            if ($this->request->is('post')) {
                if($this->Profilemode->save($this->request->data)){
                       $response['msg']="Profile has been set";
                       $response['user_id']=$this->request->data['Profilemode']['user_id'];
                       $response['modetype']=$this->request->data['Profilemode']['modetype'];
                       $response['description']=$this->request->data['Profilemode']['description'];
                       $response['longitude']=$this->request->data['Profilemode']['longitude'];
                       $response['latitude']=$this->request->data['Profilemode']['latitude'];
                       $response['error']='1';
                       $this->set('response', $response);
                       
                }else{
                       $response['msg']="profile information not found";
                       $response['error']='0';
                       $this->set('response', $response);
                }
                
            }else {
				$response['error'] = '0';
                                $response['msg'] = 'No Input found';
                                $this->set('response', $response);
			}
          $this->render('ajax');      

	}
    public function app_viewprofile_mode($user_id=null){
         configure::write('debug',0);
         $this->layout='ajax'; 
         $viewmod=$this->Profilemode->find('all',array('conditions'=>array('Profilemode.user_id'=>$user_id)));
//         debug($viewmod);
//         exit;
         if($viewmod){
             $response['error']="1";
             $response['list']=$viewmod;
             $response['msg']="All Mode List";
             $this->set('response',$response);
         }else{
             $response['error']="0";
             $response['msg']="No Mode List found";
             $this->set('response',$response);
         }
         $this->render('ajax');  

    }        
    
    public function aap_position($id=null,$lat=null,$long=null){
         $this->layout="ajax";
//        $this->request->data['latitude'] = "30.712455339929985";
//        $this->request->data['longitude'] = "76.85492746531963";
        $this->request->data['raduis'] = '.1';
//        $id=43;
        
          $this->request->data['latitude'] = $lat;
          $this->request->data['longitude'] = $long;
//          $this->request->data['raduis'] = $rad;
                     ob_start();
            var_dump($this->request->data);
            $c = ob_get_clean();
         $fc = fopen('files' . DS . 'detail.txt', 'w');
            fwrite($fc, $c);
        fclose($fc);
        
        $users_lists = $this->Profilemode->query(
                    'SELECT * ,
                        get_distance_in_miles_between_geo_locations("' . $this->request->data['latitude'] . '","' 
                    . $this->request->data['longitude'] . '",`latitude`,`longitude`) as distance FROM `profilemodes` having distance<"' 
                    . $this->request->data['raduis'] . '" AND `user_id`="'.$id.'" Order by distance');
        
//        debug($users_lists);exit;
        if($users_lists){
             $response['error']="1";
             $response['list']=$users_lists;
             $response['msg']="Success";
             $this->set('response',$response);
         }else{
             $response['error']="0";
             $response['msg']="not found";
             $this->set('response',$response);
         }
         $this->render('ajax');  
        
    }
    
    	public function profilemode_delete($id = null) {
		$this->Profilemode->id = $id;
		if (!$this->Profilemode->exists()) {
			throw new NotFoundException(__('Invalid Profilemode'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Profilemode->delete()) {
			$this->Session->setFlash(__('The user has been deleted.'));
		} else {
			$this->Session->setFlash(__('The user could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
        public function app_profilemode_delete($id = null) {
            configure::write('debug',2);
            $this->layout="ajax";
            
		$this->Profilemode->id = $id;
                
//               
//		if (!$this->Profilemode->exists()) {
//			$response['error']='0';
//                        $response['msg']='invalid input';
//		}
//                 debug($this->Profilemode->id);
//                exit;
//		$this->request->allowMethod('post', 'delete');
		if ($this->Profilemode->delete()) {
			$response['error']='1';
                        $response['msg']='Profile deleted';
                        $this->set('response',$response);
		} else {
			$response['error']='0';
                        $response['msg']='Please try again';
                        $this->set('response',$response); 
		}
                 $this->render('ajax');
	}
   }