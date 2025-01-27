<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;



class categoryController extends Controller
{
    public function index(Request $request) {
        $categories = Category::latest();

        if (!empty($request->get('keyword'))) {
            $categories = $categories->where('name', 'like', '%' . $request->get('keyword') . '%');
        }

        $categories = $categories->paginate(10);

        return view('admin.category.list', compact('categories'));

    }

    public function create() {
        return view('admin.category.create');
    }

    public function store(Request $request) {
        $validator = validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories',
        ]);


        if ($validator->passes()) {

            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->save();

            //save Image Here
            if(!empty($request->image_id)) {
                $tempImage =TempImage::find($request->image_id);
                $extArray = explode('.',$tempImage->name);
                $ext = last($extArray);

                $newImageName = $category->id . '.' . $ext;

                $sPath = public_path().'/temp/'.$tempImage->name;
                $dPath = public_path().'/uploads/category/'.$newImageName;

                File::copy($sPath, $dPath);

                //Generate Image Thumbnail
//                $dPath = public_path().'/uploads/category/thumb'.$newImageName;
//
//                $img = Image::make($sPath);
//                $img = resize(450,600);
//                $img->save($dPath);

                $category->image = $newImageName;
                $category->save();
            }

            $request->session()->flash('status', 'Category added successfully!');

            return response()->json([
                'status' => true,
                'message' => "Category added successfully!",
            ]);


        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function show($id) {

    }

    public function edit($categoryId, Request $request) {
        $category = category::find($categoryId);
        if(empty($category)) {
            return redirect()->route('categories.index');
        }
        return view('admin.category.edit',compact('category'));
    }

    public function update($categoryId, Request $request) {

        $category = category::find($categoryId);
        if(empty($category)) {
            $request->session()->flash('status','category not found');

            return response()->json([
                'status' => false,
                'notFound'=>true,
                'message' => "Category not found!",
            ]);
        }

        $validator = validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,'.$category->id.',id',
        ]);


        if ($validator->passes()) {

            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->save();

            $oldImage = $category->image;

            //save Image Here
            if(!empty($request->image_id)) {
                $tempImage =TempImage::find($request->image_id);
                $extArray = explode('.',$tempImage->name);
                $ext = last($extArray);

                $newImageName = $category->id . '-'.time().'.' . $ext;

                $sPath = public_path().'/temp/'.$tempImage->name;
                $dPath = public_path().'/uploads/category/'.$newImageName;

                File::copy($sPath, $dPath);

                //Generate Image Thumbnail
//                $dPath = public_path().'/uploads/category/thumb'.$newImageName;
//
//                $img = Image::make($sPath);
//                $img = resize(450,600);
//                $img->save($dPath);

                $category->image = $newImageName;
                $category->save();

                //Delete Old image
                File::delete(public_path().'/uploads/category/thumb/'.$oldImage);
                File::delete(public_path().'/uploads/category/'.$oldImage);
            }

            $request->session()->flash('status', 'Category update successfully!');

            return response()->json([
                'status' => true,
                'message' => "Category update successfully!",
            ]);


        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function destroy($categoryId, Request $request) {
        $category = category::find($categoryId);
        if(empty($category)) {
            $request->session()->flash("errors",'Cannot find category');
            return redirect()->json([
                'status' => true,
                'message'=> 'category not found',
            ]);
        }
        
         //Delete Old image
         File::delete(public_path().'/uploads/category/thumb/'.$category->image);
         File::delete(public_path().'/uploads/category/'.$category->image);
         $category->delete();

         $request->session()->flash('succes','Categories delete Successfully');
         return response()->json([
            'status' => true,
            'message' => "Category deleted successfully!",
        ]);
        
    }
}

