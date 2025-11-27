<?php

namespace Panacea\Http\Controllers;

use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Panacea\Code;
use Panacea\Company;
use Panacea\Http\Requests;
use Panacea\Medicine;
use Panacea\Order;
use Panacea\User;
use Panacea\Log;
use Panacea\Template;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Panacea\InjectableUser;
use SoapClient;
use Illuminate\Support\Facades\Http;

class CodeGenerationPanelNewController extends Controller
{
    

    public function __construct()
    {
        //session()->put('progress', 0);
    }


    public function showLogin()
    {

        $data = [];
        $data['company'] = 'Code Generation Panel';
        $data['page_title'] = 'Admin Login';
        if (Session::has('id')) {
            $user = Sentinel::findById(Session::get('id'));
            $company = $this->getUserCompany($user);
            if ($company == "panacea") {
                return redirect()->to('choosemenu');
            } else {
                return redirect()->to('code/generate');
            }
        } else {
            return view('generationPanel.login', $data);
        }
    }


    public function processLogin(Request $request)
    {
        $request->phone_number = str_replace('+', '', $request->phone_number);
        if (strlen($request->phone_number) == 11) {
            $request->phone_number = '88' . $request->phone_number;
        }
        if ($user = User::where('phone_number', $request->phone_number)->first()) {
            $auth = Sentinel::findById($user->id);
            if ($auth->hasAccess('company')) {
                Activation::removeExpired();
                $activation = Activation::create($user);
                $codeActive = substr($activation->code, 0, 4);
                $codeActive = strtoupper($codeActive);
                // $codeActive = '1234';
                // $data['msg'] = $codeActive;
                $data['message'] = $codeActive . ' - Is Your Login Code For Panacea Live.';
                // $msg = urlencode( $data['message'] );
               
                $this->sendSms($auth->phone_number, urlencode( $data['message'] ));

                return $user->id;
            }else {
                session()->forget('id');
                Sentinel::logout();
                //print_r('5');
                return 0;
            }
        } else {
            session()->forget('id');
            return 0;
        }
    }


    public function showVerify()
    {
        if (!session()->get('id')) {
            return redirect()->to('/');
        }
        $data = [];
        $data['page_title'] = 'Admin Login Verify';
        return view('generationPanel.verify', $data);
    }


    public function processVerify(Request $request)
    {
        $user = Sentinel::findById($request->input('id'));
        // if (Activation::complete($user,'==', '1234')) {  //remove cheems bypass change
        if (!Activation::complete($user, strtoupper($request->verification_code))) {
            session()->flash('message', 'Invalid verification code.');
            return 0;
        }
        Sentinel::login($user);
        if (!session()->has('id')) {
            session(['id' => $user->id]);
            session()->put('timestamp', time());
        }
        $company = $this->getUserCompany($user);
        if ($company == "panacea") {
            return 1;
        } elseif ($company == "invalid") return 0;
        $data['company'] = Company::where('display_name', $company)->first();
        Log::create([
            'company_id' => $data['company']['id'],
            'company_admin_id' => Sentinel::getUser()->id,
            'action' => 1
        ]);
        $data['page'] = 'order_page';
        $data['medicines'] = Medicine::select('id', 'medicine_name')
            ->where('company_id', $data['company']['id'])
            ->groupBy('medicine_name')
            ->get();
        return 2;
    }


    public function chooseMenu()
    {   
        $data['codes']=Code::where('status','=','0')->count();
        $data['page'] = 'menu_page';
        $data['company'] = Company::orderBy('created_at', 'asc')->get();
        return view('generationPanel.chooseCompany', $data);
    }


    public function chooseCompany($company)
    {
        $data['company'] = Company::where('display_name', $company)->first();
        $data['page'] = 'order_page';
        $data['medicines'] = Medicine::select('id', 'medicine_name')
            ->where('company_id', $data['company']['id'])
            ->groupBy('medicine_name')
            ->get(); 
        Session::put('CompanyName', $company);
        return view('generationPanel.order', $data);
    }


