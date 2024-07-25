{{-- paper: 'dot-matrix' --}}
@if(isset($paper) && $paper == 'dot-matrix')
<style>
    /* Dot Matrix = A4/2 */
    @page { size: 21cm 14cm; }
</style>
@else
<style>
    /* A4 */
    @page { size: 21cm 29.7cm; }
</style>
@endif