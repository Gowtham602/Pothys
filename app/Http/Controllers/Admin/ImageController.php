<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;
// use App\Models\ImageClick;
use App\Models\ImageClick;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Log;
use Stevebauman\Location\Facades\Location;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageController extends Controller
{
    public function index()
    {
        $images = Image::where('user_id', Auth::id())
                    ->latest()
                    ->get();

        return view('dashboard', compact('images'));
    }

    public function getImages()
    {
        $images = Image::where('user_id', Auth::id())
            ->latest()
            ->get();

        return response()->json([
            'images' => $images
        ]);
    }

        public function mobile()
        {
            $imageClicks = ImageClick::join('images', 'image_clicks.image_id', '=', 'images.id')
                ->where('images.user_id', Auth::id())
                ->select(
                    'image_clicks.*',
                    'images.image_name',
                    'images.file_path'
                )
                ->latest()
                ->get();

            return view('mobile', compact('imageClicks'));
        }

    public function store(Request $request)
    {
        $request->validate([
            'image_name' => 'required|max:255',
            'image' => 'required|image|mimes:jpeg,jpg|max:2048'
        ]);

        $cleanName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->image_name);
        $fileName = $cleanName . '.jpeg';

        if (Storage::disk('public')->exists('images/'.$fileName)) {
            return back()->with('error', 'File name already exists!');
        }

        $path = $request->file('image')
            ->storeAs('images', $fileName, 'public');

        do {
            $shortCode = Str::random(6);
        } while (Image::where('short_code', $shortCode)->exists());

        Image::create([
            'user_id' => Auth::id(),
            'image_name' => $cleanName,
            'file_path' => $path,
            'short_code' => $shortCode
        ]);

        return back()->with('success', 'Image uploaded successfully!');
    }
        // merge and create short url


public function redirect($code)
{
    $image = Image::where('short_code', $code)->firstOrFail();

    // increase total clicks
    $image->increment('click_count'); 

    $agent = new Agent();   

    // detect device
    if ($agent->isMobile()) {
        $device = 'Mobile';
    } elseif ($agent->isTablet()) {
        $device = 'Tablet';
    } else {
        $device = 'Desktop';
    }

    // browser
    $browser = $agent->browser();          

    // IP
    $ip = request()->ip();            

    // country
    $location = Location::get($ip);
    $country = $location ? $location->countryName : 'Unknown';

    // save analytics
    ImageClick::create([
        'image_id' => $image->id,   
        'ip_address' => $ip,                  
        'browser' => $browser,   
        'device_type' => $device,
        'country' => $country
    ]); 

    return redirect(asset('storage/'.$image->file_path));
}

public function process(Request $request)
{
    $request->validate([
        'images.*' => 'required|image',
        'mode' => 'required|in:vertical,horizontal',
    ]);

    $manager = new ImageManager(new Driver());

    $images = [];

    foreach ($request->file('images') as $file) {
        $images[] = $manager->read($file)->orient();
    }

    $width = 1080;

    foreach ($images as $img) {
        $img->scale(width: $width);
    }

    $totalHeight = array_sum(array_map(fn($img) => $img->height(), $images));

    $canvas = $manager->create($width, $totalHeight);

    $y = 0;
    foreach ($images as $img) {
        $canvas->place($img, 'top-left', 0, $y);
        $y += $img->height();
    }

    // SAVE PATH
    $path = public_path('storage/images');

    if (!file_exists($path)) {
        mkdir($path, 0777, true);
    }

    // TEMP NAME (ONLY TEMP, NOT FINAL NAME)
    $fileName = 'temp_' . time() . '.jpg';

    $canvas->toJpeg(95)->save($path . '/' . $fileName);

    return response()->json([
        'image' => asset('storage/images/' . $fileName),
        'file_path' => 'images/' . $fileName
    ]);
}
        //merge and shorl url create and save to db 
public function saveImage(Request $request)
{
    $request->validate([
        'image_name' => 'required',
        'file_path' => 'required'
    ]);

    $cleanName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->image_name);

    $path = public_path('storage/');

    $oldPath = $path . $request->file_path;
    $newFileName = $cleanName . '.jpg';
    $newPath = $path . 'images/' . $newFileName;

    //  duplicate check
    if (file_exists($newPath)) {
        return response()->json([
            'status' => 'error',
            'message' => 'Image name already exists!'
        ], 422);
    }

    //  rename file
    rename($oldPath, $newPath);

    // generate shortcode
    do {
        $shortCode = Str::random(6);
    } while (Image::where('short_code', $shortCode)->exists());

    // save DB
    Image::create([
        'user_id' => Auth::id(),
        'image_name' => $cleanName,
        'file_path' => 'images/' . $newFileName,
        'short_code' => $shortCode
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Image saved successfully!',
        'short_url' => url('/s/' . $shortCode)
    ]);
}
}