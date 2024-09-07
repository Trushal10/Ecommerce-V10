@extends('admin.layout.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">					
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Product</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{route('products.index')}}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <form action="" method="post" name="createProductFrom" id="createProductFrom" >
            @csrf
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-body">								
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="title">Title</label>
                                        <input type="text" name="title" id="title" class="form-control" value="{{$product->title}}" placeholder="Title">
                                        <p class="error"></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="slug">Slug</label>
                                        <input type="text" readonly name="slug" id="slug" class="form-control" value="{{$product->slug}}" placeholder="slug">
                                        <p class="error"></p>	
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" cols="30" rows="10" class="summernote" value="{{$product->description}}" placeholder="Description"></textarea>
                                    </div>
                                </div>                                            
                            </div>
                        </div>	                                                                      
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Media</h2>								
                            <div id="image" class="dropzone dz-clickable">
                                <div class="dz-message needsclick">    
                                    <br>Drop files here or click to upload.<br><br>                                            
                                </div>
                            </div>
                        </div>	                                                                      
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Pricing</h2>								
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="price">Price</label>
                                        <input type="text" name="price" id="price" class="form-control" value="{{$product->price}}" placeholder="Price">
                                        <p class="error"></p>	
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="compare_price">Compare at Price</label>
                                        <input type="text" name="compare_price" id="compare_price" class="form-control" value="{{$product->compare_price}}" placeholder="Compare Price">
                                        <p class="text-muted mt-3">
                                            To show a reduced price, move the product’s original price into Compare at price. Enter a lower value into Price.
                                        </p>	
                                    </div>
                                </div>                                            
                            </div>
                        </div>	                                                                      
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Inventory</h2>								
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sku">SKU (Stock Keeping Unit)</label>
                                        <input type="text" name="sku" id="sku" class="form-control" value="{{$product->sku}}" placeholder="sku">
                                        <p class="error"></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="barcode">Barcode</label>
                                        <input type="text" name="barcode" id="barcode" class="form-control" value="{{$product->barcode}}" placeholder="Barcode">	
                                    </div>
                                </div>   
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <div class="custom-control custom-checkbox">
                                            <input type="hidden" name="track_qty" value="No">
                                            <input class="custom-control-input" type="checkbox" id="track_qty" name="track_qty" value="Yes"{{($product->is_featured == "Yes") ? 'checked':''}}>
                                            <label for="track_qty" class="custom-control-label">Track Quantity</label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <input type="number" min="0" name="qty" id="qty" class="form-control" value="{{$product->qty}}" placeholder="Qty">
                                        <p class="error"></p>	
                                    </div>
                                </div>                                         
                            </div>
                        </div>	                                                                      
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-body">	
                            <h2 class="h4 mb-3">Product status</h2>
                            <div class="mb-3">
                                <select name="status" id="status" class="form-control">
                                    <option {{ ($product->status == 1) ? 'selected':'' }} value="1">Active</option>
                                    <option {{ ($product->status == 0) ? 'selected':'' }} value="0">Block</option>
                                </select>
                            </div>
                        </div>
                    </div> 
                    <div class="card">
                        <div class="card-body">	
                            <h2 class="h4  mb-3">Product category</h2>
                            <div class="mb-3">
                                <label for="category">Category</label>
                                <select name="category" id="category" class="form-control">
                                    <option value="">Select Category</option>
                                    @if ($categories->isNotEmpty())
                                        @foreach($categories as $category)
                                        <option {{($product->category_id == $category->id) ? 'selected':''}} value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach  
                                    @endif
                                </select>
                                <p class="error"></p>
                            </div>
                            <div class="mb-3">
                                <label for="subcategory">Sub category</label>
                                <select name="sub_category" id="sub_category" class="form-control">
                                    @if ($subCategory->isNotEmpty())
                                        @foreach($subCategory as $subcategory)
                                        <option {{($product->category_id == $subcategory->id) ? 'selected':''}} value="{{$subcategory->id}}">{{$subcategory->name}}</option>
                                        @endforeach  
                                    @endif
                                    {{-- <option {{($product->sub_category_id == $category->id) ? 'selected':''}} value="{{$subCategory->id}}">{{$subCategory->name}}</option> --}}
                                </select>
                            </div>
                        </div>
                    </div> 
                    <div class="card mb-3">
                        <div class="card-body">	
                            <h2 class="h4 mb-3">Product brand</h2>
                            <div class="mb-3">
                                <select name="brands" id="brands" class="form-control">
                                    <option value="">Select Brand</option>
                                    @if ($brands->isNotEmpty()) 
                                        @foreach ($brands as $brand) 
                                            <option value="{{$brand->id }}">{{ $brand->name }}</option>   
                                        @endforeach                                         
                                    @endif
                                </select>
                                <p class="error"></p>
                            </div>
                        </div>
                    </div> 
                    <div class="card mb-3">
                        <div class="card-body">	
                            <h2 class="h4 mb-3">Featured product</h2>
                            <div class="mb-3">
                                <select name="is_featured" id="is_featured" class="form-control">
                                    <option value="No">No</option>
                                    <option value="Yes">Yes</option>
                                    <option {{($product->is_featured == "Yes") ? 'selected':'' }} value="Yes">Active</option>
                                    <option {{($product->is_featured == "No") ? 'selected':'' }} value="No">Block</option>                                                
                                </select>
                                <p class="error"></p>
                            </div>
                        </div>
                    </div>                                 
                </div>
            </div>
            
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="{{route('products.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </div>
        </form>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('customJs')
    <script>
        $("#createProductFrom").submit(function (event){
            event.preventDefault();
            var element=$(this);
            $("button[type=submit]").prop('disabled',true)

            $.ajax({
                url:'{{route("brands.update",$brand->id)}}',
                type:'put',
                data:element.serializeArray(),
                dataType:'json', 
                success:function (response) {
                    $("button[type=submit]").prop('disabled',false)
                    if (response["status"] == true) {
                        window.location.href="{{route('brands.index')}}"

                        $("#name").removeClass('is-invalid').siblings('p')
                                .removeClass('invalid-feedback').html("");

                        $("#slug").removeClass('is-invalid').siblings('p')
                                .removeClass('invalid-feedback').html("");
                    } else {

                        if(response['notFound'] == true) {
                            window.location.href="{{route('brands.index')}}"
                            return false;
                        }

                        var errors = response['errors'];
                        // console.log(errors)

                        if(errors['name']) {
                            $("#name").addClass('is-invalid').siblings('p')
                                .addClass('invalid-feedback').html(errors['name']);
                        } else {
                            $("#name").removeClass('is-invalid').siblings('p')
                                .removeClass('invalid-feedback').html("");
                        }

                        if(errors['slug']) {
                            $("#slug").addClass('is-invalid').siblings('p')
                                .addClass('invalid-feedback').html(errors['slug']);
                        } else {
                            $("#slug").removeClass('is-invalid').siblings('p')
                                .removeClass('invalid-feedback').html("");
                        }
                    }

                },error:function (jqXRH, exception) {
                    console.log("some thing went to wrong")
                }
            })
        })
        $('#name').change(function (){
            element = $(this);
            $("button[type=submit]").prop('disabled',true)
            $.ajax({
                url: '{{route('getSlug')}}',
                type: 'get',
                data: {title:element.val()},
                dataType: 'json',
                success: function (response) {
                    $("button[type=submit]").prop('disabled',false)
                    if(response["status"] == true) {
                        $("#slug").val(response['slug']);
                    }
                }
            });
        })

    </script>
@endsection