    public function resendLogin(Request $request)
    {
        $auth = Sentinel::findById($request->id);
        $user = User::where('id', $request->id)->first();
        if ($auth->hasAccess('company')) {
            Activation::removeExpired();
            $activation = Activation::create($user);
            $codeActive = substr($activation->code, 0, 4);
            $codeActive = strtoupper($codeActive);
            $data['msg'] = $codeActive;
            $data['message'] = $codeActive . '. Your login code';
            Mail::send('emails.verify', $data, function ($message) use ($auth) {
                $message->to($auth->email);
                $message->subject("[Panacea] Login code!");
            });
            $this->sendSms($auth->phone_number, $data['message']);
            session()->flash('id', $user->id);
            echo '<div class="alert alert-success"><p style="text-align: center">A code is resent to your email and phone.
            Please take a few moment to reach you and then insert your code.</p></div>';
        }
    }

    public function showForm()
    {
        if ($user = Sentinel::check()) {
            $company = $this->getUserCompany($user);
            if ($company == "panacea") {
                $company = Session::get('CompanyName');
            } elseif ($company == "invalid") return 'you are not authenticated';
        }
        if (Session::has('orderData')) {
            $data = Session::get('orderData');
            return view('generationPanel.order_back', $data);
        } else {
            $data = [];
            $data['page'] = 'order_page';
            $data['company_name'] = $company;
            $data['company'] = Company::where('display_name', $company)->first();
            //dd($data['company']);
            $data['medicines'] = Medicine::select('id', 'medicine_name')
                ->where('company_id', $data['company']['id'])
                ->groupBy('medicine_name')
                ->orderBy('medicine_name')
                ->get();
            return view('generationPanel.order', $data);
        }
    }


    public function orderCode(Request $request)
    {
        $this->validate($request, [
            'company_id' => 'required',
            'medicine_dosage' => 'required',
            'file' => 'required',
            'quantity' => 'required|numeric',
        ]);

        $data['page'] = 'order_page';
        $data['medicine'] = Medicine::select('medicine_name', 'medicine_type', 'medicine_dosage')->where('id', $request->medicine_dosage)->first();
        //$data['company'] = Company::where('id',$request->company_id)->first();
        //$data['company_name'] = $data['company']['display_name'];
        $data['medicines'] = Medicine::select('id', 'medicine_name')->where('company_id', $request->company_id)->groupBy('medicine_name')->get();
        $data['medicine_type'] = Medicine::select('id', 'medicine_type')->where('medicine_name', $request->medicine_name)->groupBy('medicine_type')->get();
        $data['medicine_dosage'] = Medicine::select('id', 'medicine_dosage')->where('medicine_name', $request->medicine_name)->where('medicine_type', $request->med_type)->get();
        $data['med_dosage_id'] = Medicine::select('medicine_dosage')->where('id', $request->medicine_dosage)->first();
        $data['template'] = Template::select('template_message')->where('med_id', $request->medicine_dosage)->where('flag', 'active')->first();
        $data['generator'] = Session::get('id');
        $data['confirm'] = ([
            'medicine_name' => $request->medicine_name,
            'medicine_type' => $request->med_type,
            'medicine_dosage' => $data['med_dosage_id']['medicine_dosage'],
            'medicine_id' => $request->medicine_dosage,
            'company_id' => $request->company_id,
            'mfg_date' => $request->mfg_date,
            'expiry_date' => $request->expiry_date,
            'quantity' => $request->quantity,
            'batch_number' => $request->batch_number,
            'file' => $request->file,
            'pref' => $request->prefix
        ]);
        if (Session::has('orderData')) {
            Session::put('orderData', $data, 600);
        } else {
            Session::put('orderData', $data, 600);
        }
        //Change the template message's PBN/REN part with selected option during generation for proper preview message
        $find = "PBN/REN";
        $input = $request->prefix;
        //find if template was set and has PBN/REN in it
        if(strpos($data['template']['template_message'],$find)!==false)
        {
            if($input==="2777")
            {
                //if prefix from radio button input is 2777 replace the template preview's PBN/REN with PBN
                $data['template']['template_message'] = str_replace($find, "PBN", $data['template']['template_message']);
            }
            else if($input === 'qr'){
                $data['template']['template_message'] = 'Preparing data for QR CODES';
            }
            else
            {
                //if prefix from radio button input is not 2777 replace the template preview's PBN/REN with REN
                $data['template']['template_message'] = str_replace($find, "REN", $data['template']['template_message']);
            }
        }
        return view('generationPanel.confirm', $data);
    }

  
    public function orderBackForConfirm(Request $request)
    {
        $data['page'] = 'order_page';
        $data['medicines'] = Medicine::select('id', 'medicine_name')->where('company_id', $request->company_id)->groupBy('medicine_name')->get();
        $data['medicine_type'] = Medicine::select('id', 'medicine_type')->where('medicine_name', $request->medicine_name)->groupBy('medicine_type')->get();
        $data['medicine_dosage'] = Medicine::select('id', 'medicine_dosage')->where('medicine_name', $request->medicine_name)->where('medicine_type', $request->medicine_type)->get();
        $data['confirm'] = ([
            'medicine_name' => $request->medicine_name,
            'medicine_type' => $request->medicine_type,
            'medicine_dosage' => $request->medicine_dosage,
            'medicine_id' => $request->medicine_dosage_id,
            'company_id' => $request->company_id,
            'mfg_date' => $request->mfg_date,
            'expiry_date' => $request->expiry_date,
            'quantity' => $request->quantity,
            'batch_number' => $request->batch_number,
            'file' => $request->file,
            'pref' => $request->prefix
        ]);
        return view('generationPanel.order_back', $data);
    }


