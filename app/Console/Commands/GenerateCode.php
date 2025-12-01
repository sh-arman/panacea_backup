<?php

namespace Panacea\Console\Commands;

use Illuminate\Console\Command;
use Panacea\Code;
use Illuminate\Support\Facades\Mail;

class GenerateCode extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'panacea:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates unique verification codes and stores on the database';

    /**
     * Create a new command instance.
     *
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        
        $quantity = 0;
        
        $availableCodeCount = Code::where( 'status', '=', '0' )->count();

        if ( $availableCodeCount <= 2000000 ) {
            $quantity = 1000;
            // Mail::raw( 'Available codes are running low, Automatic code generation increases to 10 lakhs in this hour.', function ( $message ) {
            //     $message->to( 'soumik@panacea.live' )->cc( 'ahmed@panacea.live' );
            //     $message->subject( '[Panacea] Automatic Available Codes Generation!' );
            // } );
        } elseif( $availableCodeCount >= 5000000 ){
            $quantity = 500;
            // Mail::raw( 'Available codes are more than 50,00000 (50Lakhs), Automatic code generation dicreases to 500 in this hour.', function ( $message ) {
            //     $message->to( 'soumik@panacea.live' )->cc( 'ahmed@panacea.live' );
            //     $message->subject( '[Panacea] Automatic Available Codes Generation!' );
            // } );
        } else {
            $quantity = 20000;
        } 

        for ( $i = 0; $i < $quantity; $i++ )
        {
            $data = [
                'code' => $this->generateCode(),
            ];

            try {
                Code::create( $data );

                // $this->info("Code added:". $data['code']);
            } catch ( \Illuminate\Database\QueryException $e ) {
                // $this->info("Error:". $e->getMessage());
                continue;
            }

        }

        $this->info( "Verification code generation successful!" );
        \Log::info( "Codes generated!" );
    }

    /**
     * Generates random codes
     *
     * @return string
     */
    private function generateCode() {
        $an = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $su = strlen( $an ) - 1;
        $generatedString = substr( $an, rand( 0, $su ), 1 ) .
        substr( $an, rand( 0, $su ), 1 ) .
        substr( $an, rand( 0, $su ), 1 ) .
        substr( $an, rand( 0, $su ), 1 ) .
        substr( $an, rand( 0, $su ), 1 ) .
        substr( $an, rand( 0, $su ), 1 ) .
        substr( $an, rand( 0, $su ), 1 );
        if ( $generatedString == "MCKRTW" || $generatedString == "MCKRTWS" || $generatedString == "MTAJGPD" ) {
        } else {
            return $generatedString;
        }
    }
}
