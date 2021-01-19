<?php

namespace App\Http\Controllers;
// ini_set('max_execution_time', 300); 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FilesController extends Controller
{
    //Creates the table html after comparing two files
    public function comparisonView($f1, $f2)
    {
        $rows = '';
        $file1 = fopen($f1, "r");
        $file2 = fopen($f2, "r");
        $lineno = 1;
        while (!feof($file1) || !feof($file2)) {
            $line1 = fgets($file1);
            $line2 = fgets($file2);
            if ($line1 === $line2) {
                $rows .= '<tr><td style="background-color:yellow;">Line ' . ($lineno++) . '</td><td style="background-color:#98FB98;">' . htmlentities($line1) . '</td><td style="background-color:#98FB98;">' . htmlentities($line2) . '</td></tr>';
            } else {

                $rows .= '<tr><td style="background-color:yellow;">Line ' . ($lineno++) . '</td><td style="background-color: #ff7a7a ;">' . htmlentities($line1) . '</td><td style="background-color: #ff7a7a ;">' . htmlentities($line2) . '</td></tr>';
            }
        }
        fclose($file1);
        fclose($file2);
        return $rows;
    }

    //Returns all file paths for each category of files
    public function returnFiles()
    {
        $fileNames_v1 = [];
        $path_v1 = Storage::disk('v1')->allFiles();
        $path_v2 = Storage::disk('v2')->allFiles();

        foreach ($path_v1 as $file) {
            array_push($fileNames_v1, pathinfo($file)['dirname'] . "/" . pathinfo($file)['basename']);
        }
        $fileNames_v2 = [];
        foreach ($path_v2 as $file) {
            array_push($fileNames_v2, pathinfo($file)['dirname'] . "/" . pathinfo($file)['basename']);
        }
        $v1_only = array_diff($fileNames_v1, $fileNames_v2);
        $v2_only = array_diff($fileNames_v2, $fileNames_v1);

        $both = array_intersect($fileNames_v1, $fileNames_v2);

        $common_and_same = [];
        $common_and_different = [];
        $com_dif_count = 0;

        foreach ($both as $file) {

            if (sha1_file((Storage::disk('v1')->path($file))) == sha1_file((Storage::disk('v2')->path($file)))) {
                array_push($common_and_same, pathinfo($file)['dirname'] . "/" . pathinfo($file)['basename']);
            } else {
                array_push($common_and_different, array(
                    'path' => pathinfo($file)['dirname'] . "/" . pathinfo($file)['basename'],

                    'html' => $this->comparisonView(Storage::disk('v1')->path($file), Storage::disk('v2')->path($file)),
                    'id' => $com_dif_count
                ));
                $com_dif_count++;
            }
        }

        return array('v1_only' => $v1_only, 'v2_only' => $v2_only, 'common_and_same' => $common_and_same, 'common_and_different' => $common_and_different);
    }

    //These functions serve the file names and contents to the view
    public function return_v1_only()
    {
        $v1_only = $this->returnFiles()['v1_only'];
        return view('v1_only', ['v1_only' => $v1_only]);
    }
    public function return_v2_only()
    {
        $v2_only = $this->returnFiles()['v2_only'];
        return view('v2_only', ['v2_only' => $v2_only]);
    }
    public function return_common_and_same()
    {
        $common_and_same = $this->returnFiles()['common_and_same'];
        return view('common_and_same', ['common_and_same' => $common_and_same]);
    }
    public function return_common_and_different()
    {
        $common_and_different = $this->returnFiles()['common_and_different'];
        return view('common_and_diff', ['common_and_different' => $common_and_different]);
    }
}
