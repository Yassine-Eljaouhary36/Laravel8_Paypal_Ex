<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str; 
use Srmklive\PayPal\Services\ExpressCheckout;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth',['only'=>['create','store']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = Service::orderBy('created_at', 'desc')->with('user')->get();
        return view('service.index',compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('service.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'title' => 'required',
            'content' => 'required',
        ]);
        $amount = 1000;
        if($request->filled('premium')) $amount += 500;
        $data = [];
        $data['items'] = [
            [
                'name' => $request->title,
                'price' => $amount,
                'desc'  => $request->content,
                'qty' => 1
            ],
        ];
        $data['invoice_id'] = auth()->user()->id;
        $data['invoice_description'] = "Service #{$data['invoice_id']} Invoice";
        $data['return_url'] = route('success.payment');
        $data['cancel_url'] = route('cancel.payment');
        
        $data['total'] = $data['items'][0]['price']*$data['items'][0]['qty'];

        $paypalModule = new ExpressCheckout; 
        $res = $paypalModule->setExpressCheckout($data);
        $res = $paypalModule->setExpressCheckout($data, true);
        return redirect($res['paypal_link']);

    }
    public function paymentCancel(){
        return redirect()->route('service.create')->with('failed','Problem in processing Payment !');
    }
    public function paymentSuccess(Request $request){
        $paypalModule = new ExpressCheckout; 
        $response = $paypalModule->getExpressCheckoutDetails($request->token);
        if(in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])){
            $authed_user= auth()->user();
            $authed_user->services()->create([
                'title'=>$request->title,
                'slug'=>Str::slug($request->title),
                'content'=>$request->content,
                'premium'=>$request->filled('premium'),
            ]);

            return redirect()->route('service.index')->with('success','Item created successfully!');
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        //
    }
}
