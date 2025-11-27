<?php

namespace Panacea\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Panacea\Http\Requests;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Cartalyst\Sentinel\Laravel\Facades\Reminder;
use Panacea\Http\Controllers\Controller;
use Panacea\Campaign;
use Panacea\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Storage;
use Panacea\InjectableUser;

class ConsumerEngagementController extends Controller
{


    public function __construct()
    {
        $this->page_title = 'Panacea. The Future Is Original.';
        $this->body_id = '';
    }

    function showLanding()
    {
        $data = [];
        $data['page_title'] = $this->page_title;
        $data['body_id'] = $this->body_id;

        if (Session::get('campaign_user_session')) {
            return view('consumerEngagement.consumerEngagement_view');
        } else {
            return view('consumerEngagement.login', $data);
        }
    }

//    function showLanding()
//    {
//        $data = [];
//        $data['page_title'] = $this->page_title;
//        $data['body_id'] = $this->body_id;
//        return view('consumerEngagement.consumerEngagement_view');
//
//    }


    /**
     * View page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    function index()
    {
        if (Session::get('campaign_user_session')) {
            return view('consumerEngagement.consumerEngagement_view');
        } else {
            $data = [];
            $data['page_title'] = $this->page_title;
            $data['body_id'] = $this->body_id;

            return redirect('/');
        }
    }

    /**
     * View page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    function campaignReport()
    {
        $data = [];
        $data['page_title'] = $this->page_title;
        $data['body_id'] = $this->body_id;

        return view('consumerEngagement.campaign_report');
    }


//    /**
//     * View page
//     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
//     */
//    function registration_page()
//    {
//        $data = [];
//        $data['page_title'] = $this->page_title;
//        $data['body_id'] = $this->body_id;
//
//        return view('consumerEngagement.registration');
//    }


    /**
     * Process logout
     * @return \Illuminate\Http\RedirectResponse
     */
    function processLogout()
    {
        Session::flush(); // removes all session data
        return redirect()->route('campaign_view');
    }

