<?php

namespace Modules\Admin\Http\Controllers;

use App\Helpers\ImageUploadingHelper as ImgUploader;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TinyMceController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadImage(Request $request)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $fileName = ImgUploader::UploadImageTinyMce('tinymce_images', $image, time());
            echo json_encode(array('location' => url('/') . '/tinymce_images/' . $fileName));
        } else {
            echo 'No Image Available';
        }
    }
}