    public function ConfrimArman(Request $request) {
        // return $request->all();

        Session::forget('orderData');

        ini_set('memory_limit', '512M');

        $order_id = Order::select('id')->orderBy('id', 'desc')->first()->id;
        $order_id += 1;
        $filename = $order_id . '_' . $request->file . '.csv';
        $request->mfg_date = $request->mfg_date . "-28";
        $request->expiry_date = $request->expiry_date . "-28";
        $order = Order::create([
            'company_id' => $request->company_id,
            'medicine_id' => $request->medicine_dosage_id,
            'mfg_date' => $request->mfg_date,
            'expiry_date' => $request->expiry_date,
            'batch_number' => $request->batch_number,
            'quantity' => $request->quantity,
            'file' => $filename,
        ]);
        Log::create([
            'company_id' => $request->company_id,
            'company_admin_id' => Sentinel::getUser()->id,
            'action' => 2
        ]);
        
        $collection = Code::select('code')
            ->where('status', 0)
            ->where(DB::raw('CHAR_LENGTH(code)'), '=', 7)
            ->where('code','not like','%0%')
            ->orderBy('id','desc')
            ->take($request->quantity);

        $template = Template::select('template_message')->where('med_id', $request->medicine_dosage_id)->where('flag', 'active')->first();
        $codesDir = public_path('codes');
        if (!is_dir($codesDir)) {
            @mkdir($codesDir, 0775, true);
        }
        $handle = fopen($codesDir . '/' . $filename, 'w+');

        if ($request->quantity > 500) $chunk = 500;
        else $chunk = $request->quantity;

        if (Session::get('id') == "1929" and $request->medicine_dosage_id == "3") 
        {
            foreach ($collection->get()->chunk($chunk) as $codes) {
           
                foreach ($codes as $code) {
                    fputcsv($handle, [
                        "SMS (REN " . $code->code . ")",
                    ]);
                }
            }
        }

        elseif ($template['template_message'] == "") 
        {
            foreach ($collection->get()->chunk($chunk) as $codes) {
      
                foreach ($codes as $code) {
                    fputcsv($handle, [
                        'REN ' . $code->code,
                        // 'SMS (REN ' . $code->code . ') to 26969 to VERIFY',
                    ]);
                }
            }
        }

        elseif ($request->prefix == "6spcae") 
        {
            // return 'space';
            foreach ($collection->get()->chunk($chunk) as $codes) {
      
                foreach ($codes as $code) {
                    fputcsv($handle, [
                        // 'SMS (REN ' . $code->code . ') to 26969 to VERIFY',
                        "REN \x20\x20\x20\x20 " . $code->code
                    ]);
                }
            }
        }
        else
        {
            // Split with PBN/REN MCKRTWS. add prefix and suffix
            $new_message = explode("PBN/REN MCKRTWS", $template['template_message']);
            foreach ($collection->get()->chunk($chunk) as $codes) {
                foreach ($codes as $code) {
                    fputcsv($handle, [
                        $new_message[0] . "REN " . $code->code . $new_message[1],
                        // $new_message[0] . "REN \x20\x20\x20\x20 " . $code->code . $new_message[1],
                    ]);
                }
            }
        }

        fclose($handle);
        $collection->update(['status' => $order->id]);
        Order::where('id', $order->id)->update(['status' => 'finished']);
        return redirect('/order');
    }

 
    public function indexOrder()
    {
        if ($user = Sentinel::check()) {
            $company = $this->getUserCompany($user);
            if ($company == "panacea") {
                $company = Session::get('CompanyName');
            } elseif ($company == "invalid") return 'you are not authenticated';
        }
        $data = [];
        $data['page'] = 'print_log_page';
        $data['company_name'] = $company;
        $data['company'] = Company::where('display_name', $company)->first();
        $data['medicine'] = Medicine::where('company_id', $data['company']->id)->groupBy('medicine_name')->get();
        $data['order'] = Order::where('company_id', $data['company']->id)
            ->orderBy('created_at', 'desc')
            ->offset(0)
            ->limit(150)
            // ->limit(50)
            ->get();
        return view('generationPanel.order.index', $data);
    }


