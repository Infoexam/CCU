<?php

namespace App\Http\Controllers\Api\V1;

use App\Infoexam\Image\Image;
use Illuminate\Http\Request;

class ImageController extends ApiController
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
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Request $request)
    {
        $image = Image::where('uploaded_at', $request->input('uploaded_at'))
            ->where('hash', $request->input('hash'))
            ->first();

        if (is_null($image)) {
            return $this->responseNotFound();
        }

        $image->delete();

        return $this->responseOk();
    }
}
