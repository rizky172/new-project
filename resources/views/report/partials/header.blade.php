{{-- Type: 'v1' --}}
{{-- Parameter: email => [] --}}
<?php
    $emails = ['info@gemaputraabadi.com'];
    if(!empty($email))
        $emails[] = $email;
?>
@if(isset($type) && $type == 'v1')
<header class="logo">
    <img src="{{URL::asset('/img/gema_logo_h64px.png')}}" height="64" width="auto">
    @include('report.partials.address', ['emails' => $emails])
</header>
@elseif(isset($type) && $type == 'salary-slip')
<header class="logo">
    <table width="100%" style="padding: 0px; margin: 0px">
        <tr style="padding: 0px; margin: 0px">
            <td width="185px" style="padding: 0px; margin: 0px"><img src="{{URL::asset('/img/gema_logo_h64px.png')}}" height="64" width="auto">
            </td>
            <td>
                <h1 class="text-center">{{ $title }}</h1>
                <h2 class="text-center">{{ $sub_title }}</h2>
            </td>
        </tr>
    </table>
</header>
@elseif(isset($type) && $type == 'surat-jalan')
<header class="logo">
    <table width="100%" style="padding: 0px; margin: 0px">
        <tr style="padding: 0px; margin: 0px">
            <td width="185px" style="padding: 0px; margin: 0px"><img src="{{URL::asset('/img/gema_logo_h64px.png')}}" height="64" width="auto">
            </td>
            <td>@include('report.partials.address', ['emails' => $emails])</td>
        </tr>
    </table>
    <table width="100%" style="padding: 0px; margin: 0px">
        <tr style="padding: 0px; margin: 0px">
            <td><h1>{{ $title }}</h1></td>
            <td align="right"><b>Document Date: {{ $created }}</b></td>
        </tr>
    </table>
</header>
@else
{{-- Default HEADER --}}
<header class="logo">
    <table width="100%" style="padding: 0px; margin: 0px; background-color: rgb(65, 60, 60); color: white">
        <tr style="padding: 0px; margin: 0px">
            <td width="80px" style="padding: 10px; margin: 0px"><img src="{{URL::asset('/uploads/config/'.$logo)}}" height="80px" width="80px">
            </td>
            <td>@include('report.partials.address', ['emails' => $company_email])</td>
        </tr>
    </table>
</header>
@endif