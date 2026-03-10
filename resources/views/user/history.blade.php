@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <h2 class="mb-4">Riwayat Pembelian Tiket</h2>

        @if ($transactions->isEmpty())
            <div class="alert alert-info">
                Anda belum memiliki transaksi yang berhasil. Yuk, beli tiket PIFF/PCE sekarang!
            </div>
        @else
            <div class="row">
                @foreach ($transactions as $transaction)
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm border-primary">
                            
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Invoice: {{ $transaction->invoice_code }}</h6>
                                <span class="badge bg-success">Lunas</span>
                            </div>

                            <div class="card-body">
                                <p class="text-muted small mb-2">Tanggal Beli: {{ $transaction->created_at->format('d M Y, H:i') }}</p>
                                
                                <strong>Detail Tiket:</strong>
                                <ul>
                                    @foreach($transaction->tickets as $ticket)
                                        <li>1x {{ $ticket->ticketCategory->name }} 
                                            <span class="text-muted small">({{ $ticket->ticket_code }})</span>
                                        </li>
                                    @endforeach
                                </ul>
                                
                                <hr>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 text-primary">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</h5>
                                    
                                    <a href="{{ route('ticket.download', $transaction->invoice_code) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-file-pdf"></i> Download E-Ticket
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection