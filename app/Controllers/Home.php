<?php

namespace App\Controllers;

use App\Libraries\ImportZipData;

class Home extends BaseController
{
    public function index()
    {
        helper('form');

        return view('home.html');
    }

    public function insert()
    {
        set_time_limit(6000);
        ini_set('memory_limit', '4096M');
        ini_set('default_socket_timeout', '-1');

        $start = microtime(true);

        $userfile = $this->request->getFile('userfile');

        if ($userfile) {
            $importer = new ImportZipData();

            $csvFilePath     = $importer->upload($userfile);
            $recordsImported = $importer->import($csvFilePath);

            if ($recordsImported) {
                $end        = microtime(true);
                $peakMemory = memory_get_peak_usage();

                return 'insertBatch(): ' . $recordsImported . ' records have been imported. '
                    . $this->humanBytes($peakMemory) . ', ' . round($end - $start) . ' seconds.';
            }
        }

        return redirect()->back();
    }

    public function update()
    {
        set_time_limit(6000);
        ini_set('memory_limit', '4096M');
        ini_set('default_socket_timeout', '-1');

        $start = microtime(true);

        $userfile = $this->request->getFile('userfile');

        if ($userfile) {
            $importer = new ImportZipData();

            $csvFilePath     = $importer->upload($userfile);
            $recordsImported = $importer->update($csvFilePath);

            if ($recordsImported) {
                $end        = microtime(true);
                $peakMemory = memory_get_peak_usage();

                return 'updateBatch(): ' . $recordsImported . ' records have been imported. '
                    . $this->humanBytes($peakMemory) . ', ' . round($end - $start) . ' seconds.';
            }
        }

        return redirect()->back();
    }

    private function humanBytes(int $size = 0, array $units = ['B', 'KB', 'MB', 'GB', 'TB']): string
    {
        for ($i = 0; $size > 1024; $i++) {
            $size /= 1024;
        }

        return round($size) . ' ' . $units[$i];
    }
}
