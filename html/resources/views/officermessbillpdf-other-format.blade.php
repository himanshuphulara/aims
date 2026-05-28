@php use App\Helpers\Helper; @endphp
<style>
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }
    
    #header tr, #header td {
        border-left: 1px solid #000;
        border-right: 1px solid #000;
        font-size: .80rem !important;
        font-weight: bold !important;
    }
    
    th, td {
        padding: 5;
        text-align: left;
    }
    
    th {
        background-color: #f2f2f2;
    }
    
    .signature {
        margin-top: 20px;
    }
    .container{
        display: flex;
    }
    .text-align-center{
        text-align: center !important;
    }
    /* #header { margin-top: 35px; left: 0px; right: 0px; padding: 10px; } */
    h4{
        font-size: .80rem !important;
        margin-top: 13px;
        margin-bottom: 13px;
    }
    #last {
        font-size: 15px;
    }
    </style>
    <div class="container">
        <h3 style="text-align: center">Officers Messbill</h3>
        <p style="text-align: center"><b>1841 Rkt Regt (Pinaka)</b></p>
        <div style="width:50%">
            <h4>No:	{{ $messbill->id }}</h4>
            <h4>Messbill of <u>{{ $messbill->officer_name }}</u></h4>
            <h4>for the duration <u>{{ $messbill->bill_date }}</u></h4>
        </div>
        <div style="clear:both">
        <div style="width:100%;" >
            <table id="header">
                    <tr>
                        <td class="text-align-center" style="border: 1px solid #000;">Details of Recovery</td>
                        <td style="text-align: right;border: 1px solid #000;">Rs</td>
                    </tr>
                <tbody>
                    @foreach ($categories as $cate)
                        @php $catname = strtolower(str_replace(" ", "_", $cate->name)) @endphp
                        <tr>
                            <td class="align-top text-wrap">
                                @if($catname == 'ent_fund')
                                {{ $cate->name }}
                                @else
                                {{ $cate->name }}
                                @endif                                                
                                @foreach ($subcats as $subcat)
                                    @if($subcat->main_category == $catname)
                                            + {{ $subcat->subcategory_name }}
                                    @endif
                                @endforeach 
                            </td>  
                            <td style="text-align: right">{{ $result[$catname] }}</td>      
                        </tr>                                           
                    @endforeach
                </tbody>
                <tfoot style="border-top: 1px solid #000;border-bottom:1px solid #000;">
                    <tr>
                        <td class="text-end"><b>Total</b></td>
                        <td style="text-align: right"><b>{{ Helper::numberFormat($messbill->bill_total) }}</b></td>
                    </tr>
                    <tr>
                        <td class="text-end"><b>Round off</b></td>
                        <td style="text-align: right"><b>{{ Helper::numberFormat($messbill->round_off) }}</b></td>
                    </tr>
                    <tr>
                        <td class="text-end"><b>Grand Total</b></td>
                        @php
                            $round_off = $messbill->round_off;
                            $sum = $messbill->bill_total;
                            $final = $sum+$round_off;
                        @endphp
                        <td style="text-align: right"><b>{{ Helper::numberFormat($final) }}</b></td>
                    </tr>
                </tfoot>
            </table>   
            <table style="border-bottom:1px solid #000;" id="last">
                <tr>
                    <td><b>Date __________________________</b></td>
                    <td style="text-align: right"><b>Mess Secretay</b></td>
                </tr>
                <tr>
                    <td>Received payment by</td>
                </tr>
                <tr>
                    <td> <b>Cheque Rs. ____________________</b></td>
                </tr>
                <tr>
                    <td> <b>Cash Rs. ______________________</b></td>
                </tr>
                <tr>
                    <td><b>Date _________________________</b></td>
                    <td style="text-align: right"><b>Mess Secretay</b></td>
                </tr>
            </table>                       
        </div>
        <div id="last">
            <b>Note:</b>
            <ol>
                <li><b>Cheque will be drawn in favour of Officers Mess 1841 Rkt Regt (Pinaka). Please add Rs. 50/- for outstation cheque</b></li>
                <li><b>Mess Bill will be cleared by 15th of every month or before leaving the station (Not at per)</b></li>
            </ol>
        </div>
    </div>

    public function officerMessbillPdf($mess_id,$catid,$start,$end)
    {
        // $messbill = Messbill::find($mess_id);
        $title = "Officer Messbill Pdf";
        $categories = Category::where('parent_id',$catid)->get(['name','type']);
        $subcats = MessbillSubCategory::where([
            'category_id'=>$catid,
            'subcat_date'=>$start
        ])->get(['main_category','subcategory_name','amount']);
        $messbill = Messbill::where('id',$mess_id)->first();
        $bills = json_decode($messbill->bill_json,true);
        foreach ($bills as $key=>$value){
            if (!isset($sums[$key])){
                $sums[$key] = 0;
                $forother[$key] = 0;
            }
            $sums[$key] += $value; 
            $forother[$key] += $value; 
        }
        $subcatgs_json = json_decode($messbill->subcatgs_json,true);
        foreach ($subcatgs_json as $key1=>$value){
            foreach($value as $key=>$value){
                if (!isset($sums[$key])){
                    $sums[$key] = 0;
                    $other[$key1][$key] = 0;
                }
                $sums[$key] += $value; 
                $other[$key1][$key] += $value; 
            }
        }
        foreach ($other as $key=>$sub_array){
            if (is_array($sub_array)){
                if($key=='cat_stock'){
                    $other[$key] = array_sum($sub_array);
                }else{
                    $other[$key] = array_sum($sub_array);
                }                
            }
        }
        // print_r($other);            
        foreach ($forother as $key=>$value){
            if (isset($other[$key])){
                $result[$key] = Helper::numberFormat($value + $other[$key]); // Sum of matching keys
            }else{
                if($key=='ent_fund'){
                    $result[$key] = Helper::numberFormat($value);
                }else{
                    $result[$key] = Helper::numberFormat($value);
                }
            }
        }
        // print_r($result);die;
        $pdf = PDF::loadView('messbill.officermessbillpdf',['title'=>$title,'messbill'=>$messbill,'categories'=>$categories,'subcats'=>$subcats,'result'=>$result]);
        $date = date('d-m-Y_H:i:s');
        return $pdf->download('Officer Messbill-'.$date.'.pdf');
        // return view('messbill.officermessbillpdf',compact('title','messbill','categories','subcats','result'));
    }