<x-guest-layout>
    <div class="min-h-screen bg-slate-100 lg:grid lg:grid-cols-2">
        <section class="relative hidden overflow-hidden bg-slate-950 px-12 py-14 text-white lg:flex lg:flex-col lg:justify-between xl:px-20">
            <div class="absolute -right-24 -top-24 h-80 w-80 rounded-full bg-amber-400/10 blur-3xl"></div>
            <div class="absolute -bottom-28 -left-20 h-96 w-96 rounded-full bg-sky-400/10 blur-3xl"></div>
 
            <a href="{{ url('/') }}" class="relative z-10 inline-flex items-center gap-3">
                <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-400 text-xl font-black text-slate-950">A</span>
                <span>
                    <span class="block text-lg font-bold tracking-[0.18em] text-amber-300">ANSAR TRAVEL</span>
                    <span class="text-xs tracking-widest text-slate-300">MANAGEMENT SYSTEM</span>
                </span>
            </a>
 
            <div class="relative z-10 max-w-xl">
                <p class="mb-4 text-sm font-semibold uppercase tracking-[0.25em] text-amber-300">Assalamu’alaikum</p>
                <h1 class="text-4xl font-bold leading-tight xl:text-5xl">
                    Perjalanan ibadah lebih terarah, pelayanan lebih mudah.
                </h1>
                <p class="mt-6 max-w-lg text-base leading-7 text-slate-300">
                    Sistem informasi Ansar Travel membantu pengelolaan jamaah,
                    dokumen, pembayaran, dan persiapan keberangkatan dalam satu tempat.
                </p>
            </div>
 
            <p class="relative z-10 text-xs text-slate-400">
                © {{ date('Y') }} Ansar Travel. Sistem Monitoring Jamaah Umroh.
            </p>
        </section>
 
        <section class="flex min-h-screen items-center justify-center px-5 py-10 sm:px-8">
            <div class="w-full max-w-md">
                <div class="mb-8 lg:hidden">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-3">
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-400 text-xl font-black text-slate-950">A</span>
                        <span>
                            <span class="block font-bold tracking-widest text-slate-900">ANSAR TRAVEL</span>
                            <span class="text-xs tracking-wider text-slate-500">MANAGEMENT SYSTEM</span>
                        </span>
                    </a>
                </div>
 
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-900/5 sm:p-9">
                    <div class="mb-8">
                        <p class="text-sm font-semibold text-amber-600">Selamat datang kembali</p>
                        <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">Masuk ke akun</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Silakan masuk untuk melanjutkan ke sistem Ansar Travel.
                        </p>
                    </div>
 
                    <x-auth-session-status class="mb-5" :status="session('status')" />
 
                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf
 
                        <div>
                            <label for="email" class="mb-2 block text-sm font-medium text-slate-700">Email</label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="nama@email.com"
                                class="block w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/15 @error('email') border-red-500 focus:border-red-500 focus:ring-red-500/10 @enderror"
                            >
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
 
                        <div>
                            <div class="mb-2 flex items-center justify-between gap-3">
                                <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-xs font-semibold text-amber-700 hover:text-amber-800 hover:underline">
                                        Lupa password?
                                    </a>
                                @endif
                            </div>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                required
                                autocomplete="current-password"
                                placeholder="Masukkan password"
                                class="block w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/15 @error('password') border-red-500 focus:border-red-500 focus:ring-red-500/10 @enderror"
                            >
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
 
                        <label for="remember_me" class="flex cursor-pointer items-center gap-2 text-sm text-slate-600">
                            <input
                                id="remember_me"
                                name="remember"
                                type="checkbox"
                                class="h-4 w-4 rounded border-slate-300 text-amber-600 focus:ring-amber-500"
                            >
                            Ingat saya
                        </label>
 
                        <button
                            type="submit"
                            class="flex w-full items-center justify-center rounded-xl bg-slate-950 px-4 py-3.5 text-sm font-semibold text-white shadow-lg shadow-slate-900/10 transition hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-amber-500/30"
                        >
                            Masuk ke Sistem
                        </button>
                    </form>
                </div>
 
                <p class="mt-6 text-center text-xs leading-5 text-slate-500">
                    Dengan masuk, Anda menyetujui penggunaan sistem internal Ansar Travel.
                </p>
            </div>
        </section>
    </div>
</x-guest-layout>
