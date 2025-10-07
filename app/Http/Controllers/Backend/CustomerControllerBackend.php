<?php
namespace App\Http\Controllers\Backend;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Orders;
use App\Imports\CustomerImport;
use App\Models\GroupCategories;
use App\Models\CustomerCareRequest;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Exception;


class CustomerControllerBackend extends Controller
{
    public function index(Request $request){
        $data['customer_list'] = Customer::orderBy('id', 'desc')->paginate(15);
        $data['category_group'] = GroupCategories::with('groups')->get();
        if ($request->ajax()) {
            return view('backend.manage-customer.partials.customer-list', compact('data'))->render();
        }
        //return response()->json($data['category_group']);
        return view('backend.manage-customer.index', compact('data'));
    }
    
}
