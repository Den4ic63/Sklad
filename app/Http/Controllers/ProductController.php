<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ProductController extends Controller
{
    /**
     * Отобразить список ресурсов.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:product-list|product-create|product-edit|product-delete', ['only' => ['index','show']]);
        $this->middleware('permission:product-create', ['only' => ['create','store']]);
        $this->middleware('permission:product-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:product-delete', ['only' => ['destroy']]);
        $this->middleware('permission:show-stats',['only'=>['stats','cheakstats']]);
    }
    /**
     * Отобразить список ресурсов.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $user =Auth::user()->email;
        $products = Product::latest()->paginate(5);
        return view('products.index',compact('products'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Отобразить форму для создания нового ресурса.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Поместить только что созданный ресурс в хранилище.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request,User $user)
    {
        request()->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);

        $product=Product::create(
            [
                'name'=>$request->name,
                'detail'=>$request->detail,
                'date_project_end'=>$request->input('date_project_end','not_set'),
                'condition'=>$request->input('condition','Проект на рассмотрении'),
                'user_name'=>Auth::user()->name,
                'email'=>Auth::user()->email
            ]
        );

        return redirect()->route('products.index')
            ->with('success','Product created successfully.');
    }

    /**
     * Отобразить указанный ресурс.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(Product $product)
    {
        return view('products.show',compact('product'));
    }

    /**
     * Отобразить форму для редактирования указанного
     * ресурса.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Product $product)
    {
        return view('products.edit',compact('product'));
    }

    /**
     * Обновить указанный ресурс в хранилище.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Product $product)
    {
        request()->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')
            ->with('success','Product updated successfully');
    }

    /**
     * Удалить указанный ресурс из хранилища.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success','Product deleted successfully');
    }

    public function cheakstats()
    {

        $all_project =DB::table('products')->select(DB::raw('condition'))->count();
        $start_project =DB::table('products')->where('condition','=','Проект начат')->count();
        $end_project =DB::table('products')->where('condition','=','Проект закончен')->count();
        $observe_project =DB::table('products')->where('condition','=','Проект на рассмотрении')->count();
        $piechart =[
            [
                'category'=> 'START',
                'value'=>$start_project
            ],
            [
                'category'=> 'END',
                'value'=>$end_project
            ],
            [
                'category'=> 'OBSERVE',
                'value'=>$observe_project
            ],
        ];

        $pie=json_encode($piechart);

        return view('products.stats',['pie'=>$pie],compact('all_project','start_project','end_project','observe_project'));
    }

    public function CreatePdf()
    {
        $all_project =DB::table('products')->select(DB::raw('condition'))->count();
        $start_project =DB::table('products')->where('condition','=','Проект начат')->count();
        $end_project =DB::table('products')->where('condition','=','Проект закончен')->count();
        $observe_project =DB::table('products')->where('condition','=','Проект на рассмотрении')->count();
        $date=Carbon::now();

        $data =[
            'all'=>$all_project,
            'start'=>$start_project,
            'end'=>$end_project,
            'observe'=>$observe_project,
            'date'=>$date
        ];
        $pdf = PDF::loadView('products.pdf', $data);
        return $pdf->download('Statistic.pdf');
    }
}
