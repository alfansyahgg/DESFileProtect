<?php
class Pasien_model extends CI_Model
{
    public function getAllPasien()
    {
        $this->db->select('*');
        // $this->db->join(
        //     'kelas',
        //     'pasien.id_pasien=kelas.id_pasien',
        //     'inner'
        // );
        return $this->db->get('pasien')->result_array();
    }

    public function tambahDataPasien()
    {
        $data = [
            "id_pasien" => $this->input->post('id_pasien', true),
            "nama" => $this->input->post('nama', true),
            "tempat_lahir" => $this->input->post('tempat_lahir', true),
            "tanggal_lahir" => $this->input->post('tanggal_lahir', true),
            "nomor_hp" => $this->input->post('nomor_hp', true),
            "alamat" => $this->input->post('alamat', true),
        ];

        $this->db->insert('pasien', $data);
        redirect('pasien');
    }

    public function hapusDataPasien($id_pasien)
    {
        $this->db->where('id_pasien', $id_pasien);
        $this->db->delete('pasien');
    }

    public function getPasienById($id_pasien)
    {
        return $this->db->get_where('pasien', ['id_pasien' => $id_pasien])->row_array();
    }

    public function ubahDataPasien()
    {
        $data = [
            "id_pasien" => $this->input->post('id_pasien', true),
            "nama" => $this->input->post('nama', true),
            "tempat_lahir" => $this->input->post('tempat_lahir', true),
            "tanggal_lahir" => $this->input->post('tanggal_lahir', true),
            "nomor_hp" => $this->input->post('nomor_hp', true),
            "alamat" => $this->input->post('alamat', true),
        ];
        $this->db->where('id_pasien', $this->input->post('id_pasien'));
        $this->db->update('pasien', $data);
        redirect('pasien');
    }
}
