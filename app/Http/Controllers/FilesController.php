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
        $path_v1 = Storage::disk('public')->allFiles('task-websites/v1');
        $path_v2 = Storage::disk('public')->allFiles('task-websites/v2');

        foreach ($path_v1 as $file) {
            $path=substr(pathinfo($file)['dirname'],  17) . "/" . pathinfo($file)['basename'];
            if($path[0]=='/')
                $path = substr($path, 1);
            array_push($fileNames_v1, $path);
        }
        $fileNames_v2 = [];
        foreach ($path_v2 as $file) {
            $path=substr(pathinfo($file)['dirname'],  17) . "/" . pathinfo($file)['basename'];
            if($path[0]=='/')
                $path = substr($path, 1);
            array_push($fileNames_v2, $path);
        }
        $v1_only = array_diff($fileNames_v1, $fileNames_v2);
        $v2_only = array_diff($fileNames_v2, $fileNames_v1);

        $both = array_intersect($fileNames_v1, $fileNames_v2);

        $common_and_same = [];
        $common_and_different = [];
        $com_dif_count = 0;

        foreach ($both as $file) {
            $path=pathinfo($file)['dirname'] . "/" . pathinfo($file)['basename'];
            if($path[0]=='.')
                $path = substr($path, 2);
            if (sha1_file((Storage::disk('public')->path('task-websites/v1/'.$file))) == sha1_file((Storage::disk('public')->path('task-websites/v2/'.$file)))) {
                array_push($common_and_same,$path );
            } else {
                array_push($common_and_different, array(
                    'path' => $path,

                    'html' => $this->comparisonView(Storage::disk('public')->path('task-websites/v1/'.$file), (Storage::disk('public')->path('task-websites/v2/'.$file))),
                    'id' => $com_dif_count
                ));
                $com_dif_count++;
            }
        }
        dd($common_and_different);
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
