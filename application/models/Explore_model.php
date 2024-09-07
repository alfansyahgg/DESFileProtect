<?php
class Explore_model extends CI_Model
{
    public function getAllPengguna()
    {
        $this->db->select('*');
        $this->db->join(
            'role',
            'users.role_id=role.id_role',
            'inner'
        );
        return $this->db->get('users')->result_array();
    }

    public function getAllAvailableFile($id_user)
    {
        $sql = "select id_file from file_user where id_user=$id_user";
        $res = $this->db->query($sql);
        $registeredFile = $res->result_array();
        $sizeRegisteredFile = sizeof($registeredFile);
        if ($sizeRegisteredFile > 0) {
            $val = "";
            foreach ($registeredFile as $item) {
                $val .= $item['id_file'] . ',';
            }
            $val = substr_replace($val, "", -1);
            $queryIn = "WHERE id_file not in (" . $val . ")";
        } else {
            $queryIn  = "";
        }

        $sqlFile = "SELECT * FROM `file` $queryIn";
        $res = $this->db->query($sqlFile);
        $exploreFile = $res->result_array();

        return $exploreFile;
    }

    public function myfile($id_user)
    {
        $sql = "select id_file from file_user where id_user=$id_user";
        $res = $this->db->query($sql);
        $registeredFile = $res->result_array();
        $sizeRegisteredFile = sizeof($registeredFile);
        $exploreFile = array();
        if ($sizeRegisteredFile > 0) {
            $val = "";
            foreach ($registeredFile as $item) {
                $val .= $item['id_file'] . ',';
            }
            $val = substr_replace($val, "", -1);
            $queryIn = "WHERE id_file in (" . $val . ")";
            $sqlFile = "SELECT * FROM `file` $queryIn";
            $res = $this->db->query($sqlFile);
            $exploreFile = $res->result_array();
        }



        return $exploreFile;
    }

    public function addFileUser($data)
    {
        return $this->db->insert('file_user', $data);
    }
}
