@extends(auth()->user()->role == 'pengelola' ? 'layouts.admin' : 'layouts.alumni')

@section('title', 'Laman Diskusi')

@section('content')
<div class="row p-0 m-0" style="margin: -1.5rem !important;">
    <div class="col-12 p-0">
        
        <div class="chat-wrapper">

            <div class="chat-overlay" id="chatOverlay"></div>

            {{-- SIDEBAR --}}
            <div class="chat-sidebar" id="chatSidebar">
                <div class="chat-sidebar-header text-center">LAMAN DISKUSI</div>

                <div class="room-list">
                    <div class="room-item active"
                        onclick="Chat.changeRoom(null, 'Global Chat', this)">
                        Global-Chat
                    </div>

                    <div class="room-category-label mt-3">Room Angkatan</div>

                    @if(auth()->user()->role == 'pengelola')
                        @foreach($angkatanList as $angkatan)
                            <div class="room-item"
                                onclick="Chat.changeRoom({{ $angkatan->id }}, '{{ $angkatan->jenjang }} {{ $angkatan->tahun_masuk }} - {{ $angkatan->nama_angkatan }}', this)">
                                {{$angkatan->jenjang}}-{{ $angkatan->tahun_masuk }}-{{ $angkatan->nama_angkatan }}
                            </div>
                        @endforeach
                    @elseif(auth()->user()->profile)
                        {{-- Tampilkan Angkatan 1 --}}
                        @php $a1 = auth()->user()->profile->angkatan; @endphp
                        @if(auth()->user()->profile->angkatan_id)
                            <div class="room-item" 
                            onclick="Chat.changeRoom({{ $a1->id }}, '{{ $a1->jenjang }} {{ $a1->tahun_masuk }} - {{ $a1->nama_angkatan }}', this)">
                                {{ $a1->jenjang }} {{ $a1->tahun_masuk }} - {{$a1->nama_angkatan }}
                            </div>
                        @endif

                        {{-- Tampilkan Angkatan 2 --}}
                        @php $a2 = auth()->user()->profile->angkatan2; @endphp
                        @if(auth()->user()->profile->angkatan2_id)
                            <div class="room-item" 
                            onclick="Chat.changeRoom({{ $a2->id }}, '{{ $a2->jenjang }} {{ $a2->tahun_masuk }} - {{ $a2->nama_angkatan }}', this)">
                                {{ $a2->jenjang }} {{ $a2->tahun_masuk }} - {{$a2->nama_angkatan }}
                            </div>
                        @endif

                        {{-- Tampilkan Angkatan 3 --}}
                        @php $a3 = auth()->user()->profile->angkatan3; @endphp
                        @if(auth()->user()->profile->angkatan3_id)
                            <div class="room-item" 
                            onclick="Chat.changeRoom({{ $a3->id }}, '{{ $a3->jenjang }} {{ $a3->tahun_masuk }} - {{ $a3->nama_angkatan }}', this)">
                                {{ $a3->jenjang }} {{ $a3->tahun_masuk }} - {{$a3->nama_angkatan }}
                            </div>
                        @endif
                    
                    @else
                        <div class="px-3 mt-2 text-muted small font-italic">
                            <i class="mdi mdi-information-outline"></i> Lengkapi profil untuk akses.
                        </div>
                    @endif
                </div>
            </div>

            {{-- MAIN --}}
            <div class="chat-main">
                <div class="chat-header">
                    <div class="chat-header-title">
                        <button class="mobile-toggle-btn" onclick="Chat.toggleSidebar()">
                            <i class="mdi mdi-menu"></i>
                        </button>
                        <span id="room-title">Global Chat</span>
                    </div>
                </div>

                <div class="chat-messages" id="chat-box">
                    <div class="d-flex justify-content-center align-items-center h-100 text-muted">
                        <div class="spinner-border text-primary mr-3"></div>
                        <span>Memuat percakapan...</span>
                    </div>
                </div>

                {{-- INPUT --}}
                <div class="chat-input-area">
                    <div class="chat-input-wrapper shadow-sm">
                        <textarea id="chat-input"></textarea>
                        <button class="btn-chat-action btn-send-discord" onclick="Chat.send()">
                            <i class="mdi mdi-send"></i>
                        </button>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
window.ChatConfig = {
    fetchUrl: "{{ route('chat.fetch') }}",
    sendUrl: "{{ route('chat.send') }}",
    csrf: "{{ csrf_token() }}",
    currentUserId: {{ auth()->id() }},
    role: "{{ auth()->user()->role }}",
    jabatanId: {{ auth()->user()->profile->jabatan_angkatan_id ?? 'null' }},
    isKetua: {{ (auth()->user()->role == 'ketua_alumni' || auth()->user()->role == 'ketua_angkatan') ? 'true' : 'false' }}
};
</script>

<script src="{{ asset('js/chat.js') }}"></script>
@endpush
