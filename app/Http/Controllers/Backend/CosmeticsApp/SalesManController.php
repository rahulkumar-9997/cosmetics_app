<?php

namespace App\Http\Controllers\Backend\CosmeticsApp;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Salesman;
use App\Models\User;
use Exception;

class SalesManController extends Controller
{
    public function index()
    {
        $salesmen = Salesman::with('user')->orderBy('id', 'desc')->get();
        return view('backend.cosmetics-app.salesman.index', compact('salesmen'));
    }

    public function create(Request $request)
    {
        $form = '
        <div class="modal-body">
            <form method="POST" action="' . route('salesman.store') . '" accept-charset="UTF-8" enctype="multipart/form-data" id="addSalesMan">
                ' . csrf_field() . '
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Salesman Name *</label>
                            <input type="text" id="name" name="name" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone No.</label>
                            <input class="form-control" type="text" id="phone" name="phone" maxlength="10">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="profile_photo" class="form-label">Profile Photo</label>
                            <input type="file" id="profile_photo" name="profile_photo" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="address" class="form-label">Full Address</label>
                            <textarea class="form-control" id="address" rows="2" name="address"></textarea>
                        </div>
                    </div>
                    <div class="mb-3 col-md-3">
                        <label class="form-check-label" for="status">Status</label>
                        <div class="form-check mt-2 form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="status" name="status" checked>
                            <label class="form-check-label" for="status" >Active</label>
                        </div>
                    </div>
                    <div class="mb-3 col-md-3">
                        <label class="form-check-label text-info" for="create_user">Create User</label>
                        <div class="form-check mt-2 form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="create_user" name="create_user">
                            <label class="form-check-label" for="create_user">Yes</label>
                        </div>
                    </div>
                    <div class="modal-footer pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </form>
        </div>';
        return response()->json([
            'message' => 'Salesman Form created successfully',
            'form' => $form,
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'nullable',
                'email',
                'max:255',
                'required_if:create_user,on',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->has('create_user') && $request->create_user === 'on') {
                        if (empty($value)) {
                            return $fail('The email field is required when creating a user.');
                        }
                    }
                    if (!empty($value)) {
                        $salesmanExists = \App\Models\Salesman::where('email', $value)->exists();
                        if ($salesmanExists) {
                            return $fail('This email is already used by another salesman.');
                        }
                        $userExists = \App\Models\User::where('email', $value)->exists();
                        if ($userExists) {
                            return $fail('This email is already registered as a user.');
                        }
                    }
                },
            ],
            'phone' => 'nullable|string|max:10',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'address' => 'nullable|string',
            'status' => 'nullable',
            'create_user' => 'nullable',
        ]);
        DB::beginTransaction();
        try {
            $imageName = null;
            if ($request->hasFile('profile_photo')) {
                $destinationPath = public_path('images/salesman');
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                $safeName = Str::slug($request->name);
                $uniqueTimestamp = round(microtime(true) * 1000);
                $imageName = $safeName . '-' . $uniqueTimestamp . '.webp';

                $imageFile = $request->file('profile_photo');
                $image = Image::make($imageFile);
                $image->encode('webp', 75);
                $image->save($destinationPath . '/' . $imageName);
            }
            $userIdForSalesman = null;
            if ($request->has('create_user') && $request->create_user == 'on' && $request->email) {
                do {
                    $uniqueUserId = 'USR' . strtoupper(Str::random(6));
                } while (User::where('user_id', $uniqueUserId)->exists());
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'user_id' => $uniqueUserId,
                    'password' => Hash::make('password123'),
                    'phone_number' => $request->phone,
                    'address' => $request->address,
                    'profile_img' => $imageName,
                    'status' => $request->has('status') ? 1 : 0,
                    'user_type' => 'salesman',
                ]);
                /* Automatically assign "Salesman" role (id = 3) */
                $user->roles()->sync([3]);
                /* Automatically assign "Salesman" role (id = 3) */
                $userIdForSalesman = $user->id; 
            }
            $salesman = Salesman::create([
                'user_id' => $userIdForSalesman,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'profile_photo' => $imageName,
                'address' => $request->address,
                'status' => $request->has('status') ? 1 : 0,
            ]);
            DB::commit();
            $salesmen = Salesman::with('user')->orderBy('id', 'desc')->get();
            return response()->json([
                'status' => 'success',
                'message' => 'Salesman created successfully!',
                'salesmanListData' => view('backend.cosmetics-app.salesman.partials.salesman-list', compact('salesmen'))->render()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create salesman: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id){
        $salesman = Salesman::with('user')->findOrFail($id);
        $form = '
        <div class="modal-body">
            <form method="POST" action="'.route('salesman.update', $salesman->id).'" accept-charset="UTF-8" enctype="multipart/form-data" id="editSalesmanForm">
                '.csrf_field().'
                '.method_field('PUT').'
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Salesman Name *</label>
                            <input type="text" id="name" name="name" class="form-control" value="'.e($salesman->name).'">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control" value="'.e($salesman->email).'">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone No.</label>
                            <input class="form-control" type="text" id="phone" name="phone" maxlength="10" value="'.e($salesman->phone).'">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="profile_photo" class="form-label">Profile Photo</label>
                            <input type="file" id="profile_photo" name="profile_photo" class="form-control">
                            '.($salesman->profile_photo ? '<img src="'.asset('images/salesman/'.$salesman->profile_photo).'" width="80" class="mt-2">' : '').'
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="address" class="form-label">Full Address</label>
                            <textarea class="form-control" id="address" rows="2" name="address">'.e($salesman->address).'</textarea>
                        </div>
                    </div>
                    <div class="mb-3 col-md-2">
                        <label class="form-check-label" for="status">Status</label>
                        <div class="form-check mt-2 form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="status" name="status" '.($salesman->status ? 'checked' : '').'>
                            <label class="form-check-label" for="status">Active</label>
                        </div>
                    </div>';

                if ($salesman->user_id)
                {
                    $form .= '
                    <div class="mb-3 col-md-4">
                        <label class="form-label">Linked User</label><br>
                        <span class="badge bg-primary">'.$salesman->user->user_id.' ('.$salesman->user->email.')</span>
                    </div>';
                }
                else
                {
                    $form .= '
                    <div class="mb-3 col-md-4">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" id="create_user" name="create_user">
                            <label class="form-check-label" for="create_user">Create User</label>
                        </div>
                    </div>';
                }

            $form .= '
                    <div class="modal-footer pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
                </form>
            </div>';

        return response()->json([
            'message' => 'Salesman edit form loaded successfully',
            'form' => $form,
        ]);
    }

    public function update(Request $request, $id)
    {
        $salesman = Salesman::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'nullable',
                'email',
                'max:255',
                function ($attribute, $value, $fail) use ($request, $salesman) {
                    if ($request->has('create_user') && $request->create_user === 'on') {
                        if (empty($value)) {
                            $fail('Email is required when creating a user.');
                        } else {
                            $exists = User::where('email', $value)
                                ->where('id', '!=', $salesman->user_id)
                                ->exists();

                            if ($exists) {
                                $fail('This email is already taken.');
                            }
                        }
                    } elseif (!empty($value)) {
                        $exists = Salesman::where('email', $value)
                            ->where('id', '!=', $salesman->id)
                            ->exists();
                        if ($exists) {
                            $fail('This email is already used by another salesman.');
                        }
                    }
                },
            ],
            'phone' => 'nullable|string|max:10',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'address' => 'nullable|string',
            'status' => 'nullable',
        ]);

        DB::beginTransaction();
        try {
            $imageName = $salesman->profile_photo;
            if ($request->hasFile('profile_photo')) {
                $destinationPath = public_path('images/salesman');
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                if ($salesman->profile_photo && File::exists($destinationPath . '/' . $salesman->profile_photo)) {
                    File::delete($destinationPath . '/' . $salesman->profile_photo);
                }
                $safeName = Str::slug($request->name);
                $uniqueTimestamp = round(microtime(true) * 1000);
                $imageName = $safeName . '-' . $uniqueTimestamp . '.webp';
                $image = Image::make($request->file('profile_photo'))->encode('webp', 75);
                $image->save($destinationPath . '/' . $imageName);
            }
            if ($salesman->user_id) {
                $user = $salesman->user;
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone_number' => $request->phone,
                    'address' => $request->address,
                    'profile_img' => $imageName,
                    'status' => $request->has('status') ? 1 : 0,
                ]);
            } elseif ($request->has('create_user') && $request->create_user === 'on' && $request->email) {
                do {
                    $userCode = 'USR' . strtoupper(Str::random(6));
                } while (User::where('user_id', $userCode)->exists());
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'user_id' => $userCode,
                    'password' => Hash::make('password123'),
                    'phone_number' => $request->phone,
                    'address' => $request->address,
                    'profile_img' => $imageName,
                    'status' => $request->has('status') ? 1 : 0,
                    'user_type' => 'salesman',
                ]);
                /* Automatically assign "Salesman" role (role_id = 3)*/
                $user->roles()->sync([3]);
                /* Automatically assign "Salesman" role (role_id = 3)*/
                $salesman->user_id = $user->id;
            }
            $salesman->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'profile_photo' => $imageName,
                'address' => $request->address,
                'status' => $request->has('status') ? 1 : 0,
            ]);
            DB::commit();
            $salesmen = Salesman::with('user')->orderBy('id', 'desc')->get();
            return response()->json([
                'status' => 'success',
                'message' => 'Salesman updated successfully!',
                'salesmanListData' => view('backend.cosmetics-app.salesman.partials.salesman-list', compact('salesmen'))->render(),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update salesman: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $salesman = Salesman::findOrFail($id);
            if ($salesman->profile_photo) {
                $photoPath = public_path('images/salesman/' . $salesman->profile_photo);
                if (File::exists($photoPath)) {
                    File::delete($photoPath);
                }
            }
            if ($salesman->user_id) {
                $salesman->user()->delete();
            }
            $salesman->delete();
            DB::commit();
            $salesmen = Salesman::with('user')->orderBy('id', 'desc')->get();
            return response()->json([
                'status' => 'success',
                'message' => 'Salesman deleted successfully!',
                'salesmanListData' => view('backend.cosmetics-app.salesman.partials.salesman-list', compact('salesmen'))->render()
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete salesman: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function toggleStatus($id)
    {
        try {
            $salesman = Salesman::with('user')->findOrFail($id);
            $salesman->status = !$salesman->status;
            $salesman->save();
            /*Update user table status */
            if ($salesman->user) {
                $salesman->user->status = $salesman->status;
                $salesman->user->save();
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Salesman status updated successfully!',
                //'newStatus' => $salesman->status ? 'Active' : 'Inactive',
                //'badgeClass' => $salesman->status ? 'bg-success' : 'bg-danger',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update status: ' . $e->getMessage(),
            ], 500);
        }
    }



}
