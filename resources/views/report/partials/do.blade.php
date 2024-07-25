<!-- <img src="{{URL::asset('/img/gema_logo_h64px.png')}}" height="34" width="auto"> -->
<h1 class="title">Delivery Order</h1>

<table style="width:100%; margin-bottom: 10px;">
    <tr>
        <td width="5%"><p class="title">No. DO</p></td>
        <td width="15%"><p class="title">: {{ $data['ref_no'] }}</p></td>
    </tr>
    <tr>
        <td width="5%"><p class="title">Tanggal</p></td>
        <td width="15%"><p class="title">: {{ DateFormat::national(new \DateTime($data['created_at'])) }}</p></td>
    </tr>
    <tr>
        <td width="5%"><p class="title">Tujuan/Line</p></td>
        <td width="15%"><p class="title">: {{ $data['person_company'] }}</p></td>
    </tr>
</table>

<table style="width:100%;" class="gema" cellspacing="0" border="1">
    <thead>
        <tr>
            <th class="text-left" width="25px">No</th>
            <th class="text-left">Item</th>
            <th class="text-right">Qty</th>
            <th class="text-center">WH</th>
            <th class="text-center">QC</th>
        </tr>
    </thead>
    <tbody>
    {{ $no = 1 }}
    @foreach ($data['detail'] as $d)
        <tr>
            <td>{{ $no }}</td>
            <td>{{ $d['item'] }}({{ $d['storage_ref_no'] }})</td>
            <td class="text-right">{{ $d['qty'] }}</td>
            <td></td>
            <td></td>
        </tr>
    {{ $no++ }}
    @endforeach
    </tbody>
</table>
<br>
<table style="width: 100%;" cellspacing="0" cellpadding="0">
    <tbody>
        <tr>
            <td><b>Notes</b></td>
        </tr>
        <tr>
            <td hei>{{ $data['notes'] }}</td>
        </tr>
    </tbody>
</table>
<table style="width: 100%;margin-top: 20px;" cellspacing="0" cellpadding="0">
    <tbody>

        <tr>
            {{-- Add 'false' so it always goes to else block --}}
            @if($signature && false)
                <td class="text-center">
                    <img style="height: 70px;width:auto;" src="{{ $signature }}" alt="signature" />
                </td>
            @else
                <td height="70px"></td>
            @endif
        </tr>
        <tr>
            <td class="text-center">
                <u>
                    @if($pic)
                        {{ $pic->name }}
                    @endif
                </u>
            </td>
        </tr>
        <tr>
            <td class="text-center">
                <strong>
                    @if($pic)
                        {{ $pic->department->label }}
                    @endif
                </strong>
            </td>
        </tr>
    </tbody>
</table>