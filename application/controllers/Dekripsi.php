<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . '/libraries/dompdf/autoload.inc.php';


use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class Dekripsi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Dekripsi_model');
        $this->load->helper('date');

        if (!isset($this->session->email)) {
            redirect('auth');
        }
    }

    public function index()
    {
        $data['title'] = 'Dekripsi';
        $data['user'] = $this->db->get_where('users', ['email' =>
        $this->session->userdata('email')])->row_array();

        $file = $this->Dekripsi_model->getAllDekripsi();
        $file_user =
            $this->Dekripsi_model->getFileUser();

        $combined = array();
        foreach ($file as $key => $fl) {
            $fl['jumlah_user'] = 0;
            foreach ($file_user as $fus) {
                if ($fl['id_file'] == $fus['id_file']) {
                    $fl['jumlah_user'] = $fus['jumlah_user'];
                }
            }
            $combined[$key] = $fl;
        }

        $data['file'] = $combined;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('Dekripsi/index', $data);
        $this->load->view('templates/footer');
    }

    public function detail($id_file)
    {
        $data['title'] = 'Dekripsi';
        $data['user'] = $this->db->get_where('users', ['email' =>
        $this->session->userdata('email')])->row_array();

        $file = $this->Dekripsi_model->getAllDekripsi();
        $file_user =
            $this->Dekripsi_model->getFileUser($id_file);

        $data['users_file'] = $this->Dekripsi_model->getUserFileInfo($id_file);

        // echo "<pre>";
        // print_r($data);
        // exit();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('Dekripsi/detail', $data);
        $this->load->view('templates/footer');
    }


    public function download($id_file)
    {
        $file = $this->Dekripsi_model->getWhereFile($id_file);

        $name_file = $file['nama_file_enkrip'];
        $data = file_get_contents('./assets/file_encrypt/' . $name_file);

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $name_file . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize('./assets/file_encrypt/' . $name_file));

        echo "$data";
    }

    public function dekrip($id_file)
    {
        $data['user'] = $this->db->get_where('users', ['email' =>
        $this->session->userdata('email')])->row_array();

        $data['title'] = 'Form Dekripsi';
        $this->load->model('Dekripsi_model', 'data');

        $data_file = $this->data->getDekripsiById($id_file);

        $data['data'] = $data_file;

        $password = $this->input->post('password');

        if (password_verify($password, $data_file['password'])) {
            $path = "assets/file_encrypt/" . $data_file['nama_file_enkrip'];
            $extension = explode(".", $data_file['nama_file_enkrip']);
            $extension = end($extension);
            if ($extension == "xls" || $extension == "xlsx") {
                // Membaca file Excel
                $spreadsheet = IOFactory::load($path);

                foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                    // Iterate through all cells
                    foreach ($worksheet->getRowIterator() as $row) {
                        foreach ($row->getCellIterator() as $cell) {
                            // Get the value of the cell
                            $value = $cell->getValue();
                            // Encrypt the value using DES
                            $decryptedValue  = openssl_decrypt($value, 'DES-ECB', $password, 0);
                            if (false === $decryptedValue) {
                                echo openssl_error_string();
                                die;
                            }

                            // Set the encrypted value back to the cell
                            $cell->setValue($decryptedValue);
                        }
                    }
                }

                $dataStatusDekrip = [
                    'status_dekripsi' => 1,
                ];

                $this->Dekripsi_model->updateStatusDekrip($id_file, $dataStatusDekrip);


                // Proses file excel
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment; filename="' . $data_file['nama_file_enkrip'] . '"'); // Set nama file excel nya
                header('Cache-Control: max-age=0');


                // Menyimpan file hasil dekripsi ke dalam file baru
                $outputFileName = "assets/file_decrypt/" . $data_file['nama_file_enkrip'];
                $writer = new Xlsx($spreadsheet);
                $writer->save($outputFileName);
                $data = file_get_contents('./assets/file_decrypt/' . $data_file['nama_file_enkrip']);

                echo "$data";
                $this->session->set_flashdata('berhasil', 'berhasil');
                redirect('Dekripsi');
            } else if ($extension == "txt") {
                $plaintext = "";
                $ciphertext = "";
                $this->load->library('DES');

                $this->load->library('PdfEncryption');
                $path = "assets/file_encrypt/" . $data_file['nama_file_enkrip'];

                $inputFilePath =
                    './assets/file_encrypt/' . $data_file['nama_file_enkrip'];
                $outputFilePath = './assets/file_decrypt/' . $data_file['nama_file'];

                $this->pdfencryption->decryptPdf($password, $inputFilePath, $outputFilePath);
                $dataStatusDekrip = [
                    'status_dekripsi' => 1,
                ];

                $this->Dekripsi_model->updateStatusDekrip($id_file, $dataStatusDekrip);
                header('Content-Length: ' . filesize($outputFilePath));
                header("Content-Type: application/pdf");
                header('Content-Disposition: attachment; filename="' . $data_file['nama_file'] . '"'); // feel free to change the suggested filename
                readfile($outputFilePath);
                $data = file_get_contents($outputFilePath);

                echo "$data";
                $this->session->set_flashdata('berhasil', 'berhasil');
                redirect('Dekripsi');
            } else {
                $this->session->set_flashdata('gagal', 'gagal');
                redirect('Dekripsi');
            }
        } else {
            $this->session->set_flashdata('gagal', 'gagal');
            redirect('Dekripsi');
        }
    }

    public function hapus($id_file)
    {
        $data['user'] = $this->db->get_where('users', ['email' =>
        $this->session->userdata('email')])->row_array();

        $this->Dekripsi_model->hapusDataDekripsi($id_file);
        $this->session->set_flashdata('flash', 'Dihapus');
        redirect('dekripsi');
    }
}