    protected function sendSms($phone_number, $message, $mask = 'Panacea')
    {

        try {
            $soapClient = new SoapClient("https://api2.onnorokomSMS.com/sendSMS.asmx?wsdl");
            $paramArray = array('userName' => "01675430523",
                'userPassword' => "tapos99", 'mobileNumber' => $phone_number,
                'smsText' => $message, 'type' => "TEXT",
                'maskName' => "Panacea", 'campaignName' => '',);

            $value = $soapClient->__call("OneToOne", array($paramArray));
            //var_dump($value);
            if (substr(get_object_vars($value)["OneToOneResult"], 0, 4) == "1903") {
                Mail::raw('Onnorokom needs to be recharged', function ($message) {
                    $message->to("souvik@panacea.live");
                    $message->subject("[Panacea] Onnorokom Recharge Alert!");
                });
            }
        } catch (Exception $e) {
            echo $e;
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function confirmCampaign(Request $request)
    {

        $data['page'] = 'campaign confirm page';
        $data['campaign'] = ([
            'campaign_name' => $request->fileToUpload,
            'language_type' => $request->language_type,
            'operator' => $request->operator,
            'sms' => $request->sms,
            'target' => $request->target,
            'time' => $request->time,
            'filename' => $request->fileToUpload,
        ]);


        $target_dir = "consumerEngagement/campaign_files/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $csvFileType = pathinfo($target_file, PATHINFO_EXTENSION);

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists. Please use another name";
            $uploadOk = 0;
        }
        // Limit file size to 10 MB
        if ($_FILES["fileToUpload"]["size"] > 10000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        // Allow only CSVs
        if ($csvFileType != "csv") {
            echo "Sorry, only CSV files are allowed.";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }

        $uploadedCSV = file($target_file, FILE_SKIP_EMPTY_LINES);
        foreach ($uploadedCSV as $phonenumbers) {
            //\Log::info($phonenumbers);
        }
        $fp = file($target_file);
        // \Log::info(count($fp));
        $target = $request->target;
        if ($target == "All") {
            $target = "Maxpro, Rolac";
        }
        //        Get company_id, company_admin_id after auth. Get case somehow.
//        Campaign::create([
//            'company_id' => $request->company_id,
//            'company_admin_id' => $request->company_admin_id,
//            'amount' => count($fp),
//            'language' => $request->language_type,
//            'campaign_name' => substr($request->fileToUpload,0, -4),
//            'filename' => $request->fileToUpload,
//            'product' => $request->target,
//            'message' => $request->sms,
//            'operator' => $request->operator,
//            'execution time' => $request->time,
//            'status' => 'ongoing',
//            'case' => 1,
//        ]);


        return view('consumerEngagement.confirm_campaign', $data);

    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function saveCampaign(Request $request)
    {

        $data['page'] = 'save campaign';
//        $data['campaign'] = ([
//            'campaign_name' => $request->campaign_name,
//            'language_type' => $request->language_type,
//            'operator' => $request->operator,
//            'sms' => $request->sms,
//            'target' => $request->target,
//            'time' => $request->time,
//        ]);


        return view('consumerEngagement.campaign_report', $data);

    }

    /**
     * Delete template: just changes flag to make it inactive
     */
    public function finishCampaign($id)
    {
        if (isset($id)) {
            $templateId = Campaign::find($id);
            if ($templateId) {
                DB::table('campaign')->where('id', $id)->update(
                    ['status' => 'finished',
                        'updated_at' => DB::raw('NOW()')
                    ]);
                return redirect('/campaign_report');
            }
        }
    }


    /**
     * Login page
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);


        $check = 0;
        $user = DB::table('users')->where(
            array('email' => $request->email))->first();
        if ($user) {
            $auth = Sentinel::findById($user->id);
            if ($auth->hasAccess('campaign')) {
                if (Hash::check($request->password, $user->password)) {
                    $check = 1;
                } else {
                    return response()->json(['error' => 'Invalid password']);
                }
            } else {
                return response()->json(['error' => 'You are not allowed to view this page']);
            }
        }

        if (!$user || $check == 0) {
            return response()->json(['error' => 'Invalid email address or password']);
        } else {
            Session::put('campaign_user_session', $user->id);
            if (strpos($user->email, "panacea.live") || strpos($user->email, "panacealive.xyz")) {
                Session::put('campaign_user_panacea', $user->email);
            }
            return response()->json(['success' => true, 'role' => 'user']);
        }
    }

    /**
     * Forgot password code request.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function forgotPassword(Request $request)
    {


        if ($user = User::where('email', $request->email)->first()) {
            $user = Sentinel::findById($user->id);
            Reminder::removeExpired();

            if (!Reminder::exists($user)) {
                $reminder = Reminder::create($user);
            } else {
                $reminder = Reminder::exists($user);
            }

            $codeActive = substr($reminder->code, 0, 4);
            $codeActive = strtoupper($codeActive);

            $message = 'Your code is ' . $codeActive . '. Use this code for resetting your password. Happy Verification. ';
            $sms_response = $this->sendSms($user->phone_number, $message);

            return response()->json(['success' => $reminder->code, 'sms_response' => $sms_response]);

        } else {
            $message = 'Sorry. This number is not registered with us.';
            return response()->json(['error' => $message]);
        }
    }


    /**
     * Get logged in company info
     */
    public function getUserCompany($user)
    {
        $user_email = User::select('email')->where('id', $user->id)->first();
        $ijectable_user=InjectableUser::select('id')->where('user_id',$user->id)->first();
        $explodedEmail = explode('@', $user_email->email);
        $domain = array_pop($explodedEmail);
        if ($domain == 'panacea.live' || $domain == 'panacealive.xyz') {
            return 'panacea';
        } elseif ($domain == 'gmail.com' || $domain == 'hotmail.com' || $domain == 'yahoo.com') {
            return 'invalid';
        } else {
            if(!empty($ijectable_user))
            {
                return 'renata_injectable';
            }
            $company_name = Company::select('display_name')->where('contact_email',
                'like', '%' . $domain)->first();
            return $company_name->display_name;
        }
    }


}
