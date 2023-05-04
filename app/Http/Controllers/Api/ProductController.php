<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = request('search','');
        $perPage = request('per_page', 10);
        $sortField = request('sort_field', 'created_at');
        $sortDirection = request('sort_direction', 'desc');


        $query = Product::query()
        ->where('title', 'like', "%{$search}%")
        ->orderBy ($sortField, $sortDirection)
        ->paginate ($perPage);
        return ProductListResource::collection($query);
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $data = $request->validated();
       $data['created_by'] = $request->user()->id;
       $data['updated_by'] = $request->user()->id;

       /** @var \Illuminate\Http\UploadedFile $image */
       $image = $data['image'] ?? null;
       //Check if img was given and save as local file system
       if($image) {
            $relativePath = $this->saveImage($image);
            $data['image'] = URL::to(Storage::url($relativePath));
            $data['image_mime'] = $image->getClientMimeType();
            $data['image_size'] = $image->getSize();
       }
       $product = Product::create($data);

       return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     * 
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param \App\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->validated();
        $data['updated_by'] = $request->user()->id;

        /** @var \Illuminate\Http\UploadedFile $image */
        $image = $data['image'] ?? null;

        //Check if img was given and save on local file system
        if($image) {
            $relativePath = $this->saveImage($image);
            $data['image'] = URL::to(Storage::url($relativePath));
            $data['image_mime'] = $image->getClientMimeType();
            $data['image_size'] = $image->getSize();

            if($product->image) {
                Storage::deleteDirectory('/public/' . dirname($product->image));
            }
        }

        $product->update($data);

        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->noContent();
    }
    private function saveImage(UploadedFile $image)
    {
        $path = 'images/' . Str::random();
        if(!Storage::exists($path)){
            Storage::makeDirectory($path, 0755, true);
        }
        if(!Storage::putFileAS('public/' . $path, $image, $image->getClientOriginalName())) {
            throw new \Exception("Unable to save file \"{$image->getClientOriginalName()}\"");
        }

        return $path . '/' . $image->getClientOriginalName();
    }

}

