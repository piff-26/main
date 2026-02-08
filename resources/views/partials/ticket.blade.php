@push('styles')
    <style>
        .title-session{
            color: var(--black);
            font-size: 50px;
            font-family: "Arial";
        }
        .info-session{
            color: var(--black);
            font-family: "Arial";
            text-align: center;
            font-size: 16px;
        }
    </style>
@endpush

<div class="relative w-screen left-1/2 -translate-x-1/2 h-auto overflow-hidden">
    <div class="absolute inset-0 w-full h-full z-0" style="background-color: yellow"></div>
    <div class="relative z-10 container mx-auto py-20 px-4">
        <div class="flex items-center justify-center gap-6">
            <div>
                <h1 class="title-session" style="font-weight: lighter">SESSION</h1>
            </div>
            <div>
                <h1 class="title-session" style="font-weight: bold">ACCESS</h1>
            </div>
        </div>
        <div>
            <h2 class="info-session" style="font-weight: 800">Tickets for D-Day sessions will be available soon</h2>
            <h2 class="info-session" style="font-weight: 500">Check back for more details.</h2>
        </div>
    </div>
    
</div>




@push('scripts')
<script>

</script>
@endpush
