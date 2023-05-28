<?php
defined('BASEPATH') or exit('No direct script access allowed');


use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class Enkripsi extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Enkripsi_model');
        $this->load->helper('date');
        if (!isset($this->session->email)) {
            redirect('auth');
        }
    }

    public function index()
    {
        $data['title'] = 'Enkripsi';
        $data['user'] = $this->db->get_where('users', ['email' =>
        $this->session->userdata('email')])->row_array();

        $data['enkripsi'] = $this->Enkripsi_model->getAllEnkripsi();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('enkripsi/index', $data);
        $this->load->view('templates/footer');
    }

    public function import()
    {
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == false) {
            redirect('enkripsi');
        } else {
            $user = $this->db->get_where('users', ['email' =>
            $this->session->userdata('email')])->row_array();
            $keys = $this->input->post('password');
            $bin_ciphertext = "";
            $ciphertext = "";

            $fileName = $_FILES['file']['name'];
            $extension = explode(".", $fileName);
            $extension = end($extension);

            // echo "<pre>";print_r($_FILES);exit();
            if (isset($_FILES['file'])) {
                if ($extension == "xls" || $extension == "xlsx") {
                    $nama_file = str_replace("_", "-", $_FILES['file']['name']);

                    // Inisialisasi
                    $random_number = rand(1000, 100000);
                    $filename_pdf = $random_number . '-' . $_FILES['file']['name'];
                    $filename_enc = $random_number . '-' . $nama_file;
                    $format = "%Y-%m-%d";

                    $config['upload_path'] = './assets/file_encript/';
                    $config['file_name'] = $filename_pdf;
                    $config['allowed_types'] = 'xls|xlsx';
                    $config['max_size'] = 2048;
                    $config['max_width'] = 1024;
                    $config['max_height'] = 768;

                    $this->load->library('upload', $config);
                    $this->load->helper('file');
                    $this->load->helper('date');

                    // file_put_contents('./assets/file_chipertext/' . $filename_enc, $pdf_output);
                    // file_put_contents('./assets/file_encript/' . $filename_enc, $pdf_encript);

                    // Pindah file pdf ke folder file decript
                    if ($this->upload->do_upload('file')) {
                        // if (write_file(FCPATH . './assets/file_chipertext/' . $filename_enc, $ciphertext)) {
                        // echo "Berhasil write chipertext file";

                        // if (write_file(FCPATH . './assets/file_encript/' . $filename_enc, $bin_ciphertext)) {

                        $inputFileName = './assets/file_encript/' . $filename_pdf;
                        $spreadsheet = IOFactory::load($inputFileName);

                        $key = $keys; // kunci enkripsi

                        // Mengenkripsi file Excel menggunakan metode DES
                        // Iterate through all worksheets
                        foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                            // Iterate through all cells
                            foreach ($worksheet->getRowIterator() as $row) {
                                foreach ($row->getCellIterator() as $cell) {
                                    // Get the value of the cell
                                    $value = $cell->getValue();


                                    // echo "<pre>";print_r($value);exit();
                                    // Encrypt the value using DES
                                    $encryptedValue = openssl_encrypt($value, 'DES-ECB', $key, 0);

                                    if (false === $encryptedValue) {
                                        echo openssl_error_string();
                                        die;
                                    }
                                    // Set the encrypted value back to the cell
                                    $cell->setValue($encryptedValue);
                                }
                            }
                        }

                        // Menyimpan file hasil enkripsi ke dalam file baru
                        $outputFileName = './assets/file_chipertext/' . $filename_enc;
                        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                        $writer->save($outputFileName);

                        $data = array(
                            'id_user' => $user['id_user'],
                            'nama_file' => $filename_pdf,
                            'nama_file_enkrip' => $filename_enc,
                            'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                            'createdAt' => $keys,
                        );

                        $this->Enkripsi_model->tambahDataEnkripsi($data);

                        redirect('Enkripsi');
                    } else {
                        $error = array('error' => $this->upload->display_errors());

                        echo $error['error'];
                    }

                    // Write file txt ke folder encript

                    // echo $plaintext;
                    // echo $bin_ciphertext;
                    // echo "<br>";
                    // echo $ciphertext;
                } else if ($_FILES['file']['type'] == "application/pdf") {
                    $this->load->library('DES');
                    $this->load->library('pdfgenerator');
                    $this->load->library('PDF2Text');

                    $pdf = new PdftoText($_FILES['file']['tmp_name']);
                    $data = $pdf->Text;

                    // encrypt
                    $plaintext = trim($data);
                    $desModule = new DES();

                    $arr_plaintext = str_split($plaintext, 8);

                    foreach ($arr_plaintext as $i) {
                        $encrypt = $desModule->encrypt($i, $keys);
                        $bin_ciphertext .= $encrypt;
                        $ciphertext .= $desModule->read_bin($encrypt);
                    }
                    // echo "<pre>";
                    // print_r($arr_plaintext);
                    // exit();

                    // write file txt
                    $nama_file = str_replace(".pdf", ".txt", $_FILES['file']['name']);
                    // $nama_file = $_FILES['file']['name'] . ".txt";
                    // force_download($nama_file, $bin_ciphertext);

                    // Inisialisasi
                    $random_number = rand(1000, 100000);
                    $filename_pdf = $random_number . '-' . $_FILES['file']['name'];
                    $filename_enc = $random_number . '-' . $nama_file;
                    $format = "%Y-%m-%d";

                    $config['upload_path'] = './assets/file_decript/';
                    $config['file_name'] = $filename_pdf;
                    $config['allowed_types'] = 'pdf';
                    $config['max_size'] = 2048;
                    $config['max_width'] = 1024;
                    $config['max_height'] = 768;

                    $this->load->library('upload', $config);
                    $this->load->helper('file');
                    $this->load->helper('date');

                    // Pindah file pdf ke folder file decript
                    if ($this->upload->do_upload('file')) {
                        if (write_file(FCPATH . './assets/file_chipertext/' . $filename_enc, $ciphertext)) {
                            // echo "Berhasil write chipertext file";

                            if (write_file(FCPATH . './assets/file_encript/' . $filename_enc, $bin_ciphertext)) {
                                $data = array(
                                    'id_user' => $user['id_user'],
                                    'nama_file' => $filename_pdf,
                                    'nama_file_enkrip' => $filename_enc,
                                    'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                                    'createdAt' => $keys,
                                );

                                $this->Enkripsi_model->tambahDataEnkripsi($data);
                                redirect('Dekripsi');
                            }
                        }
                    } else {
                        $error = array('error' => $this->upload->display_errors());

                        echo $error['error'];
                    }
                }
            } else {
                redirect('Enkripsi');
            }
        }
    }

    public function process()
    {
        $data['title'] = 'Enkripsi';
        $data['user'] = $this->db->get_where('users', ['email' =>
        $this->session->userdata('email')])->row_array();

        $data['enkripsi'] = $this->Enkripsi_model->getAllEnkripsi();

        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == false) {
            redirect('enkripsi');
        } else {
            $user = $this->db->get_where('users', ['email' =>
            $this->session->userdata('email')])->row_array();
            $key = $this->input->post('password');
            $bin_ciphertext = "";
            $ciphertext = "";

            if (isset($_FILES['file'])) {
                if ($_FILES['file']['type'] == "application/pdf") {
                    $this->load->library('DES');
                    $this->load->library('pdfgenerator');
                    $this->load->library('PDF2Text');

                    // $this = new this();
                    $pdf = new PdftoText($_FILES['file']['tmp_name']);
                    $file_data = $pdf->Text;

                    // encrypt
                    $plaintext = trim($file_data);

                    $arr_plaintext = str_split($plaintext, 8);
                    $proses = 0;

                    $tampil_proses = [];
                    $desModule = new DES();

                    foreach ($arr_plaintext as $i) {
                        $tampil_proses[$proses] = "";
                        $tampil_proses[$proses] .= "Proses ke " . ($proses + 1) . " <br>";
                        $encrypt = $desModule->encrypt($i, $key, true);
                        $bin_ciphertext .= $encrypt;
                        $ciphertext .= $desModule->read_bin($encrypt);
                        $tampil_proses[$proses] .= $desModule->proses_encrypt;
                        $proses++;
                    }

                    $data['proses'] = $tampil_proses;

                    $this->load->view('templates/header', $data);
                    $this->load->view('templates/sidebar', $data);
                    $this->load->view('templates/topbar', $data);
                    $this->load->view('enkripsi/proses', $data);
                    $this->load->view('templates/footer');
                } else {
                    $data['pesan'] = '<div class="alert alert-danger mt-4" role="alert">File tidak disupport! Silahkan upload file berekstensi PDF!</div>';

                    $this->load->view('templates/header', $data);
                    $this->load->view('templates/sidebar', $data);
                    $this->load->view('templates/topbar', $data);
                    $this->load->view('enkripsi/index', $data);
                    $this->load->view('templates/footer');
                }
            } else {
                redirect('Enkripsi');
            }
        }
    }
}
