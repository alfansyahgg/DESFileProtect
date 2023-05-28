<?php
class Dekripsi_model extends CI_Model
{
    public function getAllDekripsi()
    {
        $this->db->select('file.*, users.nama as nama_user');
        $this->db->join(
            'users',
            'file.id_user=users.id_user',
            'inner'
        );

        return $this->db->get('file')->result_array();
    }

    public function getAllFile($id_user)
    {
        return $this->db->get_where('file', ['id_user' => $id_user])->result_array();
    }

    public function getWhereFile($id_file)
    {
        return $this->db->get_where('file', ['id_file' => $id_file])->row_array();
    }

    public function getDekripsiById($id_file)
    {
        return $this->db->get_where('file', ['id_file' => $id_file])->row_array();
    }

    public function hapusDataDekripsi($id_file)
    {
        $this->db->where('id_file', $id_file);
        $this->db->delete('file');
    }

    public function formDataDekripsi($data)
    {
        $this->db->where('id_file', $data);
        $this->db->update('file', $data);
        // redirect('dekripsi');
    }

    public function updateStatusDekrip($id_file, $data)
    {
        $this->db->where('id_file', $id_file);
        $this->db->update('file', $data);
    }
}
