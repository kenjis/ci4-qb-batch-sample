<?php

namespace App\Libraries;

use CodeIgniter\HTTP\Files\UploadedFile;
use Exception;
use SplFileObject;
use ZipArchive;

class ImportZipData
{
    private string $table     = 'zip_address';
    private string $uploadDir = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'zip_data';

    public function upload(UploadedFile $file): string
    {
        $fileName = $file->getRandomName();
        $filePath = $this->uploadDir . DIRECTORY_SEPARATOR . $fileName;

        $file->move($this->uploadDir, $fileName);

        if ($file->getClientExtension() === 'zip') {
            $csvFilePath = $this->unzip($filePath);
        } else {
            $csvFilePath = $filePath;
        }

        if (! $csvFilePath) {
            throw new Exception('No csv file path.');
        }

        log_message('debug', 'ImportZipData::csvFilePath: ' . $csvFilePath);

        return $csvFilePath;
    }

    /**
     * @return string|null File path
     */
    private function unzip(string $filePath): ?string
    {
        $zip = new ZipArchive();

        $fileDir = rtrim($filePath, '.zip');
        $zip->open($filePath);
        $zip->extractTo($fileDir);
        $zip->close();
        unset($zip);
        unlink($filePath);

        $filePaths = array_map(static function ($file) {
            // The original data is in Shift JIS encoding. Convert to UTF-8.
            $data = mb_convert_encoding(@file_get_contents($file), 'UTF-8', 'SJIS-Win');
            file_put_contents($file, $data);
            unset($data);

            return $file;
        }, glob($fileDir . '/*.csv'));

        return $filePaths[0] ?? null;
    }

    public function import(string $filePath): int
    {
        set_time_limit(6000);
        ini_set('memory_limit', '4096M');
        ini_set('default_socket_timeout', '-1');

        $db     = \Config\Database::connect();
        $fields = $db->getFieldData($this->table);

        $objFileCsv = new \CodeIgniter\Files\File($filePath);
        $splFileCsv = $objFileCsv->openFile('r');
        $splFileCsv->setFlags(
            SplFileObject::READ_CSV |
            SplFileObject::READ_AHEAD |
            SplFileObject::SKIP_EMPTY |
            SplFileObject::DROP_NEW_LINE
        );

        $splFileCsv->seek(1);
        $rows = [];

        while (! $splFileCsv->eof()) {
            $currentRow = $splFileCsv->current();

            $row = [];

            foreach ($fields as $key => $field) {
                $row[$field->name] = $currentRow[$key] ?? null;
            }
            $rows[] = $row;
            $splFileCsv->next();
        }
        unset($splFileCsv);

        $db->table($this->table)->truncate();
        $db->table($this->table)->insertBatch($rows);

        unset($rows);

        return $db->table($this->table)->countAll();
    }
}
