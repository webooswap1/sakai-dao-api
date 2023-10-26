<?php

namespace App\Http\Controllers;

use App\Models\ProfilePicture;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Jdenticon\Identicon;

class UtilsController extends Controller
{
    public function ping(): \Illuminate\Http\JsonResponse
    {
        return $this->response([
            'message' => 'pong'
        ]);
    }

    public function time() : \Illuminate\Http\JsonResponse
    {
        return $this->response([
            'time' => now()->timestamp,
            'timezone' => 'UTC'
        ]);
    }

    public function profile(Request $request)
    {
        $address = $request->input('address');
        $profile = ProfilePicture::where('address',$address)->first();
        $image = null;
        if($profile){
            $image = $profile->picture;
        } else {
            $icon = new Identicon();
            $icon->setValue($request->input('address','0x0000000'.time()));
            $icon->setSize(200);
            $image = $icon->getImageDataUri();
        }

        return Image::make($image)->response();
    }
}
