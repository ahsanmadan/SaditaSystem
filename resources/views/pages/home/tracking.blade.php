@extends('layouts.app')

@section('content')
    <section class="bg-[#FFFDFB] py-16 sm:py-20">
        <div class="mx-auto max-w-4xl px-5 sm:px-6 lg:px-8">
            <div class="rounded-[2rem] border border-[#eadfd4] bg-white shadow-[0_20px_50px_rgba(80,44,33,0.06)]">
                <div class="border-b border-[#f1e7de] px-6 py-6 sm:px-8">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <div class="text-[11px] font-semibold uppercase tracking-[0.22em] text-[#7A1F2B]/70">Tracking</div>
                            <h1 class="mt-2 text-3xl font-bold text-[#2D1E1E] sm:text-4xl">Lacak pesanan</h1>
                            <p class="mt-2 text-sm text-[#75645d]">Masukkan kode pesanan untuk cek progres dan lanjut bayar.</p>
                        </div>
                        <a href="{{ route('home') }}#lacak"
                            class="inline-flex items-center justify-center rounded-full border border-[#ead1c9] px-5 py-2.5 text-sm font-semibold text-[#7A1F2B] transition hover:bg-[#fbf4ef]">
                            Kembali
                        </a>
                    </div>

                    <form method="GET" action="{{ route('tracking.page') }}" class="mt-6 flex flex-col gap-3 sm:flex-row">
                        <input
                            type="text"
                            name="code"
                            value="{{ $trackingCode }}"
                            placeholder="Contoh: SDT-20260411-001"
                            class="w-full rounded-full border border-[#eadfd4] bg-[#fffdfa] px-5 py-3 text-sm text-[#2D1E1E] outline-none transition focus:border-[#c9a27a]"
                        >
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-full bg-[#7A1F2B] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[#65202a]">
                            Cek pesanan
                        </button>
                    </form>
                </div>

                <div class="px-6 py-6 sm:px-8 sm:py-8">
                    @if ($trackingCode !== '' && ! $trackingData)
                        <div class="rounded-[1.6rem] border border-dashed border-[#eadfd4] bg-[#fffaf6] px-6 py-12 text-center">
                            <h2 class="text-lg font-semibold text-[#2D1E1E]">Kode tidak ditemukan</h2>
                            <p class="mt-2 text-sm text-[#7b6963]">Pastikan kode pesanan benar, lalu coba lagi.</p>
                        </div>
                    @elseif ($trackingData)
                        <div class="grid gap-6 lg:grid-cols-[minmax(0,1.15fr)_minmax(0,0.85fr)]">
                            <div class="space-y-6">
                                <div class="rounded-[1.6rem] border border-[#f0e4d8] bg-[#fffdfa] p-5">
                                    <div class="flex flex-wrap items-start justify-between gap-4">
                                        <div>
                                            <div class="text-xs font-medium uppercase tracking-[0.18em] text-[#7A1F2B]/65">Kode pesanan</div>
                                            <div class="mt-2 font-mono text-sm font-semibold text-[#2D1E1E]">{{ $trackingData['order_id'] }}</div>
                                        </div>
                                        <span class="inline-flex items-center rounded-full border border-[#ead1c9] bg-[#fbf4ef] px-3 py-1 text-xs font-semibold text-[#7A1F2B]">
                                            {{ $trackingData['status_label'] }}
                                        </span>
                                    </div>

                                    <dl class="mt-5 grid gap-4 sm:grid-cols-2">
                                        <div>
                                            <dt class="text-xs text-[#8a7770]">Produk</dt>
                                            <dd class="mt-1 text-sm font-medium text-[#2D1E1E]">{{ $trackingData['product_name'] }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-xs text-[#8a7770]">Total</dt>
                                            <dd class="mt-1 text-sm font-medium text-[#2D1E1E]">{{ $trackingData['total'] }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-xs text-[#8a7770]">Pembayaran</dt>
                                            <dd class="mt-1 text-sm font-medium text-[#2D1E1E]">{{ $trackingData['payment_status'] }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-xs text-[#8a7770]">Metode</dt>
                                            <dd class="mt-1 text-sm font-medium text-[#2D1E1E]">{{ $trackingData['payment_method'] }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-xs text-[#8a7770]">Tanggal kirim</dt>
                                            <dd class="mt-1 text-sm font-medium text-[#2D1E1E]">{{ $trackingData['delivery_date'] }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-xs text-[#8a7770]">Jam kirim</dt>
                                            <dd class="mt-1 text-sm font-medium text-[#2D1E1E]">{{ $trackingData['delivery_time'] }}</dd>
                                        </div>
                                    </dl>
                                </div>

                                <div class="rounded-[1.6rem] border border-[#f0e4d8] bg-white p-5">
                                    <div class="text-xs font-semibold uppercase tracking-[0.18em] text-[#7A1F2B]">Progres</div>
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        @foreach ($trackingData['timeline'] as $step)
                                            @php
                                                $classes = $step['done']
                                                    ? 'border-[#d8ead2] bg-[#eef8e9] text-[#416936]'
                                                    : (!empty($step['active'])
                                                        ? 'border-[#ead1c9] bg-[#f9efea] text-[#7A1F2B]'
                                                        : 'border-gray-200 bg-gray-50 text-gray-500');
                                            @endphp
                                            <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-medium {{ $classes }}">
                                                {{ $step['label'] }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div class="rounded-[1.6rem] border border-[#f0e4d8] bg-[#fffdfa] p-5">
                                    <div class="text-xs font-semibold uppercase tracking-[0.18em] text-[#7A1F2B]/70">Aksi cepat</div>
                                    <div class="mt-4 flex flex-col gap-3">
                                        <a href="{{ $trackingData['invoice_url'] }}"
                                            class="inline-flex items-center justify-center rounded-full px-5 py-3 text-sm font-semibold transition {{ $trackingData['can_continue_payment'] ? 'bg-[#7A1F2B] text-white hover:bg-[#65202a]' : 'border border-[#ead1c9] bg-white text-[#7A1F2B] hover:bg-[#fbf4ef]' }}">
                                            {{ $trackingData['can_continue_payment'] ? 'Lanjut bayar' : 'Buka invoice' }}
                                        </a>
                                        @if ($trackingData['deadline'])
                                            <div class="rounded-[1.2rem] bg-[#fbf4ef] px-4 py-3 text-sm text-[#6b4d49]">
                                                Batas bayar: <span class="font-semibold">{{ $trackingData['deadline'] }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="rounded-[1.6rem] border border-[#f0e4d8] bg-white p-5">
                                    <div class="text-xs font-semibold uppercase tracking-[0.18em] text-[#7A1F2B]/70">Butuh bantuan?</div>
                                    <p class="mt-3 text-sm leading-relaxed text-[#75645d]">
                                        Jika ada perubahan alamat, jadwal, atau pembayaran belum masuk, hubungi admin Sadita lewat WhatsApp.
                                    </p>
                                    <a
                                        href="https://wa.me/6289653090248?text={{ rawurlencode('Halo Sadita, saya ingin bantu cek pesanan dengan kode '.$trackingData['order_id']) }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="mt-4 inline-flex items-center justify-center rounded-full border border-[#ead1c9] px-5 py-3 text-sm font-semibold text-[#7A1F2B] transition hover:bg-[#fbf4ef]">
                                        Hubungi admin
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="rounded-[1.6rem] border border-dashed border-[#eadfd4] bg-[#fffaf6] px-6 py-12 text-center">
                            <h2 class="text-lg font-semibold text-[#2D1E1E]">Masukkan kode pesanan</h2>
                            <p class="mt-2 text-sm text-[#7b6963]">Setelah kode diisi, status pesanan dan tombol lanjut bayar akan muncul di sini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
