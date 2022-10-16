<?php

namespace App\Http\Controllers;

use App\Models\gallery;
use Illuminate\Http\Request;
use Image;

class GalleryController extends Controller
{
   
    
    /**
     * Image resize & Save - Function
     */
    public function imgResize(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'image' => 'required|image|mimes:jpg,jpeg,png,svg,gif|max:2048',
        ]);

        $image = $request->file('image');

       
        $input['product_image'] = time() . '.' . $image->extension();

        // Get path of thumbnails folder from /public
        $thumbnailFilePath = public_path('thumbnails');


        $height = Image::make($image)->height();
        $width = Image::make($image)->width();
        $aspect = $width / $height;

        $img = Image::make($image->path());

        // Image resize to given aspect dimensions
        // Save this thumbnail image to /public/thumbnails folder
        if ($aspect >= 1.0){
            $img->resize(420, 278, function ($const) {
                $const->aspectRatio();
            })->save($thumbnailFilePath . '/' . $input['product_image']);
        }
        else{
            $img->resize(420, 584, function ($const) {
                $const->aspectRatio();
            })->save($thumbnailFilePath . '/' . $input['product_image']);
        }
       

        // Product images folder
        $ImageFilePath = public_path('images');

        // Store product original images
        $image->move($ImageFilePath, $input['product_image']);

        $product = new gallery();

        $product->name = $request->name;
        $product->image = "images/" . $input['product_image'];
        $product->thumbnail = "thumbnails/" . $input['product_image'];

        $product->save();

        return back()
            ->with('success', 'Product uploaded');
    }
}
