@push('styles')
    <style>
        #session-1{
            color: var(--black);
            font-size: 50px;
            font-weight: lighter;
            font-family: "Arial";
        }
        #session-2{
            color: var(--black);
            font-size: 50px;
            font-weight: bold;
            font-family: "Arial Black";
        }
        #grid-session{
            background-color: yellow;
        }
        #description-session{
            background-color: yellow;
        }
        #info-session-1{
            font-family: "Arial";
            text-align: center;
            font-weight: 800;
            font-size: 16px;
        }
        #info-session-2{
            font-family: "Arial";
            text-align: center;
            font-weight: 500;
            font-size: 16px;
        }
    </style>
@endpush

<div class="grid grid-cols-12" id="grid-session">
    <div class="col-span-6">
        <h1 id="session-1">SESSION</h1>
    </div>
    <div class="col-span-6 ml-2">
        <h1 id="session-2">ACCESS</h1>
    </div>
</div>
<div id="description-session">
    <h2 id="info-session-1">Tickets for D-Day sessions will be available soon</h2>
    <h2 id="info-session-2">Check back for more details.</h2>
</div>


@push('scripts')
<script>

</script>
@endpush
