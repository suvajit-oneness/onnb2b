<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Interfaces\InvoiceInterface;
use Facade\FlareClient\Stacktrace\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File as FacadesFile;

class InvoiceController extends Controller
{
    public function __construct(InvoiceInterface $invoiceInterface)
    {
        $this->invoiceInterface = $invoiceInterface;
    }
    public function index(Request $request)
    {
        $collection = $this->invoiceInterface->invoicedetails();
        return view('front.retailer.index', compact('collection'));
    }

    public function create()
    {
        return view('front.retailer.create');
    }

    public function add(Request $request)
    {
        $request->validate([
            'amount' => 'required',
            'description' => 'required',
            'image' => 'required|mimes:jpeg,png|file|max:1024',
        ]);

        $collection = $request->except('_token');

        $image = uniqid() . $request->file('image')->getClientOriginalName();
        $request->file('image')->move(public_path() . '/uploads/invoice', $image);

        $collection['image'] = $image;

        $data = $this->invoiceInterface->addInvoice($collection);
        if ($data)
            return redirect(route('front.invoice.index'))->with(['success' => "Uploaded Successfully"]);
        else
            return redirect()->back()->with(['failure' => "Upload cannot be done!"]);
    }

    public function edit($id)
    {
        $data = $this->invoiceInterface->invoicedetailsById($id);
        return view('front.retailer.edit', compact('data'));
    }
    public function update(Request $request)
    {
        if ($request->file('image')) {
            $image = public_path() . '/uploads/invoice//' . DB::table('invoice_retailer')->where('id', $request->id)->get('image')[0]->image;
            FacadesFile::delete($image);

            $image1 = uniqid() . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path() . '/uploads/invoice', $image1);

            $data = $request->except('_token', $request->id);

            $data['image'] = $image1;

            $update = $this->invoiceInterface->updateInvoice($request->id, $data);
            if ($update)
                return redirect(route('front.invoice.index'))->with(['success' => "Uploaded Successfully"]);
            else
                return redirect()->back()->with(['failure' => "Upload cannot be done!"]);
        } else {
            $image = DB::table('invoice_retailer')->where('id', $request->id)->get('image')[0]->image;

            $data = $request->except('_token', $request->id);

            $data['image'] = $image;

            $update = $this->invoiceInterface->updateInvoice($request->id, $data);
            if ($update)
                return redirect(route('front.invoice.index'))->with(['success' => "Uploaded Successfully"]);
            else
                return redirect()->back()->with(['failure' => "Upload cannot be done!"]);
        }
    }
    public function delete($id)
    {
        $data = $this->invoiceInterface->deleteInvoiceById($id);
        if ($data)
            return redirect(route('front.invoice.index'))->with(['success' => "Deleted Successfully"]);
        else
            return redirect()->back()->with(['failure' => "Data cannot be deleted!"]);
    }
}
