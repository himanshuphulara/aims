@php use App\Helpers\Helper; @endphp
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
    * { font-family: DejaVu Sans, sans-serif; }
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }
    
    #header tr, #header td {
        border-left: 1px solid #000;
        border-right: 1px solid #000;
        font-size: 10px !important;
        font-weight: bold !important;
    }
    
    th, td {
        padding: 1;
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
        font-size: 10px;
    }
    .page-break {
    page-break-after: always;
}
    </style>
    <div class="container">
        <h3 style="text-align: center">Officers Messbill</h3>
        <p style="text-align: center"><b>1841 Rkt Regt (Pinaka)</b></p>
        <div style="width:50%">
            <h4>No:	{{ $messbill->id }}</h4>
            <h4>Messbill of <u>{{ $messbill->officer_name }}</u></h4>
            <h4>for the duration <u>{{ date('M, Y',strtotime(request()->start)) }}</u></h4>
        </div>
        {{-- <div style="clear:both"> --}}
        <div style="width:100%;" >
            <table id="header">
                    <tr>
                        <td class="text-align-center" style="border: 1px solid #000;">Details of Recovery</td>
                        <td style="text-align: right;border: 1px solid #000;">Rs</td>
                    </tr>
                <tbody>
                    @foreach ($sums as $key=>$value)
                        @php 
                            $name = '';
                            $catname = str_replace("_", " ", $key); 
                            $sets = explode(' ',$catname);
                            foreach($sets as $act)
                            {
                                $name.=ucfirst($act).' ';
                            }
                        @endphp
                        <tr>
                            <td>{{ $name }}</td>
                            <td class="align-top text-wrap" style="text-align: right;">
                                {{ Helper::numberFormat($value) }}
                            </td>  
                            {{-- <td style="text-align: right">{{ $result[$catname] }}</td>       --}}
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
                    <td style="text-align: right"><b>Mess Secretary</b></td>
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
                    <td style="text-align: right"><b>Mess Secretary</b></td>
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

    <div class="page-break"></div>

    <div class="container" style="width:100%">
        <h3 style="text-align: center">Officers Messbill</h3>
        <p style="text-align: center"><b>1841 Rkt Regt (Pinaka)</b></p>
    <table  id="last">
        <tr>
            <td><b>No: {{ $messbill->id }}</b></td>
            <td><b>Date {{ $messbill->bill_date }}</b></td>
        </tr>
        <tr>
            <td><b>Received from <u>{{ $messbill->officer_name }}</u></b></td>
        </tr>
        <tr>
            <td> <b>a sum of Rs. <u>{{ Helper::numberFormat($final) }}</u></b></td>
        </tr>
        <tr>
            <td> <b>on account of Mess Bill for the month of <u>{{ date('M, Y',strtotime(request()->start)) }}</u></b></td>
        </tr>
        <tr>
            <td><b>by Cash/Cheque No. _________________________</b></td>
        </tr>
        <tr>
            <td><b>₹ <u>{{ Helper::numberFormat($final) }}</u></b></td>
            <td><b>Mess Secretary</b></td>
        </tr>
    </table> 
    </div> 

    <div class="container" style="border-top:3px dotted #000;width:100%">
        <h3 style="text-align: center">Officers Messbill</h3>
        <p style="text-align: center"><b>1841 Rkt Regt (Pinaka)</b></p>
    <table  id="last">
        <tr>
            <td><b>No: {{ $messbill->id }}</b></td>
            <td><b>Date {{ $messbill->bill_date }}</b></td>
        </tr>
        <tr>
            <td><b>Received from <u>{{ $messbill->officer_name }}</u></b></td>
        </tr>
        <tr>
            <td> <b>a sum of Rs. <u>{{ Helper::numberFormat($final) }}</u></b></td>
        </tr>
        <tr>
            <td> <b>on account of Mess Bill for the month of <u>{{ date('M, Y',strtotime(request()->start)) }}</u></b></td>
        </tr>
        <tr>
            <td><b>by Cash/Cheque No. _________________________</b></td>
        </tr>
        <tr>
            <td><b>₹ <u>{{ Helper::numberFormat($final) }}</u></b></td>
            <td><b>Mess Secretary</b></td>
        </tr>
    </table> 
    </div> 