<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller
{
    public function index() {
        $categories = Category::all();                
        return view('admin.categories.index', compact('categories'));
    }

    public function create() {
        $category = new Category();
        return view('admin.categories.create', compact('category'));
    }

    public function store(Request $request) {

        // Validate the form
        $request->validate([
           'name' => 'required',
            'image' => 'image|required',
            'status' => 'required'
        ]);

        $image_name='';

        // Upload the image
        if ($request->hasFile('image')) {
            $image = $request->image;
            $image_name=  time().'.'.$image->getClientOriginalName();                                   
            $image->move('uploads', $image_name);
        }

        // Save the data into database
        Category::create([
            'name' => $request->name,            
            'image' => $image_name,            
            'status' => $request->status
        ]);
        

        // Sessions Message
        $request->session()->flash('msg','Your category has been added');

        // Redirect
        return redirect('admin/categories/create');
    }

    public function edit($id) {
        $category = Category::find($id);                
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id) {

        // Find the product

        $Category = Category::find($id);
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

        // Updating the product
        $Category->update();

        // Store a message in session
        $request->session()->flash('msg', 'Category has been updated');        

        // Redirect
        return redirect('admin/categories');
    }

    public function show($id) {
        $category = Category::find($id);
        return view('admin.categories.details', compact('category'));
    }

    public function destroy($id) {
        // Delete the product
        Category::destroy($id);

        // Store a message
        session()->flash('msg','Category has been deleted');

        // Redirect back
        return redirect('admin/categories');
    }
}