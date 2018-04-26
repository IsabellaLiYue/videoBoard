<?php
namespace backend\util;

class UploadUtil
{

    public static function upload($path, $MAX_FILE_SIZE, $upForm)
	{
        $save_file_path= $path;

        if (empty($_FILES[$upForm])) {
            return false;
        } else {
            $uploadfile = $_FILES[$upForm];
            $file_name = $uploadfile['name'];
            $file_type = $uploadfile['type'];
            $file_size = $uploadfile['size'];
            $file_tmp_name = $uploadfile['tmp_name'];
            $file_error = $uploadfile['error'];
            $file_name_arr = explode(".", $file_name);
            $file_name_nums_ceil = count($file_name_arr) - 1;
            $file_name = $save_file_path . date("Ymd") . time() . rand(1000,9999) . "." . $file_name_arr[$file_name_nums_ceil];

            if(is_uploaded_file($file_tmp_name) && $file_error == 0) {
                move_uploaded_file($file_tmp_name,$file_name);
                $message = "Upload file successfully!";
                $ok_save = $file_name;
            } else {
                $message="Fail to upload file";

                return $message;
            }
            if(!empty($ok_save)) {
                return $ok_save;
            } else {
                return false;
            }
        }
    }
}