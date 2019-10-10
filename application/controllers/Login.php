<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
class Login extends CI_Controller {
 
    public function __construct() {
        parent::__construct();
        
        $this->load->helper(array('url','form'));
        $this->load->library('session');
        $this->load->database();
    }    
 
    public function index(){
      $this->load->view('view_login');
    }
  
    public function googleCaptachStore(){
        $data = array('name' => $this->input->post('name'),
                      'email' => $this->input->post('email'), 
                      'mobile_number' => $this->input->post('mobile_number'), 
                     );
        $recaptchaResponse = trim($this->input->post('g-recaptcha-response'));
        $userIp=$this->input->ip_address();
        $secret='6Lc7-f8SAAAAAPcKkoyM8cVeSPEqzr--wOVlii1K'; // ini adalah Secret key yang didapat dari google, silahkan disesuaikan
        $credential = array(
              'secret' => $secret,
              'response' => $this->input->post('g-recaptcha-response')
          );
        $verify = curl_init();
        curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($verify, CURLOPT_POST, true);
        curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($credential));
        curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($verify);

        $status= json_decode($response, true);

        if($status['success']){ 
            //$this->db->insert('users',$data); 
            $this->session->set_flashdata('message', 'Google Recaptcha Successful');
        }else{
            $this->session->set_flashdata('message', 'Sorry Google Recaptcha Unsuccessful!!');
        }
        redirect(base_url('login'));
    }
 
}
