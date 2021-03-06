<?php

namespace App\Http\Controllers\Category;

use App\Category;
use Illuminate\Http\Request;
use \App\Http\Controllers\ApiController;
use App\Transformers\CategoryTransformer;


class CategoryController extends ApiController
{

    public function __construct(){

        parent::__construct();

        //ADICIONADA FILTRADA DO MIDDLEWARE 
        $this->middleware('transform.input'.CategoryTransformer::class)->only(['store','update']);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        return $this->showAll($categories);        ;
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name'=>'required',
            'description'=>'required'
        ];

        $this->validate($request,$rules);
        $category = Category::create( $request->all() );

        return $this->showOne($category,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
    
        return $this->showOne($category);
    }

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $category->fill($request->intersect([
            'name',
            'description'
        ]));    

        if($category->isClean()):
            return $this->errorResponse("Para atualizar a categoria necessita de valores diferentes dos originais",422);
        endif;

        $category->save();
        return $this->showOne($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return $this->showOne($category);

    }
}
