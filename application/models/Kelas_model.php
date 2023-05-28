<?php
class Kelas_model extends CI_Model
{
    public function getAllKelas()
    {
        $this->db->select('*');
        return $this->db->get('kelas')->result_array();
    }

    public function tambahDataKelas()
    {
        $data = [
            "nama_kelas" => $this->input->post('nama_kelas', true),
        ];
        // var_dump($data);
        // die();

        $this->db->insert('kelas', $data);
        redirect('kelas');
    }

    public function hapusDataKelas($id_kelas)
    {
        $this->db->where('id_kelas', $id_kelas);
        $this->db->delete('kelas');
    }

    public function getKelasById($id_kelas)
    {
        return $this->db->get_where('kelas', ['id_kelas' => $id_kelas])->row_array();
    }

    public function ubahDataKelas()
    {
        $data = [
            "nama_kelas" => $this->input->post('nama_kelas', true),
        ];
        $this->db->where('id_kelas', $this->input->post('id_kelas'));
        $this->db->update('kelas', $data);
        redirect('kelas');
    }
}