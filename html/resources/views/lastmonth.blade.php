<style>
    th,td{
        text-align: right;
        width: 5rem;
    }
</style>
<table border="1">
    <thead>
        <tr>
            <th>Date</th>
            <th>Cash</th>
            <th>Bank</th>
            {{-- @foreach($lastmonth as $last)@endforeach
            @foreach ($last['voc_json'] as $key=>$c)
            <th>{{ $key }}</th>
            @endforeach --}}
            <th>Json</th>
            <th>(Cash+Bank)-Json</th>
        </tr>
    </thead>
    <tbody>
        @php $cash=$bank=0; $sums = [];@endphp
        @forelse ($lastmonth as $last)
            @php 
                $rowSum = 0;
                $cash+=$last['voc_cash'];$bank+=$last['voc_bank'];
                foreach($last['voc_json'] as $json){ 
                    $sum[] = $json;               
                    $rowSum += $json;
                }
                $balance = ($last['voc_cash']+$last['voc_bank'])-$rowSum    ;
            @endphp
            <tr>
                <td>{{ $last['voc_date'] }}</td>
                <td>{{ $last['voc_cash'] }}</td>
                <td>{{ $last['voc_bank'] }}</td>
                {{-- @foreach($last['voc_json'] as $json)
                <td>{{ $json }}</td>
                @endforeach --}}
                <td>{{ $rowSum }}</td>
                <td>{{ $balance }}
            </tr>
        @empty
            <tr><td colspan="5">NoData Found</td></tr>
        @endforelse
        <tr>
            @php $jsonsum=isset($sum)?array_sum($sum):0;@endphp
            <td>Total</td>
            <td>{{ $cash }}</td>
            <td>{{ $bank }}</td>
            {{-- @foreach($last['voc_json'] as $json)
            <td></td>
            @endforeach --}}
            <td>{{ $jsonsum }}</td>
            <td>{{ ($cash+$bank)-$jsonsum }}</td>
        </tr>
    </tbody>
</table>