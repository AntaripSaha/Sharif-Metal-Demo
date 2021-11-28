<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Controllers\BaseController;
use Modules\Product\Entities\Category;
use App\User;
use DB;
use DataTables;
class CategoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $data = Category::get();
        if(!$this->user->can('browse_category',app('Modules\Product\Entities\Product'))){
            return redirect()->route('users.index')->with('flash',array('status'=>'error','message'=>'permission denied'));
        } 
        if ($request->ajax()) {          
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                    $btn = "";
                    $btnDel ="";
                    $btn_view ='';
                    $btn_edit ='';
                    if($this->user->isOfficeAdmin()){
                        $btn_view ='<a class="mr-2 cp view-tr btn btn-success btn-sm" id="view-tr-'.$row->id.'"> View</a>'; 
                        $btn_edit ='<a class="mr-2 btn btn-info btn-sm cp edit-tr" id="edit-tr-'.$row->id.'">Edit</a>';
                        $btnDel = '<a class="btn btn-danger delete-tr btn-sm" id="delete-tr-'.$row->id.'">Delete</a>';
                    }
                    if($this->user->can('view_category',app('Modules\Product\Entities\Product')) ){
                        $btn_view ='<a class="mr-2 cp view-tr btn btn-success btn-sm" id="view-tr-'.$row->id.'"> View</a>'; 
                    }
                    if($this->user->can('edit_category',app('Modules\Product\Entities\Product')) ){
                        $btn_edit ='<a class="mr-2 cp edit-tr btn btn-info btn-sm" id="edit-tr-'.$row->id.'"> Edit</a>'; 
                    }
                    if($this->user->can('delete_category',app('Modules\Product\Entities\Product'))){
                        $btnDel ='<a class="mr-2 cp delete-tr btn btn-danger btn-sm" id="delete-tr-'.$row->id.'"> Delete</a>'; 
                    }
                    return $btn_view.$btn_edit.$btnDel;   
                    })
                    ->editColumn('status',function($row){
                            if ($row->status == 1) {
                                $status = 'Active';
                            }else{
                                $status = 'Inactive';
                            }
                            return $status;
                    })
                    ->addColumn('input',function($row){
                            $input = '<input type="checkbox" class="data-input ml-1"  name="data[]"  value="'.$row->id.'" >';
                            return $input;
                    })
                ->rawColumns(['action','input'])
                ->make(true); exit;
        }
        return view('product::category_index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function add_category(Request $request)
    {
        if(!$this->user->can('add_category',app('Modules\Product\Entities\Product'))){
            return redirect()->route('users.index')->with('flash',array('status'=>'error','message'=>'permission denied'));
        } 
        if($request->isMethod('post')){
            try{  
                $data=$request->all();
                Category::createcategory($data);
                return response()->json(['status'=>'success'], 200);
            }catch(\Exception $e){
                return response()->json(['status'=>$e->getMessage()], 500);
            }   
        }else{
            return view('product::add_category');exit;
        }
    }

    public function view_category($id)
    {
        if(!$this->user->can('view_category',app('Modules\Product\Entities\Product'))){
            return response()->json(['status'=>'permission denied'], 401);
        }
        $category = Category::where('id', $id)->first();
        return view('product::view_category', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit_category(Request $request,$id)
    {
        if(!$this->user->can('edit_category',app('Modules\Product\Entities\Product'))){
            return redirect()->route('users.index')->with('flash',array('status'=>'error','message'=>'permission denied'));
        } 
        if($request->isMethod('post')){
            try{  
                $data=$request->all();
                Category::updateCategory($data,$id);
                return response()->json(['status'=>'success'], 200);
            }catch(\Exception $e){
                return response()->json(['status'=>$e->getMessage()], 500);
            }   
        }else{
            $category = Category::whereId($id)->first();
            return view('product::edit_category',compact('category'));exit;
        }
    }

    // Delete Function
    public function delete_category(Request $request,$category_id){
        if(!$this->user->can('delete_category',app('Modules\Product\Entities\Product'))){
            return redirect()->route('users.index')->with('flash',array('status'=>'error','message'=>'permission denied'));
        }
        try{
            $category = Category::where('id',$category_id)->first();
            $category->delete();
            return response()->json(['status'=>'success'], 200);

        }catch(\Exception $e){
            return response()->json(['status'=>'error'], 500);
        }   
    }
}
