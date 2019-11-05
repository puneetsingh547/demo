<?php  
class Model_page extends CI_Model{

    public function __construct (){
       parent:: __construct();
       $this->load->database();
    }
    
    public function insert_data(){
       $data = array(
           'name' => $this->input->post("name"),
           'email' => $this->input->post("email"),
           'number' => $this->input->post('number')
       );
       $this->db->insert('users', $data);
    }
    
    public function get_data(){
       $query = $this->db->get('users');
       return array('all_data'=>$query->result());
    }

}


?>
