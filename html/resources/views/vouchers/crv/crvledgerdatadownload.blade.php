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
    <h3 class="text-align-center">LEDGER FROM </h3>
    <h3 class="text-align-center">{{ $start }} - {{ $end }} </h3>
    <table border=1>
        <thead>
            <tr>
                <th rowspan="2" class="text-align-center" data-column-id="date">S.No.</th>
                <th rowspan="2" class="text-align-center" data-column-id="lpno">LP.No.</th>
                <th rowspan="2" class="text-align-center" data-column-id="items">Nomenclature</th>
                <th rowspan="2" class="text-align-center" data-column-id="au">A/U</th>
                <th rowspan="2" class="text-align-center" data-column-id="qty">Yr/Dt of Purchase</th>
                <th data-column-id="rate" class="text-align-center">Qty Held</th>
                <th data-column-id="rate" class="text-align-center" colspan="2">Cost(As per ASTB)</th>
                <th data-column-id="rate" class="text-align-center"></th>
            </tr>
            <tr>
                <th class="text-align-center">Grnd Bal</th>
                <th class="text-align-center">Rate(in Rs)</th>
                <th class="text-align-center">Amount(in Rs)</th>
                <th class="text-align-center">Sub Unit Distr</th>
            </tr>
        </thead>
        <tbody>
            @php $i=1; @endphp
            @forelse ($crvledger as $crv)
                <tr>
                    <td class="text-align-center">{{ $i++ }}</td>
                    <td class="text-align-center">{{ $crv['lpno'] }}</td>
                    <td class="text-align-center">{{ $crv['items'] }}</td>
                    <td class="text-align-center">{{ $crv['au'] }}</td>
                    <td class="text-align-center">{{ $crv['date'] }}</td>
                    <td class="text-align-center">{{ $crv['qty'] }}</td>
                    <td class="text-align-center">{{ number_format($crv['rate'],2,'.',',') }}</td>
                    <td class="text-align-center">{{ number_format($crv['qty']*$crv['rate'],2,'.',',') }}</td>
                    <td></td>
                </tr>      
            @empty
            <tr class="text-center"><td colspan="25">No Data Founds</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>