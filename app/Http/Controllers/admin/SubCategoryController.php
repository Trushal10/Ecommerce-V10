<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\FlareClient\Http\Exceptions\NotFound;

class SubCategoryController extends Controller
{
    public function index(Request $request) {
        $subCategories = SubCategory::select('sub_categories.*','categories.name as categoryName' )
                                     ->latest('sub_categories.id')
                                     ->leftJoin('categories','categories.id','sub_categories.category_id');

        if (!empty($request->get('keyword'))) {
            $subCategories = $subCategories->where('sub_categories.name', 'like', '%' . $request->get('keyword') . '%');
            $subCategories = $subCategories->orWhere('categories.name', 'like', '%' . $request->get('keyword') . '%');
        }

        $subCategories = $subCategories->paginate(10);

        return view('admin.sub_category.list', compact('subCategories'));
    }
    public function create() {
        $categories = Category::orderBy("name","asc")->get();
        return view('admin.sub_category.create', compact('categories'));
    }

    public function store(Request $request){
        $validator =Validator::make($request->all(),[
            'category'=>'required',
            'name'=> 'required',
            'slug'=>'required|unique:sub_categories',
            'status'=>'required',
        ]);

        if($validator->passes()){
            $subCategory = new SubCategory();

            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->category_id = $request->category;
            $subCategory->save();

            $request->session()->flash('success','Sub Category added successfully');

            return response([
                'status' => true,
                'message' =>'Sub Category added successfully'
            ]);

        } else{
            return response([
                'status'=>false, 
                'errors'=>$validator->errors()
            ]);
        }
    }

    public function edit($id,Request $request) {
        $subCategory = SubCategory::find($id);

        if(empty($subCategory)) {
            $request->session()->flash('success','Record not found');
            return redirect()->route('sub-categories.index');
        }

        $categories = Category::orderBy('name','asc')->get();
        $data['categories'] = $categories;
        $data['subCategory'] = $subCategory;
        
        return view('admin.sub_category.edit',$data);
    }

    public function update(Request $request, $id) {
        $subCategory = SubCategory::find($id);
        if(empty($subCategory)) {
            $request->session()->flash('status','category not found');

            return response()->json([
                'status' => false,
                'notFound'=>true,
                'message' => "Sub Category not found!",
            ]);
        }

        $validator = validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:sub_categories,slug,'.$subCategory->id.',id',
            'category' => 'required',
            'status' => 'required'
        ]);


        if ($validator->passes()) {

            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->save();

            $request->session()->flash('status', 'Category updated successfully!');

            return response()->json([
                'status' => true,
                'message' => "Category updated successfully!",
            ]);


        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function destroy(Request $request, $id) {
        $subCategory = SubCategory::find($id);
        if(empty($subCategory)) {
            $request->session()->flash('status','category not found');

            return response()->json([
                'status' => false,
                'notFound'=>true,
                'message' => "Sub Category not found!",
            ]);
        }

        $subCategory->delete();

        $request->session()->flash('status', 'Category deleted successfully!');

        return response()->json([
            'status' => true,
            'message' => "Category deleted successfully!",
        ]);
    }
}
