<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Infoexam\Image\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * ImageController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * 刪除指定圖片
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function destroy(Request $request)
    {
        Image::where('uploaded_at', '=', $request->input('uploaded_at'))
            ->where('hash', '=', $request->input('hash'))
            ->firstOrFail()
            ->delete();

        return $this->ok();
    }
}