    public function logout()
    {
        if ($user = Sentinel::check()) {
            $company = $this->getUserCompany($user);
            if ($company == "panacea") {
                //return view();
                $company = Session::get('CompanyName');
            } elseif ($company == "invalid") return 'you are not authenticated';
        }
        $data['company'] = Company::where('display_name', $company)->first();
        Log::create([
            'company_id' => $data['company']['id'],
            'company_admin_id' => Sentinel::getUser()->id,
            'action' => 3
        ]);
        Session::flush(); // removes all session data
        Sentinel::logout();
        return redirect()->to('/');
    }


    public function showLog()
    {
        if ($user = Sentinel::check()) {
            $company = $this->getUserCompany($user);
            if ($company == "panacea") {
                //return view();
                $company = Session::get('CompanyName');
            } elseif ($company == "invalid") return 'you are not authenticated';
        }
        $data = [];
        $data['page'] = 'log_page';
        $data['company_name'] = $company;
        $data['company'] = Company::where('display_name', $company)->first();
        $query = "SELECT company_id, name, action, date(code_generation_log.created_at) AS log_date, time(code_generation_log.created_at) AS log_time FROM code_generation_log, users
        WHERE company_id = " . $data['company']->id . " AND company_admin_id = users.id ORDER BY code_generation_log.created_at DESC";
        $data['log'] = DB::select($query);
        //$data['userNames'] = DB::table('company_user')->select('full_name')->get();
        $i=0;
        foreach($data['log'] as $log)
        {
            $name[$i] = $log->name;
            $i++;
        }
        $data['userNames'] = array_unique($name);
        $data['log'] = array_slice($data['log'], 0, 15);
        return view('generationPanel.log', $data);
    }

