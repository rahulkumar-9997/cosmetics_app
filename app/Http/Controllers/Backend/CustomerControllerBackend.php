<?php
namespace App\Http\Controllers\Backend;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Exception;


class CustomerControllerBackend extends Controller
{
    public function index(Request $request){
        $data['customer_list'] = Customer::orderBy('id', 'desc')->paginate(15);
        return view('backend.manage-customer.index', compact('data'));
    }

    public function create(Request $request){
        return view('backend.manage-customer.create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'status' => $request->has('status') ? 1 : 0
        ]);
        $validated = $request->validate([
            'firm_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone_number' => ['nullable','regex:/^(\+91)?[6-9]\d{9}$/'],
            'email' => 'nullable|email|unique:customers,email',
            'gst_no' => ['nullable','regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[A-Z0-9]{1}[Z]{1}[A-Z0-9]{1}$/'],
            'country' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'pin_code' => 'nullable|digits:6',
            'permanent_address' => 'nullable|string',
            'profile_img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:8192',
            'status' => 'nullable|boolean',
        ]);


        $imageName = null;
        DB::beginTransaction();
        try {
            if ($request->hasFile('profile_img')) {
                $image = $request->file('profile_img');
                $destination_path = public_path('images/customer/');
                if (!file_exists($destination_path)) {
                    mkdir($destination_path, 0777, true);
                }
                $slugFirmName = Str::slug($validated['firm_name']);
                $imageName = $slugFirmName . '_' . time() . '.webp';
                $img = Image::make($image->getRealPath())->encode('webp', 100);
                $img->save($destination_path . $imageName);
            }
            $customer = Customer::create([
                'firm_name' => $validated['firm_name'],
                'contact_person' => $validated['contact_person'] ?? null,
                'phone_number' => $validated['phone_number'] ?? null,
                'email' => $validated['email'] ?? null,
                'gst_no' => $validated['gst_no'] ?? null,
                'country' => $validated['country'] ?? null,
                'state' => $validated['state'] ?? null,
                'city' => $validated['city'] ?? null,
                'pin_code' => $validated['pin_code'] ?? null,
                'permanent_address' => $validated['permanent_address'] ?? null,
                'profile_img' => $imageName,
                'status' => $request->has('status') ? true : false,
                'added_by' => Auth::check() ? Auth::id() : null,
                'password' => Hash::make('defaultpassword'),
            ]);
            DB::commit();
            return redirect()->route('manage-customer.index')->with('success', 'Customer created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($imageName && file_exists(public_path('images/customer/' . $imageName))) {
                unlink(public_path('images/customer/' . $imageName));
            }
            return redirect()->back()->withInput()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('backend.manage-customer.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $request->merge([
            'status' => $request->has('status') ? 1 : 0
        ]);
        $validated = $request->validate([
            'firm_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone_number' => ['nullable','regex:/^(\+91)?[6-9]\d{9}$/'],
            'email' => 'nullable|email|unique:customers,email,' . $customer->id,
            'gst_no' => ['nullable','regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[A-Z0-9]{1}[Z]{1}[A-Z0-9]{1}$/'],
            'country' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'pin_code' => 'nullable|digits:6',
            'permanent_address' => 'nullable|string',
            'profile_img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:8192',
            'status' => 'nullable|boolean',
        ]);
        DB::beginTransaction();
        try {
            $imageName = $customer->profile_img;
            if ($request->hasFile('profile_img')) {
                $image = $request->file('profile_img');
                $destination_path = public_path('images/customer/');
                if (!file_exists($destination_path)) {
                    mkdir($destination_path, 0777, true);
                }
                if ($customer->profile_img && file_exists($destination_path . $customer->profile_img)) {
                    unlink($destination_path . $customer->profile_img);
                }
                $slugFirmName = Str::slug($validated['firm_name']);
                $imageName = $slugFirmName . '_' . time() . '.webp';
                $img = Image::make($image->getRealPath())->encode('webp', 100);
                $img->save($destination_path . $imageName);
            }
            $customer->update([
                'firm_name' => $validated['firm_name'],
                'contact_person' => $validated['contact_person'] ?? null,
                'phone_number' => $validated['phone_number'] ?? null,
                'email' => $validated['email'] ?? null,
                'gst_no' => $validated['gst_no'] ?? null,
                'country' => $validated['country'] ?? null,
                'state' => $validated['state'] ?? null,
                'city' => $validated['city'] ?? null,
                'pin_code' => $validated['pin_code'] ?? null,
                'permanent_address' => $validated['permanent_address'] ?? null,
                'profile_img' => $imageName,
                'status' => $request->has('status') ? true : false,
            ]);
            DB::commit();
            return redirect()->route('manage-customer.index')->with('success', 'Customer updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($imageName) && $imageName !== $customer->profile_img && file_exists(public_path('images/customer/' . $imageName))) {
                unlink(public_path('images/customer/' . $imageName));
            }
            return redirect()->back()->withInput()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    public function toggleStatus(Request $request, $id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $customer->status = !$customer->status;
            $customer->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Customer status updated successfully.',
                'data' => [
                    'status' => $customer->status
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleApproval(Request $request, $id)
    {
        $user = Auth::user();
        if(!$user || !$user->hasAnyRole(['Super Admin (Wizards)', 'Main Admin (Owner)'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized action'
            ], 403);
        }

        try {
            $customer = Customer::findOrFail($id);
            $customer->approval_status = !$customer->approval_status;
            $customer->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Customer approval status updated successfully.',
                'data' => [
                    'approval_status' => $customer->approval_status
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update approval status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $customer = Customer::findOrFail($id);
            if ($customer->profile_img && file_exists(public_path('images/customer/' . $customer->profile_img))) {
                unlink(public_path('images/customer/' . $customer->profile_img));
            }
            $customer->delete();
            DB::commit();
            return redirect()->route('manage-customer.index')->with('success', 'Customer deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('manage-customer.index')->with('error', 'Failed to delete customer: ' . $e->getMessage());
        }
    }
    
}
