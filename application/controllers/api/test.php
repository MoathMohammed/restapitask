<?php
require APPPATH.'libraries/ReST_Controller.php';
require APPPATH . 'libraries/Format.php';


class Test extends ReST_Controller{

    public function __construct(){
        
        parent::__construct();
        //load database
        $this->load->database();
        $this->load->model(array("api/test_model"));
        $this->load->library(array("form_validation"));
        $this->load->helper("security");

}


    /*
    Insert: POST REQUEST TYPE
    UPDATE: PUT REQUEST TYPE
    DELETE: DELETE REQUEST TYPE
    LIST: GET REQUEST TYPE
    */
//POST:<project_url>/index.php/test
//insert data method
    public function index_post() {
/*

//inserting using body params:
        $userinfo = json_decode(file_get_contents("php://input"));
        $name = isset($userinfo->name)? $userinfo->name : "";
        $email = isset($userinfo->email)? $userinfo->email : "";
        $phone = isset($userinfo->phone)? $userinfo->phone : "";
        $gender = isset($userinfo->gender)? $userinfo->gender : "";


         if(!empty($name) && !empty($email) && !empty($phone) && !empty($gender)){
            // all values are filled
            $user = array(
                "name" => $name,
                "email" => $email,
                "phone" => $phone,
                "gender" => $gender
            );
            if($this->test_model->insert_user($user)){

                $this->response(array(
                    "status" => 1,
                    "message" => "you have added a new user"
                ), REST_Controller::HTTP_OK);
            }else{
                $this->response(array(
                    "status" => 0,
                    "message" => "faild to add user"
                ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }
        }else{
            $this->response(array(
                "status" => 0,
                "name" => $name,
                "email" => $email,
                "phone" => $phone,
                "gender" => $gender,
                "message" => "Please fill all the data"
            ), REST_Controller:: HTTP_NOT_FOUND);
        }
*/

//inserting using form data
        $name = $this->security->xss_clean($this->input->post("name"));
        $email = $this->security->xss_clean($this->input->post("email"));
        $phone = $this->security->xss_clean($this->input->post("phone"));
        $gender = $this->security->xss_clean($this->input->post("gender"));


        $this->form_validation->set_rules("name","your Name","required");
        $this->form_validation->set_rules("email","your Email","required|valid_email");
        $this->form_validation->set_rules("phone","your phone","required");
        $this->form_validation->set_rules("gender","gender","required");


        if ($this->form_validation->run() === FALSE) {
           // we have some errors
           $this->response(array(
            "status" => 0,
           "name" => $name,
           "email" => $email,
           "phone" => $phone,
           "gender" => $gender,
           "message" => "Please fill all the data in the correct way"),
             REST_Controller:: HTTP_NOT_FOUND);
        }else{
            if(!empty($name) && !empty($email) && !empty($phone) && !empty($gender)){
                // all values are filled
                $user = array(
                    "name" => $name,
                    "email" => $email,
                    "phone" => $phone,
                    "gender" => $gender
                );
                if($this->test_model->insert_user($user)){
    
                    $this->response(array(
                        "status" => 1,
                        "message" => "you have added a new user"
                    ), REST_Controller::HTTP_OK);
                }else{
                    $this->response(array(
                        "status" => 0,
                        "message" => "faild to add user"
                    ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                }
        }

    }

        // echo "This is post";
    }
//PUT:<project_url>/index.php/test/index_put
//update data method    
    public function index_put() {
        // echo "This is put";
        $updateddata= json_decode(file_get_contents("php://input"));
        if (isset($updateddata->id) && isset($updateddata->name) && isset($updateddata->email) && isset($updateddata->phone) && isset($updateddata->gender) ) {
            $user_id = $updateddata->id;
            $user_info= array(
                "name"=>$updateddata->name,
                "email"=>$updateddata->email,
                "phone"=>$updateddata->phone,
                "gender"=>$updateddata->gender
            );
if ($this->test_model->update_user($user_id,$user_info)) {
    $this->response(array(
        "status"=>1,
        "messsage"=> "updated user info",
        "name"=>$updateddata->name,
        "email"=>$updateddata->email,
        "phone"=>$updateddata->phone,
        "gender"=>$updateddata->gender
    ), REST_Controller::HTTP_OK);
}else{
    $this->response(array(
        "status"=>0,
        "messsage"=> "faild to update"
    ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
}

        }else{
        $this->response(array(
            "status"=>0,
            "messsage"=> "All fields are requierd"
        ), REST_Controller::HTTP_NOT_FOUND);
        }
        
    }
//DELETE:<project_url>/index.php/test
//DELETE data method  
    public function index_delete() {
        $requesteddata = json_decode(file_get_contents("php://input"));
        $user_id= $this->security->xss_clean($requesteddata->user_id);
        if($this->test_model->delete_user($user_id)){
            $this->response(array(
                "status"=>1,
                "message"=>"you have deleted user number".$user_id
            ),
            REST_Controller::HTTP_OK
        );
        }else{
            $this->response(array(
                "status"=>0,
                "message"=>"failed to delete user ".$user_id
            ),
            REST_Controller::HTTP_NOT_FOUND);
        }

        // echo "This is delete";
    }
//SELECT:<project_url>/index.php/test
//SELECT data method  
    public function  index_get(){
        // echo "This is get";
        $users = $this->test_model->get_users();
        // print_r($users);
        if(count($users) > 0){

        $this->response(array(
            "status" => 1,
            "message" => "the user found",
            "data" => $users
           
        ), REST_Controller::HTTP_OK);
    }else{
        $this->response(array(
            "status" => 0,
            "message" => "the user not found",
            "data" => $users
           
        ), REST_Controller::HTTP_NOT_FOUND);

    }
    }
}

?>