<?php
namespace App\Exports;
use App\Models\Courrier;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
class ExportCourrier implements FromView,ShouldAutoSize
{
   
    public $type;       
    public $service;  
    public $origine;    
    public $date_min; 
    public $date_max;   
    public $niveau;    

    public function __construct($type, $service, $origine, $date_min, $date_max, $niveau)
    {
        $this->type     = $type;
        $this->service  = $service;
        $this->origine  = $origine;
        $this->date_min = $date_min;
        $this->date_max = $date_max;
        $this->niveau   = $niveau;
    }

    public function view():View
    {
        $courriers = Courrier::with('service', 'niveau_importance', 'origine')->get();
        if ($this->type != 'all')
            $courriers = $courriers->where('type', $this->type);
        if ($this->service != 'all')
            $courriers = $courriers->where('service_id', $this->service);
        if ($this->origine != 'all')
            $courriers = $courriers->where('ar_origine_id', $this->origine);
        if ($this->niveau != 'all')
            $courriers = $courriers->where('ref_niveau_importances', $this->niveau);
        if($this->date_min != '' || $this->date_max != '')
            $courriers = $courriers->whereBetween('date_transaction', [$this->date_min, $this->date_max]);
        // dd($courriers);
        return view('courriers.export_excel',['courriers' =>$courriers]);
    }
}
