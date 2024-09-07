<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class TempImageController extends Controller
{
    public function create(Request $request)
    {
        $image = $request->image;

        if (!empty($image)) {
            $ext = $image->getClientOriginalExtension();
            $newName = time() . '.' . $ext;

            $tempImage = new TempImage();
            $tempImage->name = $newName;
            $tempImage->save();

            $image->move(public_path('/temp/'), $newName);

            //Generate thumbnail
            
            // create image manager with desired driver
            $manager = new ImageManager(new Driver());

            $sourcePath = public_path().'/temp/'. $newName;
            $destPath = public_path().'/temp/thumb/'. $newName;

            // read image from file system
            $image = $manager->read($sourcePath);

            $image->cover(300,275);
            $image->save($destPath);

            return response()->json([
                'success' => true,
                'image_id' => $tempImage->id,
                'Imagepath' => asset('/temp/thumb/'.$newName),
                'message' => 'Image uploaded successfully'
            ]);
//        }
        }
    }
}
