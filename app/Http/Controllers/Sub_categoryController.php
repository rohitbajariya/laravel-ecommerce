<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Sub_category;
use App\Category;


class Sub_categoryController extends Controller
{
    public function index() {        
        $categories = Sub_category::with('main_category')->get();   
        return view('admin.sub_categories.index', compact('categories'));
    }

    public function create() {
        $sub_category = new Sub_category();        
        $category=category::select('id','name')->where('status',1 )->get();

        return view('admin.sub_categories.create', array('category'=> $sub_category, 'main_category' => $category ));
    }

    public function store(Request $request) {
        // Validate the form
        $request->validate([
           'name' => 'required',
            'image' => 'image|required',
            'status' => 'required',
            'category_id' =>'required' 
        ]);

        $image_name='';

        // Upload the image
        if ($request->hasFile('image')) {
            $image = $request->image;
            $image_name=  time().'.'.$image->getClientOriginalName();
            $image->move('uploads', $image_name);            
        }

        // Save the data into database
        Sub_category::create([
            'name' => $request->name,            
            'image' => $image_name,            
            'status' => $request->status,
            'category_id' => $request->category_id,
        ]);
        

        // Sessions Message
        $request->session()->flash('msg','Your sub category has been added');

        // Redirect
        return redirect('admin/sub_categories/create');
    }

    public function edit($id) {
        $sub_category = Sub_category::find($id);
        $category = Category::get();   
        return view('admin.sub_categories.edit', array('category'=> $sub_category, 'main_category' => $category ));
    }

    public function update(Request $request, $id) {

        // Find the product

        $Category = Sub_category::find($id);
        // Validate The form
        $request->validate([
           'name' => 'required'
        ]);

        // Check if there is any image
        if ($request->hasFile('image')) {
            // Check if the old image exists inside folder
            if (file_exists(public_path('uploads/') . $Category->image)) {
                unlink(public_path('uploads/') . $Category->image);
            }

            // Upload the new image
            $image = $request->image;
            $image->move('uploads', $image->getClientOriginalName());
            $Category->image = $request->image->getClientOriginalName();
        }

        $Category->name = $request->name;                
        $Category->status = $request->status;
        $Category->category_id = $request->category_id;


        // Updating the product
        $Category->update();

        // Store a message in session
        $request->session()->flash('msg', 'Sub category has been updated');        

        // Redirect
        return redirect('admin/sub_categories');
    }

    public function show($id) {
        $category = Sub_category::find($id);
        return view('admin.sub_categories.details', compact('category'));
    }

    public function destroy($id) {
        // Delete the product
        Sub_category::destroy($id);

        // Store a message
        session()->flash('msg','Sub category has been deleted');

        // Redirect back
        return redirect('admin/sub_categories');
    }

    public function get_category(Request $request){
       $main_cat=$request->id;
       $sub=Sub_category::where('category_id',$main_cat)->get();
       echo json_encode($sub);
    }
}