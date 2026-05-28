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
        <h3 style="text-align: center"><u>RECEIPT ISSUE AND EXPENSE VOUCHER (S)</u> (NIV)</h3>
    <div style="width:50%;float:left">
        <h4>To be completed by Issuing Officer</h4>
        <h4>Issue Voucher No:NIV/{{ $nivs->issue_voc_no }}</h4>
        <h4>Date&nbsp;  :&nbsp; {{ $nivs->issue_date }}</h4>
        <h4>Unit&nbsp;  :&nbsp; {{ $nivs->issue_unit }}</h4>
        <h4>Station&nbsp;  :&nbsp; {{ $nivs->issue_station }}</h4>
    </div>
    <div style="width:50%;float:right">
        <h4>To be completed by Receiving Officer</h4>
        <h4>Receipt Voucher No:	{{ $nivs->receipt_voc_no }}</h4>
        <h4>Date&nbsp;  :&nbsp; {{ $nivs->receipt_date }}</h4>
        <h4>Unit&nbsp;  :&nbsp; {{ $nivs->receipt_unit }}</h4>
        <h4>Station&nbsp;  :&nbsp; {{ $nivs->receipt_station }}</h4>
    </div>
    <div style="clear:both;">
        <div style="width:100%;">
            <h4>Issue to: Items issued as per remarks</h4>
            <p>In compliance with: Issue of items procured from Public Fund.</p>
        </div>        
    </div>
    
    <div style="width:100%;" >
        <table>
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
                    $nivdata = \App\Models\Items::where('parent_item_id',$nivs->id)->where('property_type','nivs')->get();
                    $total = 0;
                    $sno=1;
                @endphp
                @foreach($nivdata as $item)
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
        <h4 style="text-align:center">( Total item <u>{{ $total }}</u> only)</h4>
        <h4 style="text-align:center">"PI return one copy duly receipted"</h4>     
    </div>

    <div style="clear:both"></div>

    
        <div style="width:33%;float:left">
            <h4>Issued by</h4>
            <h4>{{ $nivs->receipt_station }}</h4>
        </div>
        <div style="width:33%;float:left">
            <h4>Collected by</h4>
            <h4>Sig&nbsp; &nbsp;      :&nbsp;  {{ $nivs->sig }}</h4>
            <h4>No&nbsp; &nbsp;       :&nbsp;  {{ $nivs->no }}</h4>
            <h4>Rank&nbsp; &nbsp;     :&nbsp;  {{ $nivs->rank }}</h4>
            <h4>Name&nbsp; &nbsp;     :&nbsp;  {{ $nivs->name }}</h4>
            <h4>Date&nbsp; &nbsp;     :&nbsp;  {{ $nivs->date }}</h4>
        </div>
        <div style="width:33%;float:left">
            <h4>Received by</h4>
            <h4>{{ $nivs->receipt_station }}</h4>
        </div>
           
</div>