    public function showTemplate()
    {
        if ($user = Sentinel::check()) {
            $company = $this->getUserCompany($user);
            if ($company == "panacea") {
                $company = Session::get('CompanyName');
            } elseif ($company == "invalid") return 'you are not authenticated';
        }
        $data = [];
        $data['page'] = 'template_page';
        $data['company_name'] = $company;
        $data['company'] = Company::where('display_name', $company)->first();
        $data['company_admin_id'] = Sentinel::getUser()->id;
        $data['medicine_names'] = Medicine::select('id', 'medicine_name')
            ->where('company_id', $data['company']['id'])
            ->groupBy('medicine_name')
            ->orderBy('medicine_name')
            ->get();

        $query = "SELECT templates.id, medicine.company_id, company_admin_id, med_id, template_message, medicine_name, medicine_type, medicine_dosage FROM templates, users, medicine WHERE medicine.company_id = " . $data['company']->id . " AND med_id=medicine.id AND company_admin_id = users.id AND templates.flag=\"active\" ORDER BY templates.updated_at DESC";
        $data['template_log'] = DB::select($query);
        return view('generationPanel.template', $data);
    }

 
    public function addTemplate(Request $request)
    {
        //if data exists from previous template update confirmation request delete it
        if(session()->has('template'))
        {
            session()->forget('template');
        }
        if (Template::where('flag', '=', 'active')->where('med_id', '=', $request->medicine_dosage)->count() > 0) {
        
            // if medicine already exists AND flag is active, update template
            $temp = Template::select('template_message')->where('flag', '=', 'active')->where('med_id', $request->medicine_dosage)->get();
            $temp[0]['med_id'] = $request->medicine_dosage;
            $temp[0]['prefix'] = $request->prefix;
            $temp[0]['suffix'] = $request->suffix;

            session(['template' => $temp[0]]);

            return redirect()->back()->with(['templateError'=>'A template already exists for this medicine.
            Are you sure you want to replace the template- "'.
            $temp[0]->template_message.
            '" with "'.
            $request->prefix . 'PBN/REN MCKRTWS' . $request->suffix.'"?']);

        } else {
            // create new template
            Template::create([
                'company_id' => $request->company_id,
                'company_admin_id' => $request->company_admin_id,
                'med_id' => $request->medicine_dosage,
                'template_message' => $request->prefix . 'PBN/REN MCKRTWS' . $request->suffix,
                'flag' => 'active'
            ]);
        }
        return redirect('/templates')->with(['templateSuccess'=>'Template has been added.']);
    }


    public function confirmAddTemplate()
    {
        if(session()->has('template'))
        {
            $template = session('template');
            session()->forget('template');
            DB::table('templates')->where('med_id', $template->med_id)->update(
                ['template_message' => $template->prefix . 'PBN/REN MCKRTWS' . $template->suffix,
                    'updated_at' => DB::raw('NOW()')
                ]);
            return redirect('/templates')->with(['templateSuccess'=>'Template has been updated.']);
        }
        else
        {
            return redirect('/templates');
        }
    }

    public function deleteTemplate($id)
    {
        if (isset($id)) {
            $templateId = Template::find($id);
            if ($templateId) {
                DB::table('templates')->where('id', $id)->update(
                    ['flag' => 'inactive',
                        'updated_at' => DB::raw('NOW()')
                    ]);
                return redirect('/templates')->with(['templateSuccess'=>'Template has been deleted.']);
            }
        }
    }

    public function showMedicines(Request $request)
    {
        $medicines = Medicine::select('id', 'medicine_name')->where('company_id', $request->id)->groupBy('medicine_name')->get();
        foreach ($medicines as $medicine) {
            echo '<label class="btn btn-primary"> <input type="radio" value="' . $medicine->id . '" name="medicine_name" required> ' . $medicine->medicine_name . ' </label> ';
        }
    }

    public function showMedicineType(Request $request)
    {
        $medicines = Medicine::select('id', 'medicine_type')->where('medicine_name', $request->id)->groupBy('medicine_type')->get();
        foreach ($medicines as $medicine) {
            echo '<label class="btn btn-primary"> <input type="radio" value="' . $medicine->medicine_type . '" name="med_type" required> ' . $medicine->medicine_type . ' </label> ';
        }
    }

    public function showMedicineDosage(Request $request)
    {
        $medicines = Medicine::select('id', 'medicine_dosage')->where('medicine_name', $request->name)
            ->where('medicine_type', $request->type)->get();

        foreach ($medicines as $medicine) {
            echo '<label class="btn btn-primary"> <input type="radio" value="' . $medicine->id . '" name="medicine_dosage" required> ' . $medicine->medicine_dosage . ' </label> ';
        }
    }

