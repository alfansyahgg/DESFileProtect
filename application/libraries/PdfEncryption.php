<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PdfEncryption
{
    private $encryptionKey;

    public function __construct()
    {
        // Load CodeIgniter's encryption library
        $this->CI = &get_instance();
        $this->CI->load->library('encryption');

        // Set your encryption key
        // $this->encryptionKey = $key;
    }

    public function encryptPdf($key, $inputFilePath, $outputFilePath)
    {
        $inputFile = fopen($inputFilePath, 'rb');
        $outputFile = fopen($outputFilePath, 'wb');

        // Read the input file contents
        $inputContents = fread($inputFile, filesize($inputFilePath));

        $this->CI->encryption->initialize(
            array(
                'cipher' => 'des',
                'mode' => 'ecb',
                'key' => $key
            )
        );

        // Encrypt the contents using DES algorithm
        $encryptedContents = $this->CI->encryption->encrypt($inputContents);

        // Write the encrypted contents to the output file
        fwrite($outputFile, $encryptedContents);

        // Close the file handles
        fclose($inputFile);
        fclose($outputFile);
    }

    public function decryptPdf($key, $inputFilePath, $outputFilePath)
    {
        $inputFile = fopen($inputFilePath, 'rb');
        $outputFile = fopen($outputFilePath, 'wb');

        // Read the input file contents
        $inputContents = fread($inputFile, filesize($inputFilePath));



        $this->CI->encryption->initialize(
            array(
                'cipher' => 'des',
                'mode' => 'ecb',
                'key' => $key
            )
        );

        // Decrypt the contents using DES algorithm
        $decryptedContents = $this->CI->encryption->decrypt($inputContents);

        // Write the decrypted contents to the output file
        fwrite($outputFile, $decryptedContents);

        // Close the file handles
        fclose($inputFile);
        fclose($outputFile);
    }
}
