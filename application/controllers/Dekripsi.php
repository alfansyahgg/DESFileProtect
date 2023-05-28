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

        $data['file'] = $this->Dekripsi_model->getAllDekripsi();

        // echo "<pre>";print_r($data);exit();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('Dekripsi/index', $data);
        $this->load->view('templates/footer');
    }

    public function download($id_file)
    {
        $file = $this->Dekripsi_model->getWhereFile($id_file);

        $name_file = $file['nama_file_enkrip'];
        $data = file_get_contents('./assets/file_chipertext/' . $name_file);

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $name_file . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize('./assets/file_chipertext/' . $name_file));

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

        $this->load->library('DES');
        $path = "assets/file_chipertext/" . $data_file['nama_file_enkrip'];
        $extension = explode(".", $data_file['nama_file_enkrip']);
        $extension = end($extension);
        if ($extension == "xls" || $extension == "xlsx") {
            // Membaca file Excel
            $spreadsheet = IOFactory::load($path);


            $key = 'mysecretkey';

            foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                // Iterate through all cells
                foreach ($worksheet->getRowIterator() as $row) {
                    foreach ($row->getCellIterator() as $cell) {
                        // Get the value of the cell
                        $value = $cell->getValue();

                        // echo "<pre>";var_dump($value);
                        // Encrypt the value using DES
                        $decryptedValue  = openssl_decrypt($value, 'DES-ECB', $key, 0);
                        if (false === $decryptedValue) {
                            echo openssl_error_string();
                            die;
                        }

                        // Set the encrypted value back to the cell
                        $cell->setValue($decryptedValue);
                    }
                }
            }




            // echo "<pre>";
            // var_dump($decryptedData);exit();



            $dataStatusDekrip = [
                'status_dekripsi' => 1,
            ];

            $this->Dekripsi_model->updateStatusDekrip($id_file, $dataStatusDekrip);


            // Proses file excel
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $data_file['nama_file_enkrip'] . '"'); // Set nama file excel nya
            header('Cache-Control: max-age=0');


            // Menyimpan file hasil dekripsi ke dalam file baru
            $outputFileName = "assets/file_decript/" . $data_file['nama_file_enkrip'];
            $writer = new Xlsx($spreadsheet);
            $writer->save($outputFileName);
            $data = file_get_contents('./assets/file_decript/' . $data_file['nama_file_enkrip']);

            echo "$data";
        } else if ($extension == "pdf") {
            $plaintext = "";
            $ciphertext = "";
            $this->load->library('DES');
            $path = "assets/file_encript/" . $data_file['nama_file_enkrip'];
            $bin_ciphertext = (string) file_get_contents($path);
            $arr_ciphertext = str_split($bin_ciphertext, 64);


            $desModule = new DES();
            foreach ($arr_ciphertext as $i) {
                $decrypt = $desModule->decrypt($i, $data_file['createdAt']);
                $plaintext .= $desModule->read_bin($decrypt);
                $ciphertext .= $desModule->read_bin($i);
            }
            $this->load->library('Pdfgenerator');
            $dt['plaintext'] = $plaintext;

            $new_plaintext = mb_convert_encoding($plaintext, 'UTF-8', 'ASCII');

            $html = ob_get_contents();
            ob_end_clean();

            $dataStatusDekrip = [
                'status_dekripsi' => 1,
            ];
            $this->Dekripsi_model->updateStatusDekrip($id_file, $dataStatusDekrip);

            $pdfgenerator = new Pdfgenerator();
            $pdfgenerator->generate($new_plaintext, $data_file['nama_file'], "A4", "landscape", true);
            $pdfgenerator->loadHtml($html);
            $pdfgenerator->setPaper('A4', 'landscape');
            $pdfgenerator->render();
            $pdfgenerator->stream($data_file['nama_file'], array('Attachment' => 0));
            exit();
        } else {
            redirect('Enkripsi');
        }

        // $url = base_url('./assets/file_decript/' . $data_file['nama_file']);
        // $html = '<iframe src="' . $url . '" style="border:none; width: 100%; height: 100%"></iframe>';
        // echo $html;

        // $pdfgenerator = new Pdfgenerator();
        // $pdfgenerator->generate($new_plaintext, $data_file['nama_file'], "A4", "landscape", true);
        // $pdfgenerator->loadHtml($html);
        // $pdfgenerator->setPaper('A4', 'landscape');
        // $pdfgenerator->render();
        // $pdfgenerator->stream($data_file['nama_file'], array('Attachment' => 0));
        // exit();
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