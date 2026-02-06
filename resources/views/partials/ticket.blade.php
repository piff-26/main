@push('styles')
    <style>
        /* css taruh disini
    hati-hati, mungkin kalau nama id/class kalian sama dgn yang page partials lain, maka bisa ke overwrite --}}
    disini  */
    </style>
@endpush

{{-- html  --}}
{{-- taruh tanpa perlu struktur html utuh --}}
{{-- jadi langsung taruh aja kayak bikin html langsung, gaperlu head dll --}}
{{-- contoh --}}
{{-- <div>
<h1>halo</h1>
</div> --}}


<h1 class="text-center text-4xl font-bold text-white">ticket</h1>


@push('scripts')
<script>
    // js taruh disini 
    // hati-hati, mungkin kalau nama variable kalian sama dgn yang page partials lain, maka bisa ke overwrite 
    // jadi kasih nama yg unik ya ges
    // disini
</script>
@endpush
