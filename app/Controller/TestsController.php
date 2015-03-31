<?php

App::uses('AppController', 'Controller');

class TestsController extends AppController {

        public function beforeFilter() {

            $this->Auth->Allow(array('test','index'));
            
            

        }

	public function index() {

		$indexInfo['description'] = "App user Registration(post method)(2-d array) ";

		$indexInfo['url'] = FULL_BASE_URL.$this->webroot."Users/app_userregistration";

		$indexInfo['parameters'] = 

                '<b>data[User][username] - </b>Username<br>

		<b>data[User][email]</b>-User email<br>
 
		<b>data[User][password]</b>- password<br>

		<b>data[User][longitude]</b>-longitude<br>

		<b>data[User][latitude]</b>- latitude<br>
                
                <b>data[User][role]</b>- User role<br>

		<b>data[User][status]</b>- 0<br>';

                $indexarr[] = $indexInfo;
                
                $indexInfo['description'] = "App user Registration(post method)(2-d array) ";

		$indexInfo['url'] = FULL_BASE_URL.$this->webroot."Users/app_userlogin";

		$indexInfo['parameters'] = 

                '<b>data[User][username] - </b>Username<br>

		<b>data[User][password]</b>-User password<br> ';

                $indexarr[] = $indexInfo;
                
                
                $indexInfo['description'] = "App profile mode ";

		$indexInfo['url'] = FULL_BASE_URL.$this->webroot."Profilemodes/app_profile_mode";

		$indexInfo['parameters'] = '<b>data[Profilemode][user_id] - </b>user id<br>

		<b>data[Profilemode][modetype] </b>-profile type<br>
                
                <b>data[Profilemode][description] </b>-description<br> 

                <b>data[Profilemode][longitude] </b>-set longitude<br> 

                <b>data[Profilemode][latitude] </b>-set latitude<br> ';

                $indexarr[] = $indexInfo;
                
                
                $indexInfo['description'] = "App view profile mode ";

		$indexInfo['url'] = FULL_BASE_URL.$this->webroot."Profilemodes/app_viewprofile_mode/(user_id)";

		$indexInfo['parameters'] = ' ';

                $indexarr[] = $indexInfo;
                
                
                $indexInfo['description'] = "App profile  position ";

		$indexInfo['url'] = FULL_BASE_URL.$this->webroot."Profilemodes/aap_position/(user_id)/(latitude)/(longitude)";

		$indexInfo['parameters'] = ' ';

                $indexarr[] = $indexInfo;
                
                
                  $indexInfo['description'] = "App Forgot password ";

		$indexInfo['url'] = FULL_BASE_URL.$this->webroot."Users/app_userforgotpwd";

		$indexInfo['parameters'] = '<b>data[User][email] - </b>user id<br> ';

                $indexarr[] = $indexInfo;
                
                
                 $indexInfo['description'] = "App profile mode delete ";

		$indexInfo['url'] = FULL_BASE_URL.$this->webroot."Profilemodes/app_profilemode_delete(profile_id)";

		$indexInfo['parameters'] = ' ';

                $indexarr[] = $indexInfo;

        
                

                

                $this->set('IndexDetail',$indexarr);

	}

	 

	

    

     public function time_elapsed_string($datetime, $full = false) {

            $now = new DateTime;

            $ago = new DateTime($datetime);

            $diff = $now->diff($ago);



            $diff->w = floor($diff->d / 7);

            $diff->d -= $diff->w * 7;



            $string = array(

                'y' => 'year',

                'm' => 'month',

                'w' => 'week',

                'd' => 'day',

                'h' => 'hour',

                'i' => 'minute',

                's' => 'second',

            );

            foreach ($string as $k => &$v) {

                if ($diff->$k) {

                    $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');

                } else {

                    unset($string[$k]);

                }

            }



            if (!$full) $string = array_slice($string, 0, 1);

            return $string ? implode(', ', $string) . ' ago' : 'just now';

        }

        
 

        

        



}