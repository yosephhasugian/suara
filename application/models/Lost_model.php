<?php
class Lost_model extends CI_Model {

    public function get_all() {
        return $this->db->order_by('id','DESC')->get('lost_found')->result();
    }

    public function insert($data) {
        return $this->db->insert('lost_found', $data);
    }

    public function get_by_id($id) {
        return $this->db->get_where('lost_found', ['id'=>$id])->row();
    }

    public function update($id, $data) {
        return $this->db->where('id',$id)->update('lost_found',$data);
    }

    public function delete($id) {
        return $this->db->delete('lost_found',['id'=>$id]);
    }
}