    public function showMoreData(Request $request)
    {
        $selected = $request->selected;
        if (is_array($selected)) {
            $errorSelected = array_filter($selected);
            $strSelected = "('" . implode("','", $selected) . "')";
        }

        $query = "SELECT print_order.id, medicine_name, medicine_dosage, medicine_type, batch_number, quantity, file, print_order.created_at FROM print_order, medicine WHERE print_order.medicine_id = medicine.id AND medicine_id != 4 AND medicine_id != 8";
        if ($request->batch != '') {
            $query .= " AND batch_number like '" . $request->batch . "%' ";
            $request->offset = 0;
        };
        if (!empty($errorSelected)) {
            $query .= " AND medicine_name in " . $strSelected . " ";
            $request->offset = 0;
        }
        if ($request->dateStart != '' && $request->dateEnd != '') {
            $query .= " AND date(print_order.created_at) >= '" . $request->dateStart . "' AND date(print_order.created_at) <= '"
                . $request->dateEnd . "' ";
            $request->offset = 0;
        }
        $query .= " order by print_order.created_at desc";
        if (empty($errorSelected) && $request->batch == '') {
            $query .= " limit " . $request->offset . ",15";
        }

        $orderList = DB::select($query);
        /*
                $orderList = Order::where('company_id', $request->companyId)
                    ->where('medicine_id','not like','4')
                    ->orderBy('created_at', 'desc')
                    ->offset($request->offset)
                    ->limit(15)
                    ->get();
        */
        foreach ($orderList as $order) {
            echo "<tr><td> " . $order->medicine_name . " " . $order->medicine_type . " " . $order->medicine_dosage . " </td>
                        <td>" . $order->batch_number . "</td>
                        <td>" . $order->quantity . "</td>
                        <td><a href=\"codes/" . $order->file . "\">Download</a></td>
                        <td> " . $order->created_at . " </td></tr>";
        }
    }
  
    public function showMoreLog(Request $request)
    {
        $data = [];
        $datamodel['company'] = Company::where('id', $request->companyId)->first();
        $query = "SELECT company_id, name, action, date(code_generation_log.created_at) AS log_date, time(code_generation_log.created_at) AS log_time FROM code_generation_log, users
        WHERE company_id = " . $datamodel['company']->id . " AND company_admin_id = users.id ORDER BY code_generation_log.created_at DESC
        LIMIT " . $request->offset . ",15";

        $logs = DB::select($query);

        foreach ($logs as $log) {
            if ($log->action == 1) $action = 'Login to system';
            elseif ($log->action == 2) $action = 'Generated Code';
            else $action = 'Logged out';
            echo "<tr><td> " . $log->name . " </td>
            <td>" . $action . "</td>
            <td>" . $log->log_date . "</td>
            <td> " . $log->log_time . "</td></tr>";
        }
    }

    public function searchActivityLog(Request $request)
    {
        $data = [];
        $datamodel['company'] = Company::where('id', $request->companyId)->first();
        $query = "SELECT company_id, name, action, date(code_generation_log.created_at) AS log_date, time(code_generation_log.created_at) AS log_time FROM code_generation_log, users
        WHERE company_id = " . $datamodel['company']->id . " AND company_admin_id = users.id";

        $selected = $request->selected;
        if (is_array($selected)) {
            $errorSelected = array_filter($selected);
            $strSelected = "('" . implode("','", $selected) . "')";
        }

        if (!empty($errorSelected)) {
            $query .= " AND name in " . $strSelected;
            $request->offset = 0;
        }

        if($request->nameInput!=''){
            $query .= " AND name LIKE '%". $request->nameInput ."%'";
            $request->offset = 0;
        }

        $query .= " order by code_generation_log.created_at DESC";

        // if (empty($errorSelected)) {
        //     $query .= " limit " . $request->offset . ",15";
        // }

        //\Log::info($query);
        $logs = DB::select($query);

        foreach ($logs as $log) {
            if ($log->action == 1) $action = 'Login to system';
            elseif ($log->action == 2) $action = 'Generated Code';
            else $action = 'Logged out';
            echo "<tr><td> " . $log->name . " </td>
            <td>" . $action . "</td>
            <td>" . $log->log_date . "</td>
            <td> " . $log->log_time . "</td></tr>";
        }
    }


