<?php
namespace App\Exports;
use App\Models\Archive;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
class ExportArchive implements FromView,ShouldAutoSize
{
   
    public $type;       
    public $service;  
    public $date_min; 
    public $date_max;   
    
    public function __construct($type, $service, $date_min, $date_max)
    {
        $this->type     = $type;
        $this->service  = $service;
        $this->date_min = $date_min;
        $this->date_max = $date_max;
        
    }

    public function view():View
    {
        $archives = Archive::with('service', 'type_archive')->get();
        if ($this->type != 'all')
            $archives = $archives->where('ref_type_archive_id', $this->type);
        if ($this->service != 'all')
            $archives = $archives->where('service_id', $this->service);
        if($this->date_min != '' || $this->date_max != '')
            $archives = $archives->whereBetween('date_archivage', [$this->date_min, $this->date_max]);
        // dd($this->service);
        return view('archives.export_archive',['archives' =>$archives]);
    }
}
