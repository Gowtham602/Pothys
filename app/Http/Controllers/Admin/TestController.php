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

class ImageController extends Controller
{
    public function index()
    {
        $images = Image::where('user_id', Auth::id())
                    ->latest()
                    ->get();

        return view('dashboard', compact('images'));
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

    // public function redirect($code)
    // {
    //     // dd($code);
    //     $image = Image::where('short_code', $code)->firstOrFail();
    //     return redirect(asset('storage/'.$image->file_path));
    // }

    // use Jenssegers\Agent\Agent;

// public function redirect($code)
// {
//     // dd($code);
//     $image = Image::where('short_code', $code)->firstOrFail();
//     // Increase click count
//     $image->increment('click_count');
//     // dd($image);

//     // Detect device
//     $agent = new Agent();

//     if ($agent->isMobile()) {
//         $device = 'Mobile';
//     } elseif ($agent->isTablet()) {
//         $device = 'Tablet';
//     } else {
//         $device = 'Desktop';
//     }

//     // Optional log
//     Log::info('Image clicked', [
//         'short_code' => $code,
//         'device' => $device,
//         'ip' => request()->ip()
//     ]);

//     return redirect(asset('storage/'.$image->file_path));
// }
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
        'image_name' => 'required'
    ]);

    $spacing = intval($request->input('spacing', 0));
    $bgcolor = $request->input('bgcolor', '#ffffff');

    $manager = new ImageManager(new Driver());

    $images = [];

    foreach ($request->file('images') as $file) {
        $images[] = $manager->read($file)->orient();
    }

    if (empty($images)) {
        return response()->json(['error' => 'No images'], 400);
    }

    // =========================
    // VERTICAL
    // =========================
    if ($request->mode === 'vertical') {

        $targetWidth = $request->filled('width') ? intval($request->width) : 1080;

        foreach ($images as $img) {
            $img->scale(width: $targetWidth);
            $img->sharpen(5);
        }

        $totalHeight = array_sum(array_map(fn($img) => $img->height(), $images))
            + ($spacing * (count($images) - 1));

        $canvas = $manager->create($targetWidth, $totalHeight)->fill($bgcolor);

        $y = 0;
        foreach ($images as $img) {
            $canvas->place($img, 'top-left', 0, $y);
            $y += $img->height() + $spacing;
        }
    }

    // =========================
    // HORIZONTAL
    // =========================
    else {

        $targetHeight = $request->filled('height') ? intval($request->height) : 1080;

        foreach ($images as $img) {
            $img->scale(height: $targetHeight);
            $img->sharpen(5);
        }

        $totalWidth = array_sum(array_map(fn($img) => $img->width(), $images))
            + ($spacing * (count($images) - 1));

        $canvas = $manager->create($totalWidth, $targetHeight)->fill($bgcolor);

        $x = 0;
        foreach ($images as $img) {
            $canvas->place($img, 'top-left', $x, 0);
            $x += $img->width() + $spacing;
        }
    }

    // =========================
    // SAVE + SHORT URL (🔥 FIX)
    // =========================

    $outputPath = public_path('storage/images');

    if (!file_exists($outputPath)) {
        mkdir($outputPath, 0777, true);
    }

    // clean name
    $cleanName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->image_name);

    $fileName = $cleanName . '_' . time() . '.jpg';

    $path = $outputPath . '/' . $fileName;

    // save image
    $canvas->toJpeg(95)->save($path);

    // generate short code
    do {
        $shortCode = Str::random(6);
    } while (Image::where('short_code', $shortCode)->exists());

    // save in DB
    Image::create([
        'user_id' => Auth::id(),
        'image_name' => $cleanName,
        'file_path' => 'images/' . $fileName,
        'short_code' => $shortCode
    ]);

    return response()->json([
        'image' => asset('storage/images/' . $fileName),
        'short_url' => url('/s/' . $shortCode)
    ]);
}

}