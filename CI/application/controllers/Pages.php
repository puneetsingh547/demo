<?php
class Pages extends CI_Controller {
    
    public function __construct(){

        parent::__construct();

        $this->load->model('Model_page');

        $this->load->helper('url_helper');

        $this->load->helper('url');

    }
    public function index(){
        
        $this->load->view("templates/header.php");
        $this->load->view("pages/home");
    }
    public function send(){
        
        $this->Model_page->insert_data();
        
    }
    public function show(){

        $all_data = $this->Model_page->get_data();

       $this->load->view('pages/table', $all_data);

    }
}



?>
