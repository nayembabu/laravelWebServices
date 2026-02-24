<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

use App\Models\Service;
use App\Models\UserServiceOrder;
use App\Models\UserBalanceAdd;
use App\Models\UserBalanceCut;
use App\Models\PaymentMethod;
use App\Models\UserRecharge;
use App\Models\SaveRowJsonData;
use App\Models\Voter;
use App\Models\User;

class UserController extends Controller
{
    public function dashboard()
    {
        $services = Service::all();
        $lastOrders = auth()->user()->serviceOrders()
                                    ->orderBy('created_at', 'desc')
                                    ->take(10)
                                    ->get();
        return view('user.dashboard', compact('lastOrders', 'services'));
    }

    public function view_all_services()
    {
        $services = Service::where('order_not', 1)->get();
        $querys = UserServiceOrder::with('service')->where('user_id', Auth::id())->whereDate('created_at', Carbon::today())->orderBy('id', 'desc')->get();
        return view('user.services_page', compact('services', 'querys'));
    }

    public function order_services(Request $request)
    {
        // Validate input
        $request->validate([
            'service' => 'required|exists:services,id',
            'order_details' => 'required|min:5'
        ]);

        $service = Service::findOrFail($request->service);
        $userId  = Auth::id();

        // Calculate balance
        $totalAdd = UserBalanceAdd::where('user_id', $userId)->sum('amount');
        $totalCut = UserBalanceCut::where('user_id', $userId)->sum('amount');
        $currentBalance = $totalAdd - $totalCut;

        // Balance check
        if ($currentBalance < $service->rate) {
            return back()->with('error', 'আপনার ব্যালেন্স পর্যাপ্ত নয়। অর্ডার করা সম্ভব নয়।');
        }

        DB::beginTransaction();

        try {

            // ✅ Place order (store to variable)
            $order = UserServiceOrder::create([
                'user_id'       => $userId,
                'service_id'    => $service->id,
                'order_details' => $request->order_details,
                'amount'        => $service->rate,
                'status'        => 'pending',
            ]);

            // ✅ Cut balance
            UserBalanceCut::create([
                'user_id' => $userId,
                'amount'  => $service->rate,
                'reason'  => 'service_order',
                'note'    => 'Service Order ID: ' . $order->id,
            ]);

            DB::commit();

            return back()->with('success', 'সার্ভিস অর্ডার সফল হয়েছে ✅');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'কিছু সমস্যা হয়েছে, আবার চেষ্টা করুন ❌');
        }
    }

    public function download_order_file()
    {
        $orders = UserServiceOrder::with('service')
                    ->where('user_id', Auth::id())
                    ->latest()
                    ->get();

        return view('user.download_view_file', compact('orders'));
    }

    public function download_make_file()
    {
        $make = Voter::where('user_id', Auth::id())
                    ->latest()
                    ->get();

        return view('user.download_make_view_file', compact('make'));
    }


    public function ordersAjax(Request $request)
    {

        $userId = auth()->id();

        $query = UserServiceOrder::with('service')->where('user_id', $userId)->orderBy('created_at', 'desc');

        // যদি min/max date থাকে
        if ($request->minDate) {
            $query->whereDate('created_at', '>=', $request->minDate);
        }
        if ($request->maxDate) {
            $query->whereDate('created_at', '<=', $request->maxDate);
        }

        // যদি কোন ডেট না আসে, ডিফল্টে আজকের অর্ডার
        if (!$request->minDate && !$request->maxDate) {
            $query->whereDate('created_at', Carbon::today());
        }

        // গ্লোবাল সার্চ
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_details', 'like', "%$search%")
                ->orWhereHas('service', function($q2) use ($search) {
                    $q2->where('name', 'like', "%$search%");
                });
            });
        }

        // Pagination
        $perPage = 100;
        $page = $request->page ?? 1;
        $orders = $query->orderBy('created_at', 'desc')
                        ->skip(($page - 1) * $perPage)
                        ->take($perPage)
                        ->get();

        $totalItems = $query->count();
        $totalPages = ceil($totalItems / $perPage);

        // JSON response
        $data = $orders->map(function($order) {
            return [
                'id' => $order->id,
                'type' => $order->service->name ?? 'নির্দিষ্ট নেই',
                'info' => $order->order_details,
                'status' => $order->status,
                'note' => $order->admin_note ?? '',
                'downloadable' => $order->status === 'approved',
                'file' => $order->admin_file ? $order->admin_file : null,
                'rate' => '৳'.number_format($order->amount),
                'time' => $order->created_at->format('Y-m-d H:i')
            ];
        });

        return response()->json([
            'data' => $data,
            'totalPages' => $totalPages
        ]);

    }

    public function deposite_user_accounts()
    {
        $payments = PaymentMethod::all();
        $recharges = UserRecharge::with('paymentmethod')
                        ->where('user_id', Auth::id())
                        ->latest()
                        ->get();
        return view('user.deposite_page', compact('payments', 'recharges'));
    }


    public function deposite_request(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:payment_methods,id',
            'amount' => 'required|numeric|min:1',
            'trx_id' => 'required|string|max:255',
        ]);

        try {

            $recharge = UserRecharge::create([
                'user_id' => auth()->id(),
                'payment_id' => $request->payment_id,
                'amount' => $request->amount,
                'trx_id' => $request->trx_id,
                'status' => 'pending', // default
            ]);

            // 🔔 Telegram Notification
            $message = "💰 New Deposit Request\n\n"
            . "👤 User: ".auth()->user()->name."\n"
            . "💵 Amount: ".$request->amount."\n"
            . "🧾 TRX ID: ".$request->trx_id."\n"
            . "📅 Time: ".$recharge->created_at->format('d M Y h:i A');

            // ✅ multiple admin send
            foreach (config('services.telegram.chat_ids') as $chatId) {
                Http::post(
                    "https://api.telegram.org/bot".config('services.telegram.token')."/sendMessage",
                    [
                        'chat_id' => $chatId,
                        'text' => $message,
                    ]
                );
            }

            return back()->with('success', 'ডিপোজিট রিকোয়েস্ট সফলভাবে সাবমিট হয়েছে ✅ ১০ মিনিট অপেক্ষা করুন। ');

        } catch (\Exception $e) {

            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function view_user_profile()
    {
        return view('user.profile_page');
    }

    public function update_user_profile(Request $request)
    {
        $user = auth()->user();

        // ✅ Validation
        $request->validate([
            'name'      => 'required|string|max:255',
            'username'  => 'required|string|max:255|unique:users,username,' . $user->id,
            'email'     => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone'     => 'required|string|max:20',
            'password'  => 'nullable|min:6|confirmed',
        ]);

        // ✅ Base data
        $data = [
            'name'      => $request->name,
            'username'  => $request->username,
            'email'     => $request->email,
            'phone'     => $request->phone,
        ];

        // ✅ যদি password দেয়া থাকে
        if ($request->filled('password')) {
            $data['password']       = Hash::make($request->password);
            $data['show_password']  = $request->password;
        }

        // ✅ Update
        $user->update($data);

        return back()->with('success', 'প্রোফাইল সফলভাবে আপডেট হয়েছে ✅');
    }

    public function signtonid()
    {
        return view('user.sign2nid');
    }

    public function signtonid_api_fetch(Request $request)
    {
        // প্রথমে এই user এর ব্যালেন্স চেক করো।
        $userId = auth()->id();
        $currentBalance = auth()->user()->balance();

        // services টেবিলের 18 নং রো এর rate বের করো
        $service18 = Service::find(18);
        $requiredRate = $service18 ? $service18->rate : null;

        // ব্যালেন্স চেক করো
        if (!$requiredRate || $currentBalance < $requiredRate) {
            return response()->json(['error' => 'আপনার ব্যালেন্স পর্যাপ্ত নয়।']);
        }

        try {
            // Validate the uploaded file
            $request->validate([
                'pdf' => 'required|file|mimes:pdf|max:20480', // 20MB max
            ]);

            if (!$request->hasFile('pdf')) {
                return response()->json(['error' => 'No PDF file found in request'], 400);
            }

            $pdf = $request->file('pdf');

            // Prepare file for sending with Guzzle
            $client = new \GuzzleHttp\Client();
            $response = $client->request('POST', 'https://api.rampur-server.xyz/', [
                'multipart' => [
                    [
                        'name'     => 'pdf',
                        'contents' => fopen($pdf->getPathname(), 'r'),
                        'filename' => $pdf->getClientOriginalName(),
                    ],
                ],
                // Optionally you can add headers or timeout
            ]);

            $body = $response->getBody()->getContents();

            $json = json_decode($body, true);
            $data = $json['data'];


            SaveRowJsonData::create([
                'row_data' => $json, // পুরো JSON ডাটা
                'nid'      => isset($data['nationalId']) ? $data['nationalId'] : null,
                'pin'      => isset($data['pin']) ? $data['pin'] : null,
                'dob'      => isset($data['dateOfBirth']) ? $data['dateOfBirth'] : null,
            ]);

            UserBalanceCut::create([
                'user_id' => $userId,
                'amount'  => $service18->rate,
                'reason'  => 'Make Sign 2 NID',
                'note'    => 'Service Sign 2 NID',
            ]);

            // images একটা array আকারে আছে, এটাকে ভেঙ্গে দুইটা ভ্যারিয়েবলে দিয়ে দাও
            $image1 = isset($data['userIMG']) ? $data['userIMG'] : null;
            $image2 = isset($data['signIMG']) ? $data['signIMG'] : null;

            // $photoPath = $this->saveBase64ToPublic($image1, 'photo');
            // $signPath  = $this->saveBase64ToPublic($image2, 'sign');

            $photoPath = $this->savePhotoFromUrl($image1, 'photo');
            $signPath  = $this->savePhotoFromUrl($image2, 'sign');

            $nid              = isset($data['nationalId']) ? $data['nationalId'] : null;
            $pin              = isset($data['pin']) ? $data['pin'] : null;
            $formNo           = isset($data['formNo']) ? $data['formNo'] : null;
            $sl_no            = isset($data['sl_no']) ? $data['sl_no'] : null;
            $father_nid       = isset($data['father_nid']) ? $data['father_nid'] : null;
            $mother_nid       = isset($data['mother_nid']) ? $data['mother_nid'] : null;
            $religion         = isset($data['religion']) ? $data['religion'] : null;
            $mobile           = isset($data['mobile']) ? $data['mobile'] : null;
            $voterNo          = isset($data['voterNo']) ? $data['voterNo'] : null;
            $voterArea        = isset($data['voterArea']) ? $data['voterArea'] : null;
            $education        = isset($data['education']) ? $data['education'] : null;
            $occupation       = isset($data['occupation']) ? $data['occupation'] : null;
            $status           = isset($data['status']) ? $data['status'] : null;
            $nameBangla       = isset($data['nameBangla']) ? $data['nameBangla'] : null;
            $nameEnglish      = isset($data['nameEnglish']) ? $data['nameEnglish'] : null;
            $dateOfBirth      = isset($data['dateOfBirth']) ? $data['dateOfBirth'] : null;
            $birthPlace       = isset($data['birthPlace']) ? $data['birthPlace'] : null;
            $fatherName       = isset($data['fatherName']) ? $data['fatherName'] : null;
            $motherName       = isset($data['motherName']) ? $data['motherName'] : null;
            $spouseName       = isset($data['spouseName']) ? $data['spouseName'] : null;
            $gender           = isset($data['gender']) ? $data['gender'] : null;
            $bloodGroup       = isset($data['bloodGroup']) ? $data['bloodGroup'] : null;
            $presentAddress   = isset($data['presentAddress']) ? $data['presentAddress'] : null;
            $permanentAddress = isset($data['permanentAddress']) ? $data['permanentAddress'] : null;
            $address          = isset($data['address']) ? $data['address'] : null;

            $voter = Voter::create([
                'nid'               => $nid,
                'pin'               => $pin,
                'user_id'           => Auth::id(),
                'formNo'            => $formNo,
                'sl_no'             => $sl_no,
                'father_nid'        => $father_nid,
                'mother_nid'        => $mother_nid,
                'religion'          => $religion,
                'mobile'            => $mobile,
                'voterNo'           => $voterNo,
                'voterArea'         => $voterArea,
                'education'         => $education,
                'occupation'        => $occupation,
                'status'            => $status,
                'nameBangla'        => $nameBangla,
                'nameEnglish'       => $nameEnglish,
                'dateOfBirth'       => $dateOfBirth,
                'birthPlace'        => $birthPlace,
                'fatherName'        => $fatherName,
                'motherName'        => $motherName,
                'spouseName'        => $spouseName,
                'gender'            => $gender,
                'bloodGroup'        => $bloodGroup,
                'presentAddress'    => $presentAddress,
                'permanentAddress'  => $permanentAddress,
                'address'           => $address,
                'image_photo'       => $photoPath,
                'image_sign'        => $signPath,
                'issueDate'         => date('Y-m-d', time()),
            ]);

            // একসাথে সব ভ্যরিয়েবল রেসপন্সে পাঠানো হচ্ছে
            $responseData = [
                'nid'              => $nid,
                'pin'              => $pin,
                'formNo'           => $formNo,
                'sl_no'            => $sl_no,
                'father_nid'       => $father_nid,
                'mother_nid'       => $mother_nid,
                'religion'         => $religion,
                'mobile'           => $mobile,
                'voterNo'          => $voterNo,
                'voterArea'        => $voterArea,
                'education'        => $education,
                'occupation'       => $occupation,
                'status'           => $status,
                'nameBangla'       => $nameBangla,
                'nameEnglish'      => $nameEnglish,
                'dateOfBirth'      => $dateOfBirth,
                'birthPlace'       => $birthPlace,
                'fatherName'       => $fatherName,
                'motherName'       => $motherName,
                'spouseName'       => $spouseName,
                'gender'           => $gender,
                'bloodGroup'       => $bloodGroup,
                'presentAddress'   => $presentAddress,
                'permanentAddress' => $permanentAddress,
                'address'          => $address,
                'image1'           => $image1,
                'image2'           => $image2,
                'issueDate'        => date('d-m-Y', time()),
                'voterid'          => $voter->id
            ];


            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'error' => 'API returned invalid JSON',
                    'body' => $data,
                ], 500);
            }

            return response()->json($responseData);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => $e->getMessage(), 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function nid_data_save_and_download(Request $request)
    {
        // Input validation
        $data = $request->validate([
            'voter_id'      => 'required|integer|exists:voters,id',
            'userPhotoLeft' => 'nullable|string',
            'userSignRight' => 'nullable|string',
            'nidID'         => 'nullable|string',
            'nidPin'        => 'nullable|string',
            'nameBangla'    => 'nullable|string',
            'nameEnglish'   => 'nullable|string',
            'fatherName'    => 'nullable|string',
            'dob'           => 'nullable|string',
            'birthPlace'    => 'nullable|string',
            'gender'        => 'nullable|string',
            'issueDate'     => 'nullable|string',
            'fullAddress'   => 'nullable|string',
        ]);

        // Get voter by voter_id (id)
        $voter = Voter::findOrFail($data['voter_id']);
        $update = [];

        // Handle images if uploaded as files from the form
        if ($request->hasFile('userPhotoLeft') && $request->file('userPhotoLeft')->isValid()) {
            $photoFile = $request->file('userPhotoLeft');
            $photoName = 'photo_' . now()->format('Ymd_His') . '_' . Str::random(6) . '.' . $photoFile->getClientOriginalExtension();
            $destinationPath = public_path('img_uploads');
            if (!\File::exists($destinationPath)) {
                \File::makeDirectory($destinationPath, 0755, true);
            }
            $photoFile->move($destinationPath, $photoName);
            $update['image_photo'] = 'img_uploads/' . $photoName;
        }

        if ($request->hasFile('userSignRight') && $request->file('userSignRight')->isValid()) {
            $signFile = $request->file('userSignRight');
            $signName = 'sign_' . now()->format('Ymd_His') . '_' . Str::random(6) . '.' . $signFile->getClientOriginalExtension();
            $destinationPath = public_path('img_uploads');
            if (!\File::exists($destinationPath)) {
                \File::makeDirectory($destinationPath, 0755, true);
            }
            $signFile->move($destinationPath, $signName);
            $update['image_sign'] = 'img_uploads/' . $signName;
        }


        // Always update these fields (sync)
        $syncFields = [
            'nid'            => $data['nidID'] ?? $voter->nid,
            'pin'            => $data['nidPin'] ?? $voter->pin,
            'nameBangla'     => $data['nameBangla'] ?? $voter->nameBangla,
            'nameEnglish'    => $data['nameEnglish'] ?? $voter->nameEnglish,
            'fatherName'     => $data['fatherName'] ?? $voter->fatherName,
            'dateOfBirth'    => $data['dob'] ?? $voter->dateOfBirth,
            'birthPlace'     => $data['birthPlace'] ?? $voter->birthPlace,
            'gender'         => $data['gender'] ?? $voter->gender,
            'issueDate'      => $data['issueDate'] ?? $voter->issueDate,
            'address'        => $data['fullAddress'] ?? $voter->address,
        ];
        $update = array_merge($update, $syncFields);

        // Update Voter
        $voter->update($update);

        // If any image is uploaded, respond with success
        if (isset($update['image_photo']) || isset($update['image_sign'])) {
            return response()->json(['success' => 'Voter synced and images uploaded', 'voter_id' => $voter->id]);
        }

        return redirect()->back()->with([
            'download_link' => route('user.downloadsnid', ['voter_id' => $voter->id]),
            'success' => 'NID তৈরী হয়েছে!'
        ]);

    }

    private function saveBase64ToPublic(?string $base64, string $prefix = 'img'): ?string
    {
        if (empty($base64)) return null;

        // যদি data:image/png;base64,... থাকে
        if (str_contains($base64, 'base64,')) {
            $base64 = explode('base64,', $base64, 2)[1];
        }

        $binary = base64_decode($base64);
        if ($binary === false) return null;

        $uploadPath = public_path('img_uploads');

        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        $fileName = $prefix.'_'.now()->format('Ymd_His').'_'.Str::random(6).'.png';
        $filePath = $uploadPath.'/'.$fileName;

        file_put_contents($filePath, $binary);

        // DB তে relative path রাখবো
        return 'img_uploads/'.$fileName;
    }


    public function savePhotoFromUrl($photoUrl, string $prefix = 'img')
    {

        if (!$photoUrl) {
            return response()->json([
                'status' => false,
                'message' => 'Photo URL is required'
            ], 400);
        }

        try {

            // Download image
            $response = Http::timeout(60)->get($photoUrl);

            if (!$response->successful()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Image download failed'
                ], 500);
            }

            // Ensure folder exists
            $uploadPath = public_path('img_uploads');

            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Generate unique file name
            $extension = pathinfo(parse_url($photoUrl, PHP_URL_PATH), PATHINFO_EXTENSION);
            $extension = $extension ? $extension : 'jpg';

            $fileName = $prefix.'_'.now()->format('Ymd_His').'_'.Str::random(20) . '.' . $extension;

            // Save file
            file_put_contents($uploadPath . '/' . $fileName, $response->body());


            // DB তে relative path রাখবো
            return 'img_uploads/'.$fileName;

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function auto_server_copy()
    {
        return view('user.auto_server');
    }

    public function auto_server_copy_api(Request $request)
    {

        $request->validate([
            'nid' => ['required', 'string', 'max:30'],
            'dob' => ['required', 'string', 'max:50'],
        ]);

        $inputNid = trim($request->nid);
        $inputDob = trim($request->dob);

        $rate = (float) Service::where('id', 23)->value('rate');

        $currentBalance = auth()->user()->balance();

        if ($rate <= 0) {
            if ($request->ajax()) {
                return response()->json([
                    'ok' => false,
                    'message' => 'সমস্যা দেখাচ্ছে। ',
                ], 422);
            }
        }

        if ($currentBalance < (float) $rate) {
            if ($request->ajax()) {
                return response()->json([
                    'ok' => false,
                    'message' => "পর্যাপ্ত ব্যালেন্স নেই। প্রয়োজন: {$rate} টাকা, আছে: {$currentBalance} টাকা",
                ], 422);
            }
        }

        $userId = Auth::id();

        $oldVoter = Voter::where('nid', $inputNid)
            ->whereDate('dateOfBirth', $inputDob)
            ->latest()
            ->first();

        $source = null;
        $payload = null;

        // Voter insert fields
        $nid = $inputNid;
        $pin = null;
        $formNo = $sl_no = $father_nid = $mother_nid = $mobile = $voterNo = $education = null;
        $religion = $voterArea = $occupation = $status = null;
        $nameBangla = $nameEnglish = $dateOfBirth = $birthPlace = $fatherName = $motherName = $spouseName = $gender = $bloodGroup = null;
        $presentAddress = $permanentAddress = $address = null;
        $photoPath = $signPath = null;

        try {
            if ($oldVoter) {
                $source = 'db_cache';

                $nid = $oldVoter->nid;
                $pin = $oldVoter->pin;

                $formNo = $oldVoter->formNo;
                $sl_no = $oldVoter->sl_no;
                $father_nid = $oldVoter->father_nid;
                $mother_nid = $oldVoter->mother_nid;
                $religion = $oldVoter->religion;
                $mobile = $oldVoter->mobile;
                $voterNo = $oldVoter->voterNo;
                $voterArea = $oldVoter->voterArea;
                $education = $oldVoter->education;
                $occupation = $oldVoter->occupation;
                $status = 'success';

                $nameBangla = $oldVoter->nameBangla;
                $nameEnglish = $oldVoter->nameEnglish;
                $dateOfBirth = $oldVoter->dateOfBirth;
                $birthPlace = $oldVoter->birthPlace;
                $fatherName = $oldVoter->fatherName;
                $motherName = $oldVoter->motherName;
                $spouseName = $oldVoter->spouseName;
                $gender = $oldVoter->gender;
                $bloodGroup = $oldVoter->bloodGroup;

                $presentAddress = $oldVoter->presentAddress;
                $permanentAddress = $oldVoter->permanentAddress;
                $address = $oldVoter->address;

                $photoPath = $oldVoter->image_photo;
                $signPath  = $oldVoter->image_sign;

            } else {
                $source = 'api';

                $apiUrl = config('services.nid_api.url');
                $token  = config('services.nid_api.token');

                if (!$apiUrl) {
                    if ($request->ajax()) {
                        return response()->json([
                            'ok' => false,
                            'message' => 'সমস্যা এডমিনের সাথে যোগাযোগ করুন ',
                        ], 422);
                    }
                }

                $http = Http::timeout(30)->acceptJson();

                $res = $http->get($apiUrl, [
                    'key' => $token,
                    'nid' => $inputNid,
                    'dob' => $inputDob,
                ]);

                if (!$res->successful()) {
                    if ($request->ajax()) {
                        return response()->json([
                            'ok' => false,
                            'message' => 'সমস্যা হয়েছে আবারো চেষ্টা করুন। api  ',
                        ], 422);
                    }
                }

                $payload = $res->json();

                $info = Arr::get($payload, 'data-Info', []);
                if (empty($info)) {
                    // error হলে (catch এর ভিতরে)
                    if ($request->ajax()) {
                        return response()->json([
                            'ok' => false,
                            'message' => 'সমস্যা হয়েছে, আবার চেষ্টা করুন। data-Info ',
                        ], 422);
                    }
                }

                SaveRowJsonData::create([
                    'row_data' => $res,
                    'nid'      => isset($payload['data-Info']['nationalId']) ? $payload['data-Info']['nationalId'] : null,
                    'pin'      => isset($payload['data-Info']['pin']) ? $payload['data-Info']['pin'] : null,
                    'dob'      => isset($payload['data-Info']['dateOfBirth']) ? $payload['data-Info']['dateOfBirth'] : null,
                ]);

                // ✅ mapping
                $nid = Arr::get($info, 'nationalId', $inputNid);
                $pin = Arr::get($info, 'pin');

                $religion   = Arr::get($info, 'religion');
                $occupation = Arr::get($info, 'occupation');
                $voterArea  = Arr::get($info, 'voterArea');
                $status     = 'success';

                $nameBangla  = Arr::get($info, 'nameBangla');
                $nameEnglish = Arr::get($info, 'nameEnglish');
                $dateOfBirth = Arr::get($info, 'dateOfBirth', $inputDob);
                $birthPlace  = Arr::get($info, 'birthPlace');
                $fatherName  = Arr::get($info, 'fatherName');
                $motherName  = Arr::get($info, 'motherName');
                $spouseName  = Arr::get($info, 'spouseName');
                $gender      = Arr::get($info, 'gender');
                $bloodGroup  = Arr::get($info, 'bloodGroup');

                $presentAddress   = Arr::get($info, 'preAddress.addressLine');
                $permanentAddress = Arr::get($info, 'perAddress.addressLine');
                $address = $presentAddress ?: $permanentAddress;

                $photoPath = Arr::get($info, 'photo');
                $signPath  = null;
            }

            DB::transaction(function () use (
                $userId, $rate,
                $nid, $pin, $formNo, $sl_no, $father_nid, $mother_nid, $religion, $mobile, $voterNo,
                $voterArea, $education, $occupation, $status, $nameBangla, $nameEnglish, $dateOfBirth,
                $birthPlace, $fatherName, $motherName, $spouseName, $gender, $bloodGroup,
                $presentAddress, $permanentAddress, $address, $photoPath, $signPath,
                $payload, $source
            ) {
                User::where('id', $userId)->lockForUpdate()->firstOrFail();

                $totalAdd = (float) UserBalanceAdd::where('user_id', $userId)->sum('amount');
                $totalCut = (float) UserBalanceCut::where('user_id', $userId)->sum('amount');
                $balance  = $totalAdd - $totalCut;

                if ($balance < (float) $rate) {
                    throw new \RuntimeException("পর্যাপ্ত ব্যালেন্স নেই। প্রয়োজন: {$rate} টাকা, আছে: {$balance} টাকা");
                }

                UserBalanceCut::create([
                    'user_id' => $userId,
                    'amount'  => $rate,
                    'source'  => 'nid_search',
                    'note'    => "NID Search | source={$source} | NID={$nid} | DOB={$dateOfBirth}",
                ]);

                Voter::create([
                    'nid'               => $nid,
                    'pin'               => $pin,
                    'user_id'           => $userId,
                    'file_types'        => 2,
                    'formNo'            => $formNo,
                    'sl_no'             => $sl_no,
                    'father_nid'        => $father_nid,
                    'mother_nid'        => $mother_nid,
                    'religion'          => $religion,
                    'mobile'            => $mobile,
                    'voterNo'           => $voterNo,
                    'voterArea'         => $voterArea,
                    'education'         => $education,
                    'occupation'        => $occupation,
                    'status'            => $status,
                    'nameBangla'        => $nameBangla,
                    'nameEnglish'       => $nameEnglish,
                    'dateOfBirth'       => $dateOfBirth,
                    'birthPlace'        => $birthPlace,
                    'fatherName'        => $fatherName,
                    'motherName'        => $motherName,
                    'spouseName'        => $spouseName,
                    'gender'            => $gender,
                    'bloodGroup'        => $bloodGroup,
                    'presentAddress'    => $presentAddress,
                    'permanentAddress'  => $permanentAddress,
                    'address'           => $address,
                    'image_photo'       => $photoPath,
                    'image_sign'        => $signPath,
                    'issueDate'         => now()->toDateString(),
                ]);

            });

            if ($request->ajax()) {
                return response()->json([
                    'ok' => true,
                    'message' => 'ডাটা সেভ হয়েছে ✅',
                ]);
            }

        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function auto_server_copy_data_get(Request $request)
    {
        // Blade থেকে date আসবে (optional)
        $date = $request->date;

        $query = Voter::where('user_id', auth()->id())
            ->where('file_types', '!=', 1);

        // ✅ যদি date পাঠায়
        if (!empty($date)) {
            $query->whereDate('created_at', $date);
        } else {
            // ❗ date না পাঠালে আজকের ডাটা
            $query->whereDate('created_at', now()->toDateString());
        }

        $all_server_copy = $query->latest()->get();


        return response()->json([
            'ok' => true,
            'data' => $all_server_copy
        ]);
    }

    public function servercopyToken()
    {
        $key = config('services.servercopy.key'); // নিচের config থেকে নিবে

        try {
            $res = Http::withoutVerifying()
                    ->retry(3, 500)
                    ->timeout(30)
                    ->get('https://api.nid-servercopy.com/?key=d7003dabbb99f9fd4a0570e8e8e26f0a');

            if (!$res->successful()) {
                return response()->json([
                    'ok' => false,
                    'message' => 'API failed: ' . $res->status(),
                ], 500);
            }

            $data = $res->json(); // decoded array

            // expected: { status: 1, balance: 89, ... }
            if (($data['status'] ?? 0) != 1) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Invalid response from API',
                    'data' => $data,
                ], 422);
            }

            return response()->json([
                'ok' => true,
                'balance' => $data['balance'] ?? 0,
                'purchase_time' => $data['purchase_time'] ?? null,
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


}

