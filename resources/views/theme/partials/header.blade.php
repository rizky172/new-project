{{-- Type: 'dot-matrix' --}}
@if(isset($type) && $type == 'dot-matrix')
<header class="logo">
    <table width="100%" style="padding: 0px; margin: 0px">
        <tr style="padding: 0px; margin: 0px">
            <td width="185px" style="padding: 0px; margin: 0px"><img src="{{URL::asset('/img/gema_logo_h64px.png')}}" height="64" width="auto">
            </td>
            <td>
                <p>Jl. DOT Rawa Bogo No.123 RT.001/003 Kel Padurenan Kec.Mustika Jaya</p>
                <p>Telp.021-8262-3919 / 021-8262-3311 </p>
                <p>Email: <a href="mailto:tika@gemaputraabadi.com">tika@gemaputraabadi.com</a></p>
                <div class="border-outline wd50">
                    <p><b>NPWP  31.341.750.3-432.000</b></p>
                </div>
            </td>
        </tr>
    </table>
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
@else
<header class="logo">
    <img src="{{URL::asset('/img/gema_logo_h64px.png')}}" height="64" width="auto">
    <p>Jl. Rawa Bogo No.123 RT.001/003 Kel Padurenan Kec.Mustika Jaya</p>
    <p>Telp.021-8262-3919 / 021-8262-3311 Email: </p>
    <p>Email: <a href="mailto:tika@gemaputraabadi.com">tika@gemaputraabadi.com</a></p>
    <div class="border-outline wd50">
        <p><b>NPWP  31.341.750.3-432.000</b></p>
    </div>
</header>
@endif