    protected function sendSms($phone_number, $message, $mask = 'Panacea')
    {

        // $apiUrl = "https://api.mobireach.com.bd/SendTextMessage?Username=panacealive&Password=Panacearocks@2022&From=MAXPRO&To=".$auth->phone_number."&Message=".  urlencode( $data['message'] );
       
        $apiUrl = "https://api.mobireach.com.bd/SendTextMessage?Username=panacealive&Password=Panacearocks@2022&From=MAXPRO&To=".$phone_number."&Message=".$message;
        $curl = curl_init($apiUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($curl, CURLOPT_HTTPGET, true); 
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curlErr = curl_error($curl);
        curl_close($curl);

        // Log request + response metadata for diagnostics
        \Log::info('[SMS] Request URL: '.$apiUrl);
        if ($curlErr) {
            \Log::error('[SMS] cURL error: '.$curlErr);
        }
        \Log::info('[SMS] HTTP status: '.$httpCode);
        if (is_string($response)) {
            \Log::info('[SMS] Response: '.substr($response, 0, 500));
        }

        // return $response;

        // try {
        //     $soapClient = new SoapClient("https://user.mobireach.com.bd/index.php?r=sms/service");
        //     $value = $soapClient->SendTextMessage('panacealive','Panacearocks@2022','MAXPRO',$phone_number,$message);
        //     dd($value);
        //     return true;
        //     if($value->ErrorCode==1501)
        //     { 
        //         \Log::info("Robi needs to be recharged");
        //     }
        // } catch (Exception $e) {
        //     echo $e;
        // }
    }
 
    protected function sendSmsRobiBangla($phone_number, $message, $mask = 'Panacea')
    {
        $apiUrl = "https://api.mobireach.com.bd/SendTextMessage?Username=panacealive&Password=Panacearocks@2022&From=MAXPRO&To=".$phone_number."&Message=".$message;
        $curl = curl_init($apiUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($curl, CURLOPT_HTTPGET, true); 
        $response = curl_exec($curl);
        \Log::info($apiUrl);
        // try {       
        //     $soapClient = new SoapClient("https://user.mobireach.com.bd/index.php?r=sms/service");
        //     $value = $soapClient->SendTextMessage('panacealive','Panacearocks@2022','MAXPRO',$phone_number,$message);
        //     //var_dump($value);
        //     if($value->ErrorCode==1501)
        //     {
        //         Mail::raw('Robi needs to be recharged', function ($message) {
        //             $message->to("souvik@panacea.live");
        //             $message->subject("[Panacea] robi Recharge Alert!");
        //         });
        //     }
        // } catch (Exception $e) {
        //     echo $e;
        // }
    } 

    public function getUserCompany($user)
    {
        $user_email = User::select('email')->where('id', $user->id)->first();
        $ijectable_user=InjectableUser::select('id')->where('user_id',$user->id)->first();
        //dd($ijectable_user);
        $explodedEmail = explode('@', $user_email->email);
        $domain = array_pop($explodedEmail);
        if ($domain == 'panacea.live' || $domain == 'panacealive.xyz')
        {
            return 'panacea';
        } 
        elseif ($domain == 'kumarika.com')
        {
            return 'kumarika';
        } 
        // arif.ullah@renata-ltd.com 8804
        elseif ($domain == 'renata-ltd.com')
        {
            return 'renata';
        }
        // arif.ullah@renata-ltd.com 8804
        // elseif ($domain == 'renata_injectable.com')
        // {
        //     return 'renata_injectable';
        // }  
        
        elseif ($domain == 'gmail.com' || $domain == 'hotmail.com' || $domain == 'yahoo.com')
        {
            return 'invalid';
        }
         else
        {
            // if(!empty($ijectable_user))
            if($domain == 'renata_injectable.com')
            {
                return 'renata_injectable';
            }
            $company_name = Company::select('display_name')->where('contact_email','like', '%' . $domain)->first();
            return $company_name->display_name;
        }
    }

}