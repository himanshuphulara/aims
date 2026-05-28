<style>
table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}

table, tr, td {
    border: 1px solid #000;
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
</style>
     
<div class="container">
    <h3 style="text-align: center"><u>RECEIPT, ISSUE AND EXPENSE VOUCHER (a)</u> (CRV)</h3>
    <div style="width:50%;float:left">
        <h4>To Be Completed By Issuing Officer</h4>
        <h4>Issue Voucher No:	{{ $crvs->issue_voc_no }}</h4>
        <h4>Expense: {{ $crvs->issue_expense }}</h4>
        <h4>Unit: {{ $crvs->issue_unit }}</h4>
        <h4>Station: {{ $crvs->issue_station }}</h4>
    </div>
    <div style="width:50%;float:right">
        <h4>To Be Completed By Receiving Officer</h4>
        <h4>Receipt No:	{{ $crvs->receipt_voc_no }}</h4>
        <h4>Date: {{ $crvs->receipt_date }}</h4>
        <h4>Unit: {{ $crvs->receipt_unit }}</h4>
        <h4>Station: {{ $crvs->receipt_station }}</h4>
    </div>
    <div style="clear:both;">
        <hr>
    <div style="width:50%;float:left">
        <h4 style="text-align:right">Issued To</h4>
    </div>
    <div style="clear:both">

    <div style="width:50%;float:left">
        <h4>The articles enumerated below have been:</h4>
    </div>    
    <div style="width:50%;float:right">
        <h4>(a) Received by</h4>
        <h4>Manufactured: Purchase from</h4>
        <h4><u>{{ $crvs->purchase_from }}</u> for</h4> 
        <h4>the FY <u>{{ $crvs->for_fy }}</u> Vide Bill No <u>{{ $crvs->bill_no }}</u></h4>
        <h4>GEM <u>{{ $crvs->gem }}</u> dt <u>{{ $crvs->dt }}</u></h4>
        <h4>Contract No	 <u>{{ $crvs->contact_no }}</u></h4>
        <h4>GEMCRAC	 <u>{{ $crvs->gemcrac }}</u></h4>
        <h4>Dated	 <u>{{ $crvs->dated }}</u> .</h4>
    </div>
    <div style="clear:both">

    <div style="width:100%;">
        <h4>(b) In (a) h4art in compliance with (c) Sanction accorded by CO <b id="unitname">{{ $crvs->issue_unit }}</h4>
        <h4>The articles enumerated below have been explained under the authority of (c)</h4>
    </div>

    <div style="width:100%;" >
        <table id="header">
                <tr>
                    <td class="text-align-center">S.No</td>
                    <td class="text-align-center">LP No</td>
                    <td class="text-align-center">Items</td>
                    <td class="text-align-center">A/U</td>
                    <td class="text-align-center" style="width:15%">Date</td>
                    <td class="text-align-center">Qty</td>
                    <td class="text-align-center">Rate</td>
                    <td class="text-align-center">Amount</td>
                </tr>
            <tbody>                                        
                @php 
                    $crvdata = \App\Models\Items::where('parent_item_id',$crvs->id)->where('property_type','crvs')->get();
                    $total = 0;
                    $sno=1;
                @endphp
                @foreach($crvdata as $item)
                @php if($item['qty']!=''){$total += $item['qty'];}else{$total;} @endphp
                <tr>
                    <td class="text-align-center">{{ $sno++ }}</td>
                    <td class="text-align-center">{{ $item['lpno'] }}</td>
                    <td class="text-align-center">{{ $item['items'] }}</td>
                    <td class="text-align-center">{{ $item['au'] }}</td>
                    <td class="text-align-center" style="width:15%">{{ $item['date'] }}</td>
                    <td class="text-align-center">{{ $item['qty'] }}</td>
                    <td class="text-align-center">{{ $item['rate'] }}</td>
                    <td class="text-align-center">{{ $item['amt'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>                   
        <h4 class="text-align-center">( Total item <u>{{ $total }}</u> only)</h4>
        <h4 class="text-align-center">"Certified that above items have been taken on ledger charge by means of this CRV"</h4>
        {{-- <h4>Signature of store holder: {{ $crvs->holder_sign }}</h4>                              --}}
        <br>
        <br>
        <br>
        <h4>Signature of store holder.</h4>                             
    </div>
</div>

            