<?php

namespace App\Console;

use App\Sifec\Sifec;
use App\Technodev\TechnoDev;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        // $schedule->call(function(){
        //     $service = new TechnoDev;
        //     $service->getNonConfirmes();
        // })->everyMinute()->runInBackground();

        $schedule->call(function(){
            $service = new Sifec;
            $service->pushDataTo();
        })->everyMinute()->runInBackground();

        $schedule->call(function(){
            $service = new Sifec;
            $service->checkMatching();
        })->everyMinute()->runInBackground();

        // Purge des OTP expirés — s'exécute chaque minute (côté serveur, non contournable)
        $schedule->call(function(){
            $service = new Sifec;
            $service->validiteCodeOtpRegistre();
        })->everyMinute()->runInBackground();

        $schedule->call(function(){
            $service = new Sifec;
            $service->validiteCodeOtpActeNaissance();
        })->everyMinute()->runInBackground();

        $schedule->call(function(){
            $service = new Sifec;
            $service->validiteCodeOtpActeMariage();
        })->everyMinute()->runInBackground();

        $schedule->call(function(){
            $service = new Sifec;
            $service->validiteCodeOtpActeDeces();
        })->everyMinute()->runInBackground();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
