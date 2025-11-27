<?php

namespace Panacea\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Panacea\Http\Requests;
use Panacea\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


class PanalyticsController extends Controller
{
    public $page_title = '';
    public $body_id = '';
    /**
     * PanalyticsController constructor.
     */
    public function __construct()
    {
        $this->page_title = 'Panacea. The Future Is Original.';
        $this->body_id = '';
    }

    
    /**
     * Landing page
     */
    function showLanding()
    {
        $data = [];
        $data['page_title'] = 'Renata Analytics';
        $data['body_id'] = '';
        return view('panalytics.home', $data);
    }

    /**
     * Main post processing
     */
    function analysis()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $val = 0;

        $var = $request->source;
        $med = $request->medicine_name;
        $errorMed = array_filter($med);
        $error = array_filter($var);
        $type = $request->remarks;
        $datelimit = $request->created_at;
        $startDate = $request->rangeStart;
        $endDate = $request->rangeEnd;
        $operator = array_filter($request->operators);
        //$operator = $request->operators;


        $strMedia = "('" . implode("','", $var) . "')";
        $strMed = "('" . implode("','", $med) . "')";

        if ($type == "unique response") {
            $val = 2;
            if (empty($errorMed)) {
                // Unique response and no medicine selected
                $query = "SELECT remarks,check_history.id,check_history.created_at,WEEKDAY(check_history.created_at) AS 'week',phone_number,date(check_history.created_at) AS created_date, time(check_history.created_at) AS created_time  FROM
                      check_history WHERE 1 AND remarks = 'already verified' ";
            } else {
                // Unique response and medicine selected
                $query = "SELECT remarks,check_history.id,check_history.created_at,WEEKDAY(check_history.created_at) AS 'week',phone_number,date(check_history.created_at) AS created_date, time(check_history.created_at) AS created_time,medicine_name FROM
                      check_history,code,print_order,medicine WHERE check_history.code = code.code AND
                      code.status = print_order.id AND print_order.medicine_id = medicine.id AND
                       medicine_name IN " . $strMed . " AND  remarks = 'already verified'";
            }
        } else {
            if (empty($errorMed)) {
                // Not unique response and no medicine selected
                $query = "SELECT remarks,check_history.id,check_history.created_at,WEEKDAY(check_history.created_at) AS 'week',phone_number,date(check_history.created_at) AS created_date, time(check_history.created_at) AS created_time FROM
                      check_history WHERE 1  ";
            } else {
                if ($type == "invalid code") {
                    $val = 1;
                    // Not unique response and medicine selected and invalid code
                    $query = "SELECT remarks,check_history.id,check_history.created_at,WEEKDAY(check_history.created_at) AS 'week',phone_number,date(check_history.created_at) AS created_date, time(check_history.created_at) AS created_time FROM
                      check_history WHERE 1  AND remarks = '" . $type . " '";
                } else {
                    // Not unique response and medicine selected and not invalid code
                    $query = "SELECT remarks,check_history.id,check_history.created_at,WEEKDAY(check_history.created_at) AS 'week',phone_number,date(check_history.created_at) AS created_date, time(check_history.created_at) AS created_time,medicine_name FROM
                      check_history,code,print_order,medicine WHERE check_history.code = code.code AND
                      code.status = print_order.id AND print_order.medicine_id = medicine.id AND
                       medicine_name IN " . $strMed;
                }
            }
        }
        if ($val == 0) {
            if ($type != '') $query .= " AND remarks = '" . $type . " '";
        }
        $query .= " AND source in " . $strMedia . "";
        if ($datelimit != '') $query .= " AND check_history.created_at >= '" . $datelimit . " '";
        if ($startDate != '' && $endDate != '') {
            $query .= " AND check_history.created_at >= '" . $startDate .
                "' AND check_history.created_at <= '" . $endDate . "'";
        }
        if (count($operator) != 6) {
            $arr = $this->stringyfy($operator);
            if (count($arr) != 0) {
                $strOperator = implode("|", $arr);
                $query .= 'AND phone_number REGEXP "' . $strOperator . '" ';
            } else {
                $query .= 'AND phone_number REGEXP null ';
            }
        }
        $query .= " AND phone_number not in ('8801675430523','8801674914686','8801844147757','8801676291391','8801881036730','8801551061185','8801820555512','8801924380281','8801759939863','8801629590549','8801671066000') ";
        if ($val == 2) $query .= " group by check_history.code ";
        $query .= " order by check_history.created_at asc";
        //file_put_contents('codes/bot_'.time(), print_r($query, 1));
        $data = DB::select($query);
        if ($request->sendType == '1') {
            echo json_encode($data);
        } else {
            $fileidentity = 'CG-' . time() . '.csv';
            $filename = 'codes/' . $fileidentity;
            $handle = fopen($filename, 'w+');

            if (empty($errorMed)) {
                fputcsv($handle, [
                    '' . 'Phone Number' . '', 'Date', 'Time', 'Response'
                ]);
            } else {
                fputcsv($handle, [
                    '' . 'Phone Number' . '', 'Date', 'Time', 'Medicine Name', 'Response'
                ]);
            }
            foreach ($data as $code) {
                if (empty($errorMed)) {
                    fputcsv($handle, [
                        '' . $code->phone_number . '', $code->created_date, $code->created_time, $code->remarks
                    ]);
                } else {
                    fputcsv($handle, [
                        '' . $code->phone_number . '', $code->created_date, $code->created_time, $code->medicine_name, $code->remarks
                    ]);
                }
            }
            fclose($handle);
            echo json_encode($fileidentity);
        }
    }

    /**
     * Processing operators
     * @param $operator
     * @return array
     */
    function stringyfy($operator)
    {
        $arr = [];
        if (count($operator) == 0) {
            //file_put_contents('codes/bot_'.time(), print_r($arr, 1));
            return $arr;
        } else {
            foreach ($operator as $x) {
                if ($x == 'GP') array_push($arr, '88017');
                if ($x == 'GP') array_push($arr, '5768');
                if ($x == 'Robi') array_push($arr, '88018');
                if ($x == 'Banglalink') array_push($arr, '88019');
                if ($x == 'Airtel') array_push($arr, '88016');
                if ($x == 'Teletalk') array_push($arr, '88015');
                if ($x == 'Citycell') array_push($arr, '88011');
            }
            //file_put_contents('codes/bot_'.time(), print_r($arr, 1));
            return $arr;
        }
    }

    /**
     * View page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    function index()
    {
        if (Session::get('company_user_session')) {
            return view('panalytics.panalytics_view');
        } else {
            $data = [];
            /*$data['page_title'] = $this->page_title;
            $data['body_id'] = $this->body_id;*/
            $data['page_title'] = 'Renata Analytics';
            
            $data['body_id'] = '';

            return redirect('/');
        }
    }

    /**
     * Register processing
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function registration(Request $request)
    {


        $email = $request->email;

        if (strlen($request->password) < 6) {
            return response()->json(['error' => 'Password size must be greater than 6 characters']);
        } else {
            $activationCode = $this->_generateRandomString();
            $activationCode = strtoupper($activationCode);

            list($user, $domain) = explode('@', $email);

            if ($domain == 'panacea.live' || $domain == 'panacealive.xyz' || $domain == 'renata-ltd.com') {
                $password = Hash::make($request->password);
                try {
                    $query = "SELECT id FROM company_user WHERE email = '" . $email . "'";
                    $data = DB::select($query);
                    if (!$data) {
                        DB::insert('INSERT INTO company_user (full_name,email,password,code) VALUES (?, ?, ?, ?)', [$request->name, $email, $password, $activationCode]);
                        $data['msg'] = 'Welcome to Panacea. Your registration code is ' . $activationCode . '. Happy Verification!';
                        Mail::send('emails.company_user', $data, function ($message) use ($request) {
                            $message->to($request->email);
                            $message->subject("[PanaceaLive] Registration Confirmation for Panalytics Panel");
                        });
                        $user = DB::table('company_user')->where('email', $email)->first();

                        return response()->json(['id' => $user->id, 'success' => true, 'sms_response' => "Yup"]);
                    } else {
                        return response()->json(['error' => 'User is already registered']);
                    }
                } catch (QueryException $e) {
                    return response()->json(['error' => 'User is already registered']);
                }
            } else {
                return response()->json(['error' => '<center>Please use your Renata email address to sign up</center>']);
            }
        }

    }

    /**
     * Registration Activation
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function processActivation($id, Request $request)
    {
        $user = DB::table('company_user')->where(
            array('id' => $id,
                'code' => strtoupper($request->code)
            ))->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid or expired activation code.']);
        } else {
            DB::table('company_user')
                ->where('id', $id)
                ->update(array('status' => 1));
            Session::put('company_user_session', $id);

            return response()->json(['success' => 'Account activated', 'id' => $id]);
        }
    }

    /**
     * Login page
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $user = DB::table('company_user')->where(
            array('email' => $request->phone_number))->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $check = 1;
            } else {
                return response()->json(['error' => 'Invalid password']);
            }
        }

        if (!$user || $check == 0) {
            return response()->json(['error' => 'Invalid email address or password']);
        } elseif ($user && $check == 1 && $user->status == 0) {
            return response()->json(['error' => 'Account is not activated!', 'id' => $user->id]);
        } else {
            Session::put('company_user_session', $user->id);
            if (strpos($user->email, "panacea.live") || strpos($user->email, "panacealive.xyz")) {
                Session::put('company_user_panacea', $user->email);
            }
            return response()->json(['success' => true, 'role' => 'user']);
        }
    }

    /**
     * Generate random string
     * @param int $length
     * @return string
     */
    function _generateRandomString($length = 4)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Process logout
     * @return \Illuminate\Http\RedirectResponse
     */
    function processLogout()
    {
        Session::flush(); // removes all session data
        return redirect()->route('panalytics_home');
    }

    /**
     * Forget password request
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgotPassword(Request $request)
    {
        $email = $request->phone_number;
        $user = DB::table('company_user')->where(
            array('email' => $email))->first();

        if ($user) {
            $activationCode = $this->_generateRandomString();
            DB::table('company_user')
                ->where('id', $user->id)
                ->update(array('code' => strtoupper($activationCode)));
            $data['msg'] = strtoupper($activationCode);

            Mail::send('emails.company_user', $data, function ($message) use ($request) {
                $message->to($request->phone_number);
                $message->subject("[PanaceaLive] Reset password ");
            });

            $message = "An activation code is sent to your email. Please use the code to reset your password";
            return response()->json(['success' => true, 'sms_response' => $message]);

        } else {
            $message = 'Sorry. This email is not registered with us.';
            return response()->json(['error' => $message]);
        }
    }

    /**
     * Reset password
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {
        $email = $request->phone_number;
        $user = DB::table('company_user')->where(
            array('email' => $email))->first();

        if ($user) {
            if ($user->code == strtoupper($request->code)) {
                $password = Hash::make($request->password);
                DB::table('company_user')
                    ->where('id', $user->id)
                    ->update(array('password' => $password, 'status' => 1));
                $message = 'Your password has been reset';
                Session::put('company_user_session', $user->id);
                return response()->json(['success' => $message, 'id' => $user->id]);

            } else {
                $message = 'The provided code is not correct for the provided email.';
                return response()->json(['error' => $message]);
            }
        } else {
            $message = 'Sorry. This number is not registered with us.';
            return response()->json(['error' => $message]);
        }
    }
}
