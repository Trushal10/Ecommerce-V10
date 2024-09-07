<?php

namespace App\Http\Controllers\admin;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\TempImage;
use App\Models\SubCategory;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductController extends Controller
{
    public function index(Request $request) {
        $products = Product::latest('id');


        if (!empty($request->get('keyword'))) {
            $products = $products->where('.name', 'like', '%' . $request->get('keyword') . '%');
            
        }

        $products = $products->paginate(10);
        $data['products'] = $products;
        return view("admin.products.list", $data);
    }
    public function create() {
        $data = [];
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name','ASC')->get();
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        // return $data;
        return view('admin.products.create', $data);
    }

    public function store(Request $request) {
        // dd($request->image_array);
     
        $rules =[
            'title'=> 'required',
            'slug'=>'required|unique:products',
            'price'=>'required|numeric',
            'sku'=>'required|unique:products',
            'track_qty'=>'required|in:Yes,No',
            'category'=>'required',
            'is_featured'=>'required |in:Yes,No',
        ];

        if(!empty($request->track_qty) && !empty($request->track_qty == 'YES')) {
            $rules['qty'] ='required|numeric';
        }
        $validator =Validator::make($request->all(),$rules);

        if($validator->passes()){
            
            $product = new Product();
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->brands_id = $request->brands;
            $product->is_featured = $request->is_featured;
            $product->save();

            //save product images
            if(!empty($request->image_array)) {
                foreach($request->image_array as $image) {

                    $tempImage =TempImage::find($request->image_id);
                    $extArray = explode('.',$tempImage->name);
                    $ext = last($extArray);

                    
                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->image = 'NULL';
                    $productImage->save();

                    $imageName = $product->id.'-'.$productImage->id.'-'.time().'.'.$ext;
                    $productImage->image = $imageName;
                    $productImage->save();

                    //Generate Product thumbnail
                     // create image manager with desired driver
                    $manager = new ImageManager(new Driver());

                    //large images
                    $sourcePath = public_path().'/temp/'.$tempImage->name;
                    $destPath = public_path().'/uploads/product/large'. $tempImage->name;
                    $image = $manager->read($sourcePath);

                    $image->cover(1400,956);
                    $image->save($destPath);
        


                    //Small images
                }
            }

            $request->session()->flash('success','Prodect added successfully');

            return response([
                'status' => true,
                'message' =>'Prodect added successfully'
            ]);

        } else{
            return response([
                'status'=>false, 
                'errors'=>$validator->errors()
            ]);
        }
    }

    public function edit($id,Request $request) {
        $product = Product::find($id);
        $subCategory = SubCategory::where('category_id',$product->category_id)->get();
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name','ASC')->get();


        if(empty($product)) {
            $request->session()->flash('success','Record not found');
            return redirect()->route('products.index');
        }
        // return$brand;
        $data = [];
        $data['product'] = $product;
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        $data['subCategory'] = $subCategory;
        // return $data;
        return view('admin.products.edit',$data);
    }
}
