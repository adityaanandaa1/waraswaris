<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Carbon\Carbon;

class SidebarComposer
{
    public function compose(View $view)
    {
        $hari_ini = Carbon::today();
        $nama_hari = $hari_ini->locale('id')->translatedFormat('l');
        
        $view->with([
            'hari_ini' => $hari_ini,
            'nama_hari' => $nama_hari,
        ]);
    }
}