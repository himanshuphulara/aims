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
    <h3 class="text-align-center">NIV LEDGER FROM </h3>
    <h3 class="text-align-center">{{ $start }} - {{ $end }} </h3>
    <table border=1>
            <tr>
                <td class="text-align-center" data-column-id="date">S.No.</td>
                <td class="text-align-center" data-column-id="items">Items</td>
                <td class="text-align-center" data-column-id="au">A/U</td>
                <td class="text-align-center" data-column-id="qty">Date</td>
                <td data-column-id="rate" class="text-align-center">Qty</td>
                <td data-column-id="rate" class="text-align-center">Remarks</td>
            </tr>
        <tbody>
            @php $i=1; @endphp
            @forelse ($nivledger as $niv)
                <tr>
                    <td class="text-align-center">{{ $i++ }}</td>
                    <td class="text-align-center">{{ $niv['items'] }}</td>
                    <td class="text-align-center">{{ $niv['au'] }}</td>
                    <td class="text-align-center">{{ $niv['date'] }}</td>
                    <td class="text-align-center">{{ $niv['qty'] }}</td>
                    <td class="text-align-center">{{ $niv['remarks'] }}</td>
                </tr>      
            @empty
            <tr class="text-center"><td colspan="25">No Data Founds</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>