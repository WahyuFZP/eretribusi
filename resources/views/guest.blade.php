@extends('layouts.landing')

@section('page-title', 'Home')

@section('content')
    <header class="bg-zinc-100 text-gray-800 p-1 shadow-sm sticky top-0 z-50">
        <nav class="container mx-auto max-w-6xl flex items-center justify-between py-4">
            <a href="#" class="flex items-center gap-3">
                <img src="{{ asset('images/dlh.png') }}" alt="{{ config('app.name', 'E-Retribusi') }} logo"
                    class="h-8 w-auto" />
                <span class="font-bold text-xl text-gray-800 hidden sm:inline">{{ config('app.name', 'E-Retribusi') }}</span>
            </a>
            <ul id="main-nav" class="hidden md:flex gap-6 items-center">
                <li><a href="#home" class="text-gray-800 hover:opacity-90">Home</a></li>
                <li><a href="#about" class="text-gray-800 hover:opacity-90">About</a></li>
                <li><a href="#faq" class="text-gray-800 hover:opacity-90">Faq</a></li>
                <li><a href="#services" class="text-gray-800 hover:opacity-90">Services</a></li>
                <li><a href="#contact" class="text-gray-800 hover:opacity-90">Contact</a></li>
            </ul>

            <div class="flex items-center gap-2">
                @guest
                    <a href="{{ route('login') }}"
                        class="px-4 py-2 rounded border border-white/20 text-gray-800 hover:bg-white/10">Login</a>
                    <a href="{{ route('register') }}"
                        class="px-4 py-2 rounded bg-[#0b4b3f] text-white font-medium ml-2 hover:bg-white/10">Register</a>
                @else
                    <a href="{{ route('dashboard') }}"
                        class="px-4 py-2 rounded border border-white/20 text-white hover:bg-white/10">Dashboard</a>
                @endguest

                {{-- Mobile Toggle --}}
                <button class="md:hidden ml-2 p-2 rounded bg-white/10 hover:bg-white/20 text-white"
                    onclick="document.getElementById('main-nav').classList.toggle('hidden')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </nav>

    </header>

    <section id="home" class="bg-white text-gray-800 py-20">
        <div class="container mx-auto flex flex-col lg:flex-row items-center gap-8" data-aos="fade-up" data-aos-delay="100" >
            <div class="flex-1">
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4">Bebas Bayar — Kelola Tagihan dan Retribusi
                    dengan Mudah</h1>
                <p class="text-lg md:text-xl mb-6 text-gray-600">Sistem sederhana untuk mencatat, menagih, dan melaporkan
                    retribusi publik. Cepat, aman, dan mudah diintegrasikan ke alur kerja Anda.</p>

                <div class="flex gap-4 mb-6">
                    <a href="{{ route('register') }}" class="btn bg-[#0b4b3f] btn-lg text-white">Get Started Now</a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">
                    <div class="flex items-start gap-3">
                        <div
                            class="w-10 h-10 rounded-full bg-[#FDFDFC] dark:bg-[#161615] flex items-center justify-center border border-[#e3e3e0]">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="text-[#F53003]">
                                <path d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div>
                            <div class="font-medium">Kelola Tagihan</div>
                            <div class="text-sm text-gray-600">Buat dan kirim tagihan dalam hitungan menit.</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div
                            class="w-10 h-10 rounded-full bg-[#FDFDFC] dark:bg-[#161615] flex items-center justify-center border border-[#e3e3e0]">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="text-[#F53003]">
                                <path d="M12 1v22" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M5 8h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div>
                            <div class="font-medium">Laporan & Statistik</div>
                            <div class="text-sm text-gray-600">Grafik dan ekspor data untuk audit.</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div
                            class="w-10 h-10 rounded-full bg-[#FDFDFC] dark:bg-[#161615] flex items-center justify-center border border-[#e3e3e0]">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="text-[#F53003]">
                                <path d="M3 12h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M3 6h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div>
                            <div class="font-medium">Aman & Terpercaya</div>
                            <div class="text-sm text-gray-600">Hak akses terkontrol dan log aktivitas.</div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="p-4 bg-gray-50 dark:bg-[#1D0002] rounded-sm text-center">
                        <div class="text-sm text-gray-600">Pengguna</div>
                        <div class="font-medium text-xl">1.2k+</div>
                    </div>
                    <div class="p-4 bg-white dark:bg-[#161615] rounded-sm text-center border border-[#e3e3e0]">
                        <div class="text-sm text-gray-600">Keandalan</div>
                        <div class="font-medium text-xl">99.9%</div>
                    </div>
                    <div class="p-4 bg-white dark:bg-[#161615] rounded-sm text-center border border-[#e3e3e0]">
                        <div class="text-sm text-gray-600">Hemat Waktu</div>
                        <div class="font-medium text-xl">~30%</div>
                    </div>
                </div>
            </div>

            <div class="flex-1">
                <!-- Illustrative image (use plain background so photo colors are preserved) -->
                <div class="w-full h-64 lg:h-80 bg-white rounded-lg overflow-hidden flex items-center justify-center">
                    <img src="{{ asset('images/handphone.jpg') }}" alt="Ilustrasi pembayaran"
                        class="max-w-[560px] w-full h-auto object-contain" loading="lazy" width="560"
                        height="320" />
                </div>
            </div>
        </div>
    </section>

    {{-- Section Untuk About --}}
    <section id="about" class="py-20">
        <div class="container mx-auto px-4">
            <div class="bg-white rounded-2xl shadow-lg p-8 lg:p-12 mb-8" data-aos="fade-right" data-aos-delay="300">
                <h2 id="about-heading" class="text-sm text-gray-800 font-semibold text-center md:text-left">About Us</h2>
                <h3 class="mt-2 text-2xl lg:text-3xl font-bold text-gray-900 text-center md:text-left">Experience that
                    grows with your scale.</h3>
                <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Card normal 1 -->
                    <article
                        class="border border-[#e9e6e4] rounded-2xl p-6 bg-white shadow-sm hover:shadow-md transition-shadow focus:outline-none focus:ring-2 focus:ring-[#F53003]/20">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 rounded-full bg-[#FDFDFC] flex items-center justify-center border border-[#eee]">
                                <!-- icon (SVG) -->
                                <svg class="w-5 h-5 text-[#F53003]" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="mt-1 font-medium text-gray-900">Pembayaran Praktis</h4>
                                <p class="text-sm text-gray-600 mt-1">"Lakukan pembayaran retribusi sampah 24/7 melalui
                                    berbagai metode pembayaran digital tanpa perlu datang ke kantor."</p>
                            </div>
                        </div>
                    </article>
                    <!-- Card normal 2 -->
                    <article
                        class="border border-[#e9e6e4] rounded-2xl p-6 bg-white shadow-sm hover:shadow-md transition-shadow focus:outline-none focus:ring-2 focus:ring-[#F53003]/20">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 rounded-full bg-[#FDFDFC] flex items-center justify-center border border-[#eee]">
                                <!-- icon -->
                            </div>
                            <div>
                                <h4 class="mt-1 font-medium text-gray-900">Transparan & Aman</h4>
                                <p class="text-sm text-gray-600 mt-1">"Setiap pembayaran tercatat secara otomatis dengan
                                    bukti digital. Sistem terintegrasi dengan payment gateway terpercaya."</p>
                            </div>
                        </div>
                    </article>
                    <!-- Highlight / Featured card -->
                    <article
                        class="relative overflow-hidden rounded-2xl rounded-tr-[56px] p-6 bg-[#f5f3e8] shadow-md border border-transparent">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-[#e9e7db] flex items-center justify-center">
                                <!-- icon -->
                            </div>
                            <div>
                                <h4 class="mt-1 font-medium text-gray-900">Efisiensi Waktu</h4>
                                <p class="text-sm text-gray-700 mt-1">"Konfirmasi pembayaran otomatis dalam hitungan detik.
                                    Tidak perlu menunggu verifikasi manual."</p>
                            </div>
                        </div>

                        <!-- optional circle CTA like in ref -->
                        <a href="#"
                            class="absolute bottom-6 right-6 inline-flex items-center justify-center w-10 h-10 bg-[#0b4b3f] text-white rounded-full shadow-sm hover:scale-105 transition-transform"
                            aria-label="Learn more">
                            <!-- arrow icon -->
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                    </article>
                </div>
            </div>
        </div>
    </section>

    {{-- Section Untuk Services (langkah penggunaan & pembayaran) --}}
    <section id="faq" class="py-20 bg-[#0b4b3f]" >
            <div class="container mx-auto">
            <article data-aos="fade-down">
            <div class="max-w-3xl mx-auto text-center mb-12" data-aos="fade-down" data-aos-delay="300">
                <h2 class="text-sm text-[#0b4b3f] font-semibold">How it works</h2>
                <h3 class="mt-2 text-2xl lg:text-3xl font-bold text-white">Langkah-langkah pembayaran dan penggunaan</h3>
                <p class="mt-3 text-white/60">Ikuti langkah sederhana ini untuk melakukan pembayaran retribusi dan
                    mengelola tagihan dengan mudah.</p>
            </div>
            </article>
            

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <article class="p-6 border border-[#e9e6e4] rounded-2xl bg-white shadow-sm text-center" data-aos="fade-up" data-aos-delay="400">
                    <div class="mx-auto w-14 h-14 rounded-full bg-[#f5f3e8] flex items-center justify-center mb-4">
                        <span class="text-lg font-semibold text-[#0b4b3f]">1</span>
                    </div>
                    <h4 class="font-medium text-gray-900">Daftar / Masuk</h4>
                    <p class="text-sm text-gray-600 mt-2">Buat akun atau masuk untuk mengakses dashboard pembayaran dan
                        histori tagihan.</p>
                </article>

                <article class="p-6 border border-[#e9e6e4] rounded-2xl bg-white shadow-sm text-center" data-aos="fade-up" data-aos-delay="500">
                    <div class="mx-auto w-14 h-14 rounded-full bg-[#f5f3e8] flex items-center justify-center mb-4">
                        <span class="text-lg font-semibold text-[#0b4b3f]">2</span>
                    </div>
                    <h4 class="font-medium text-gray-900">Pilih Tagihan</h4>
                    <p class="text-sm text-gray-600 mt-2">Pilih tagihan yang ingin dibayar dari daftar atau buat tagihan
                        baru untuk lokasi/obyek terkait.</p>
                </article>

                <article class="p-6 border border-[#e9e6e4] rounded-2xl bg-white shadow-sm text-center" data-aos="fade-up" data-aos-delay="600">
                    <div class="mx-auto w-14 h-14 rounded-full bg-[#f5f3e8] flex items-center justify-center mb-4">
                        <span class="text-lg font-semibold text-[#0b4b3f]">3</span>
                    </div>
                    <h4 class="font-medium text-gray-900">Bayar & Konfirmasi</h4>
                    <p class="text-sm text-gray-600 mt-2">Bayar melalui metode yang tersedia (VA, QRIS, kartu) dan terima
                        bukti digital otomatis.</p>
                </article>
            </div>
        </div>
    </section>

    {{-- Service / Layanan Retribusi --}}
    <section id="services" class="py-20">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start" data-aos="fade-up" data-aos-delay="400">
                <!-- Left: list of services -->
                <div>
                    <h2 class="text-sm text-gray-600 font-semibold">Layanan Retribusi</h2>
                    <h3 class="mt-2 text-2xl lg:text-3xl font-bold text-gray-900">Layanan yang kami sediakan</h3>
                    <p class="mt-3 text-gray-600">Berikut beberapa layanan terkait retribusi publik yang tersedia melalui sistem ini. Klik setiap layanan untuk melihat detail dan tarifnya.</p>

                    <div class="mt-6 space-y-4">
                        <article class="p-4 border border-[#e9e6e4] rounded-xl bg-white shadow-sm hover:shadow-md">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium">Retribusi Sampah</h4>
                                    <p class="text-sm text-gray-600">Penetapan dan penagihan retribusi sampah untuk rumah tangga dan usaha.</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-gray-500">Mulai dari</div>
                                    <div class="font-semibold">Rp 500</div>
                                </div>
                            </div>
                        </article>

                        <article class="p-4 border border-[#e9e6e4] rounded-xl bg-white shadow-sm hover:shadow-md">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium">Retribusi Parkir</h4>
                                    <p class="text-sm text-gray-600">Penetapan tarif parkir per jam atau per hari untuk lokasi komersial.</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-gray-500">Mulai dari</div>
                                    <div class="font-semibold">Rp 2.000 / jam</div>
                                </div>
                            </div>
                        </article>

                        <article class="p-4 border border-[#e9e6e4] rounded-xl bg-white shadow-sm hover:shadow-md">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium">Retribusi Kebersihan</h4>
                                    <p class="text-sm text-gray-600">Layanan kebersihan dan pemeliharaan kawasan dengan struktur tarif khusus.</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-gray-500">Mulai dari</div>
                                    <div class="font-semibold">Rp 10.000</div>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>

                <!-- Right: poster / harga box -->
                <aside class="order-first lg:order-last">
                    <div class="sticky top-24">
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-[#e9e6e4]" data-aos="fade-left" data-aos-delay="500">
                            <div class="p-4">
                                <h4 class="font-medium text-gray-900">Poster Tarif Retribusi</h4>
                                <p class="text-sm text-gray-600 mt-1">Lihat ringkasan tarif retribusi dalam satu poster.</p>
                            </div>
                            <div class="w-full bg-gray-50 flex items-center justify-center p-6">
                                <div class="w-full h-[420px] md:h-[560px] bg-white overflow-hidden rounded">
                                    <object data="{{ asset('documents/perda_kotamadiun.pdf') }}" type="application/pdf" class="w-full h-full">
                                        <iframe src="{{ asset('documents/perda_kotamadiun.pdf') }}" class="w-full h-full" frameborder="0"></iframe>
                                        <div class="p-4 text-sm text-gray-600">
                                            Browser Anda tidak mendukung tampilan PDF. <a href="{{ asset('documents/perda_kotamadiun.pdf') }}" target="_blank" rel="noopener">Buka di tab baru</a> atau <a href="{{ asset('documents/perda_kotamadiun.pdf') }}" download>unduh</a> poster tarif.
                                        </div>
                                    </object>
                                </div>
                            </div>
                            <div class="p-4 flex items-center gap-2">
                                <a href="{{ asset('documents/perda_kotamadiun.pdf') }}" target="_blank" class="btn btn-outline btn-sm">Lihat ukuran penuh</a>
                                <a href="{{ asset('documents/perda_kotamadiun.pdf') }}" download class="btn btn-primary btn-sm">Unduh Poster</a>
                            </div>
                        </div>
                        <div class="mt-4 text-xs text-gray-500">Catatan: Jika poster tidak muncul, pastikan PDF tersedia di <code>public/documents/perda_kotamadiun.pdf</code> atau unggah poster ke <code>public/images/retribusi-poster.jpg</code> sebagai fallback.</div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
    
    {{-- Footer --}}
    <footer id="contact" class="footer sm:footer-horizontal bg-base-200 text-base-content p-10 bg-zinc-100">
        <aside>
            <img src="{{ asset('images/dlh.png') }}" alt="{{ config('app.name', 'E-Retribusi') }} logo"
                class="h-8 w-auto" />
            <p>
                Dinas Lingkungan Hidup Kota Madiun.
                <br />
                Jl. Salak III No.7a, Taman, Kec. Taman, Kota Madiun
                <br />
                Jawa Timur 63131
                <br />
                0351 - 468876
            </p>
        </aside>
        <nav>
            <h6 class="footer-title">Services</h6>
            <a class="link link-hover">Branding</a>
            <a class="link link-hover">Design</a>
            <a class="link link-hover">Marketing</a>
            <a class="link link-hover">Advertisement</a>
        </nav>
        <nav>
            <h6 class="footer-title">Company</h6>
            <a class="link link-hover">About us</a>
            <a class="link link-hover">Contact</a>
            <a class="link link-hover">Jobs</a>
            <a class="link link-hover">Press kit</a>
        </nav>
        <nav>
            <h6 class="footer-title">Legal</h6>
            <a class="link link-hover">Terms of use</a>
            <a class="link link-hover">Privacy policy</a>
            <a class="link link-hover">Cookie policy</a>
        </nav>
        <nav>
            <h6 class="footer-title">Social</h6>
            <div class="grid grid-flow-col gap-4">
                <a>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        class="fill-current">
                        <path
                            d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z">
                        </path>
                    </svg>
                </a>
                <a>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        class="fill-current">
                        <path
                            d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z">
                        </path>
                    </svg>
                </a>
                <a>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        class="fill-current">
                        <path
                            d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z">
                        </path>
                    </svg>
                </a>
            </div>
        </nav>
    </footer>

@endsection
