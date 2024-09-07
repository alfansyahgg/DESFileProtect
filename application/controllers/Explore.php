<?php
defined('BASEPATH') or exit('No direct script access allowed');




use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class Explore extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Explore_model');
        $this->load->model('Dekripsi_model');
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()

    {
        $id_user = $this->session->id_user;
        $data['title'] = 'Explore Files';
        $data['user'] = $this->db->get_where('users', ['email' =>
        $this->session->userdata('email')])->row_array();

        $data['explore'] = $this->Explore_model->getAllAvailableFile($id_user);

        // echo "<pre>";
        // print_r($data);
        // exit();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('explore/index', $data);
        $this->load->view('templates/footer');
    }

    public function myfile()

    {
        $id_user = $this->session->id_user;
        $data['title'] = 'Explore Files';
        $data['user'] = $this->db->get_where('users', ['email' =>
        $this->session->userdata('email')])->row_array();

        $data['explore'] = $this->Explore_model->myfile($id_user);

        // echo "<pre>";
        // print_r($data);
        // exit();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('explore/myfile', $data);
        $this->load->view('templates/footer');
    }

    public function dekrip()
    {

        $id_file = $this->input->post('id_file');
        $password = $this->input->post('password');

        $data['user'] = $this->db->get_where('users', ['email' =>
        $this->session->userdata('email')])->row_array();

        $data['title'] = 'Form Dekripsi';
        $this->load->model('Dekripsi_model', 'data');

        $data_file = $this->data->getDekripsiById($id_file);


        $batas = $data_file['batas'];
        $batas = strtotime($batas);
        $current_timestamp = strtotime(date('Y-m-d H:i:s'));

        // echo "<pre>";
        // var_dump($current_timestamp > $batas);
        // exit();

        if (time() <= $batas) {
            if (password_verify($password, $data_file['password'])) {

                $dataFileUser = [
                    'id_file' => $id_file,
                    'id_user' => $this->session->id_user,
                    'waktu_dekrip' => date('Y-m-d H:i:s')
                ];

                $this->Explore_model->addFileUser($dataFileUser);

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


                    // Proses file excel
                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header('Content-Disposition: attachment; filename="' . $data_file['nama_file_enkrip'] . '"'); // Set nama file excel nya
                    header('Cache-Control: max-age=0');


                    // Menyimpan file hasil dekripsi ke dalam file baru
                    $outputFileName = "assets/file_decrypt/" . $data_file['nama_file_enkrip'];
                    $writer = new Xlsx($spreadsheet);
                    $writer->save($outputFileName);
                    $data = file_get_contents('./assets/file_decrypt/' . $data_file['nama_file_enkrip']);

                    // echo "$data";
                    $this->session->set_flashdata('berhasil', 'berhasil');
                    redirect('Explore/myfile');
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



                    ob_end_clean();
                    header('Content-Length: ' . filesize($outputFilePath));
                    header("Content-Type: application/pdf");
                    header('Content-Disposition: attachment; filename="' . $data_file['nama_file'] . '"'); // feel free to change the suggested filename
                    readfile($outputFilePath);
                    $data = file_get_contents($outputFilePath);

                    echo "$data";



                    $this->session->set_flashdata('berhasil', 'berhasil');
                    redirect('Explore');
                } else {
                    $this->session->set_flashdata('gagal', 'gagal');
                    redirect('Explore');
                }
            } else {

                $outputFilePath = './assets/file_encrypt/' . $data_file['nama_file_enkrip'];
                ob_end_clean();
                header('Content-Length: ' . filesize($outputFilePath));
                header('Content-Disposition: attachment; filename="' . $data_file['nama_file_enkrip'] . '"'); // feel free to change the suggested filename
                readfile($outputFilePath);
                $data = file_get_contents($outputFilePath);
                echo "$data";
                $this->session->set_flashdata('password', 'gagal');
                redirect('Explore');
            }
        } else {
            ob_end_clean();
            $inputFilePath =
                './assets/file_encrypt/' . $data_file['nama_file_enkrip'];
            header('Content-Length: ' . filesize($inputFilePath));
            header('Content-Disposition: attachment; filename="' . $data_file['nama_file_enkrip'] . '"'); // feel free to change the suggested filename
            readfile($inputFilePath);
            $data = file_get_contents($inputFilePath);

            echo "$data";
            $this->session->set_flashdata('waktu', 'gagal');
            redirect('Explore');
        }
    }

    public function download($id_file)
    {
        $file = $this->Dekripsi_model->getWhereFile($id_file);

        $name_file = $file['nama_file'];
        $data = file_get_contents('./assets/file_decrypt/' . $name_file);

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $name_file . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize('./assets/file_decrypt/' . $name_file));

        echo "$data";
    }
}