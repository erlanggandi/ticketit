@extends('layouts.public')

@section('title', 'Login Administrator')

@section('content')
    <div class="mx-auto mt-4 w-full max-w-md">
        <x-card :padded="false">
            <div class="kop">
                <span>AKSES TERBATAS</span>
                <span>Petugas IT</span>
            </div>

            <div class="p-6 sm:p-7">
                <h2 class="display text-xl">Masuk Meja Penanganan</h2>
                <p class="mt-1.5 text-sm leading-relaxed text-muted">Area khusus tim IT — login dengan akun administrator.</p>

                @if (session('status'))
                    <div class="mt-5"><x-alert type="success">{{ session('status') }}</x-alert></div>
                @endif

                <form method="POST" action="{{ route('admin.login.attempt') }}" data-disable-on-submit class="mt-6 space-y-5">
                    @csrf

                    <x-input name="email" type="email" label="01 · Email" :required="true"
                             placeholder="admin@company.co.id" value="{{ old('email') }}"
                             autofocus autocomplete="username" />

                    <x-input name="password" type="password" label="02 · Password" :required="true"
                             placeholder="••••••••" autocomplete="current-password" />

                    <label class="flex items-center gap-2.5 text-sm text-muted">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}
                            class="h-4 w-4 rounded-[3px] border-ink/40 accent-stamp-600" />
                        Ingat saya
                    </label>

                    <x-button type="submit" class="w-full" size="lg">Login</x-button>
                </form>
            </div>
        </x-card>
    </div>
@endsection
