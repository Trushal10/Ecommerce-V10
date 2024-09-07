<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Validator;

class BrandController extends Controller
{
    public function index(Request $request) {
        $brands = Brand::latest('id');

        if (!empty($request->get('keyword'))) {
            $brands = $brands->where('.name', 'like', '%' . $request->get('keyword') . '%');
            
        }

        $brands = $brands->paginate(10);
        // return $brands;
        return view("admin.brand.list", compact("brands"));
    }
    public function create() {
        return view("admin.brand.create");
    }

    public function store(Request $request){
        $validator =Validator::make($request->all(),[
            'name'=> 'required',
            'slug'=>'required|unique:sub_categories',
            'status'=>'required',
        ]);

        if($validator->passes()){
            $brand = new Brand();

            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            $request->session()->flash('success','Brand added successfully');

            return response([
                'status' => true,
                'message' =>'Brand added successfully'
            ]);

        } else{
            return response([
                'status'=>false, 
                'errors'=>$validator->errors()
            ]);
        }
    }

    public function edit($id,Request $request) {
        $brand = Brand::find($id);

        if(empty($brand)) {
            $request->session()->flash('success','Record not found');
            return redirect()->route('brands.index');
        }
        // return$brand;
        return view('admin.brand.edit',compact('brand'));
    }

    public function update(Request $request, $id) {
        $brand = Brand::find($id);
        if(empty($brand)) {
            $request->session()->flash('status','Brand not found');

            return response()->json([
                'status' => false,
                'notFound'=>true,
                'message' => "Brand not found!",
            ]);
        }

        $validator = validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,'.$brand->id.',id',
            'status' => 'required'
        ]);


        if ($validator->passes()) {

            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            $request->session()->flash('status', 'Brand updated successfully!');

            return response()->json([
                'status' => true,
                'message' => "Brand updated successfully!",
            ]);


        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function destroy(Request $request, $id) {
        $brand = Brand::find($id);
        if(empty($brand)) {
            $request->session()->flash('status','Brand not found');

            return response()->json([
                'status' => false,
                'notFound'=>true,
                'message' => "Sub Category not found!",
            ]);
        }

        $brand->delete();

        $request->session()->flash('status', 'Brand deleted successfully!');

        return response()->json([
            'status' => true,
            'message' => "Brand deleted successfully!",
        ]);
    }
}
