<?php

namespace App\Http\Controllers;

use App\Models\ConfigAddress;
use App\Models\ProfilePicture;
use App\Models\Proposal;
use App\Models\ReferrerRewardHistory;
use App\Models\Stake;
use App\Models\StakeRewardHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
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
            $path = storage_path('app/' . $profile->picture); // Gantikan dengan path yang sesuai

            if (!File::exists($path)) {
                $icon = new Identicon();
                $icon->setValue($request->input('address','0x0000000'.time()));
                $icon->setSize(200);
                $image = $icon->getImageDataUri();


                return Image::make($image)->response();
            }

            $file = File::get($path);
            $type = File::mimeType($path);

            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);

            return $response;
        }

            $icon = new Identicon();
            $icon->setValue($request->input('address','0x0000000'.time()));
            $icon->setSize(200);
            $image = $icon->getImageDataUri();


        return Image::make($image)->response();
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'address'   => 'required',
            'file'     => 'required|image'
        ]);
        $address = $request->input('address');
        $profile = ProfilePicture::where('address',$address)->first();
        if(!$profile){
            $profile = new ProfilePicture();
            $profile->address = $address;
        }
        $profile->picture = $request->file('file')->store();
        $profile->save();
        return $this->response($profile);
    }

    public function deleteProfile(Request $request)
    {
        $request->validate([
            'address'   => 'required',
        ]);
        $address = $request->input('address');
        $profile = ProfilePicture::where('address',$address)->first();
        if($profile){
            $profile->delete();
        }
        return $this->response($profile);
    }

    public function updateConfig(Request $request){
        if($request->has('addresses')){
            foreach ($request->input('addresses') as $address){
                ConfigAddress::updateOrCreate([
                    'code'  => $address['code']
                ],[
                    'name'  => $address['code'],
                    'address'   => $address['address']
                ]);
            }
            StakeRewardHistory::truncate();
            Stake::truncate();
            ReferrerRewardHistory::truncate();
            Proposal::truncate();
        }
        return $this->response([
            'message'   => 'success'
        ]);
    }

    public function syncWeb3(): \Illuminate\Http\JsonResponse
    {
        // run artisan command
        Artisan::call('app:sync-user-balance');
        Artisan::call('app:sync-history-referrer');
        Artisan::call('app:sync-history-reward-stake');
        Artisan::call('app:sync-proposal');
        Artisan::call('app:sync-config');
        return $this->response([
            'message'   => 'success'
        ]);
    }
}
