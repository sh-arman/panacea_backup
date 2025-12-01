<?php

namespace Panacea\Jobs;

use Panacea\Code;
use Panacea\Order;
use Panacea\Template;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class GenerateCodesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order_id, $company_id, $medicine_id, $quantity, $prefix, $filename, $user_id;

    public function __construct($order_id, $company_id, $medicine_id, $quantity, $prefix, $filename, $user_id)
    {
        $this->order_id = $order_id;
        $this->company_id = $company_id;
        $this->medicine_id = $medicine_id;
        $this->quantity = $quantity;
        $this->prefix = $prefix;
        $this->filename = $filename;
        $this->user_id = $user_id;
    }

    public function handle()
    {
        Log::info('Generating codes for order JOBBB ' . date('Y-m-d H:i:s'));
        $template = Template::where('med_id', $this->medicine_id)
            ->where('flag', 'active')
            ->first();

        $codesDir = public_path('codes');
        if (!is_dir($codesDir)) {
            @mkdir($codesDir, 0775, true);
        }

        $file = fopen($codesDir . '/' . $this->filename, 'w+');
        Log::info('Generating codes for order JOBBB ' . date('Y-m-d H:i:s'));
        $query = Code::select('id', 'code')
            ->where('status', 0)
            ->where(DB::raw('CHAR_LENGTH(code)'), '=', 7)
            ->where('code', 'not like', '%0%')
            ->orderBy('id', 'desc');

        $chunkSize = 5000;
        $taken = 0;

        $new_message = null;
        Log::info('Generating codes for order JOBBB ' . date('Y-m-d H:i:s'));
        if ($template && $template->template_message != "") {
            $new_message = explode("PBN/REN MCKRTWS", $template->template_message);
        }
        Log::info('Before chunk ' . date('Y-m-d H:i:s'));
        $query->chunk($chunkSize, function ($codes) use (&$taken, $file, $new_message) {
            Log::info('In chunk ' . date('Y-m-d H:i:s') . '---' . count($codes));
            if ($taken >= $this->quantity) return false;

            foreach ($codes as $code) {

                if ($taken >= $this->quantity) break;

                // Write CSV row
                if (Session::get('id') == "1929" && $this->medicine_id == "3") {
                    fputcsv($file, ["SMS (REN " . $code->code . ")"]);
                } elseif (!$new_message) {
                    fputcsv($file, ['REN ' . $code->code]);
                } elseif ($this->prefix == "6spcae") {
                    fputcsv($file, ["REN \x20\x20\x20\x20 " . $code->code]);
                } else {
                    fputcsv($file, [
                        $new_message[0] . "REN " . $code->code . $new_message[1]
                    ]);
                }

                // Update code status
                Code::where('id', $code->id)->update(['status' => $this->order_id]);

                $taken++;
            }

            return $taken < $this->quantity;
        });

        fclose($file);

        // Mark order finished
        Order::where('id', $this->order_id)->update(['status' => 'finished']);
    }
}
