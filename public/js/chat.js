class ChatHandler {

    constructor() {
        this.roomId = null;
        this.polling = null;
        this.page = 1;         // Halaman saat ini
        this.lastPage = 1;     // Total halaman
        this.isLoadingOld = false; // Status loading pesan lama

        this.cfg = window.ChatConfig;
        this.chatBox = document.getElementById("chat-box");

        this.initEmoji();
        this.initAutoLoad();
        this.initOverlayClose();
    }

    // ---------------- EMOJI ----------------
    initEmoji() {
        $("#chat-input").emojioneArea({
            pickerPosition: "top", tones: false, inline: true,
            placeholder: "Kirim pesan di Global-Chat...",
            events: {
                keyup: (editor, e) => {
                    if (e.which === 13 && !e.shiftKey) {
                        e.preventDefault();
                        this.send();
                    }
                },
                ready: function() { this.setFocus(); }
            }
        });
        this.emoji = $("#chat-input").data("emojioneArea");
    }

    // ---------------- ROOM SWITCH ----------------
    changeRoom(id, name, el) {
        $(".room-item").removeClass("active");
        $(el).addClass("active");

        this.roomId = id;
        this.page = 1; // Reset ke halaman 1 tiap ganti room
        $("#room-title").text(name);

        this.setPlaceholder(name);
        
        // Load halaman 1 (scroll ke bawah mentok)
        this.load(false, 1, true); 

        if (window.innerWidth <= 768) this.toggleSidebar();
    }

    setPlaceholder(roomName) {
        if (this.emoji) {
            this.emoji.editor.attr("placeholder", "Kirim pesan di " + roomName + "...");
            this.emoji.setFocus();
        }
    }

    // ---------------- SIDEBAR ----------------
    toggleSidebar() {
        $("#chatSidebar").toggleClass("show");
        $("#chatOverlay").toggleClass("show");
    }

    initOverlayClose() {
        document.getElementById("chatOverlay").onclick = () => this.toggleSidebar();
    }

    // ---------------- AUTO LOAD ----------------
    initAutoLoad() {
        // Load awal (Global Chat)
        this.load(false, 1, true);
        
        // Polling tiap 3 detik (Hanya ambil halaman 1 agar update pesan baru masuk)
        this.polling = setInterval(() => {
            // Jangan polling kalau user sedang buka pesan lama (halaman > 1)
            // agar tidak loncat-loncat
            if (this.page === 1) {
                this.load(false, 1, false); 
            }
        }, 3000);
    }

    // ---------------- LOAD MESSAGES ----------------
    // isLoadMore: Apakah ini request tombol "Muat Pesan Lama"?
    // pageNum: Halaman berapa yang diminta
    // forceScrollBottom: Paksa scroll ke bawah (biasanya saat pertama buka room / kirim pesan)
    load(isLoadMore = false, pageNum = 1, forceScrollBottom = false) {
        
        $.get(this.cfg.fetchUrl, { room_id: this.roomId, page: pageNum }, (res) => {
            
            // Update info halaman
            this.lastPage = res.last_page;
            
            // Render pesan
            // Jika isLoadMore = true, kita prepend (taruh atas)
            // Jika false, kita replace (ganti semua / update realtime)
            this.render(res.data, isLoadMore, forceScrollBottom);
            
            this.isLoadingOld = false;
        });
    }

    loadMore() {
        if (this.page >= this.lastPage || this.isLoadingOld) return;

        this.isLoadingOld = true;
        this.page++; // Naikkan halaman
        
        // Simpan posisi scroll sebelum load
        let oldHeight = this.chatBox.scrollHeight;
        let oldTop = this.chatBox.scrollTop;

        // Panggil load
        this.load(true, this.page, false);
    }

    // ---------------- SEND MESSAGE ----------------
    send() {
        let message = this.emoji.getText().trim();
        if (!message) return;

        this.emoji.setText("");
        this.emoji.setFocus();

        $.post(this.cfg.sendUrl, {
            _token: this.cfg.csrf,
            room_id: this.roomId,
            message: message
        })
        .done(() => {
            this.page = 1; // Kembali ke halaman 1
            this.load(false, 1, true); // Reload & Scroll ke bawah
        })
        .fail((xhr) => {
            Swal.fire("Gagal!", xhr.responseJSON?.error || "Error server", "error");
        });
    }

    delete(id) {
        Swal.fire({
            title: "Hapus pesan ini?", icon: "warning", showCancelButton: true,
            confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', 
            confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal'
        }).then((x) => {
            if (!x.isConfirmed) return;
            $.ajax({
                url: "/chat/message/" + id, type: "DELETE", data: { _token: this.cfg.csrf },
                success: () => {
                    this.load(false, this.page, false);
                    Swal.fire("Terhapus", "", "success");
                }
            }).
            fail((xhr) => {
                Swal.fire("Gagal!", xhr.responseJSON?.error || "Error server", "error");
            });
        });
    }

    // ---------------- RENDER ----------------
    render(list, isLoadMore, forceScrollBottom) {
        
        // Jika data kosong
        if ((!list || list.length === 0) && !isLoadMore) {
            this.chatBox.innerHTML = `<div class="text-center mt-5 text-muted">Belum ada percakapan.</div>`;
            return;
        }

        let html = "";
        let lastSender = null;
        let lastTime = null;

        // Loop pesan
        list.forEach(msg => {
            if (!msg.sender) return;
            const sender = msg.sender;
            const isMe = sender.id === this.cfg.currentUserId;
            
            const avatar = sender.profile?.foto_profil_thumbnail ? "/storage/" + sender.profile.foto_profil_thumbnail : "/images/aset/usern.png";
            const name = sender.profile?.nama_lengkap ?? sender.username;
            const date = new Date(msg.created_at);
            const time = date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            const fullTime = date.toLocaleDateString([], {month:"short", day:"numeric"}) + " " + time;

            // Grouping Logic
            let isGroupStart = true;
            if (sender.id === lastSender && lastTime) {
                let diff = (date - lastTime) / 1000 / 60;
                if (diff < 5) isGroupStart = false;
            }
            lastSender = sender.id;
            lastTime = date;

            // Logic Hapus Pesan
            let canDelete = false;
            if (isMe) canDelete = true;
            else if (this.cfg.role === 'pengelola' || this.cfg.role === 'ketua_alumni') canDelete = true;
            else if (this.cfg.role === 'ketua_angkatan') {
                if (this.roomId && this.cfg.jabatanId && this.roomId == this.cfg.jabatanId) canDelete = true;
            }

            let delBtn = canDelete ? 
                `<div class="message-actions"><button class="btn btn-sm text-danger p-1" onclick="Chat.delete(${msg.id})"><i class="mdi mdi-trash-can-outline"></i></button></div>` : "";

            html += `
                <div class="discord-message ${isGroupStart ? "group-start" : "group-followup"}" data-timestamp="${time}">
                    <img src="${avatar}" class="message-avatar">
                    <div class="message-content-wrapper">
                        <div class="message-header">
                            <span class="message-username" style="${isMe ? "color: var(--chat-accent);" : ""}">${name}</span>
                            <span class="message-timestamp">${fullTime}</span>
                        </div>
                        <div class="message-text">${msg.message}</div>
                    </div>
                    ${delBtn}
                </div>`;
        });

        // --- TOMBOL LOAD MORE ---
        // Jika ini bukan load more (berarti load halaman 1), kita reset isi chatBox
        if (!isLoadMore) {
            let loadMoreBtn = "";
            // Jika masih ada halaman berikutnya, tampilkan tombol
            if (this.page < this.lastPage) {
                loadMoreBtn = `
                    <div class="text-center py-2" id="btn-load-wrapper">
                        <button class="btn btn-sm btn-light border shadow-sm" onclick="Chat.loadMore()">
                            <i class="mdi mdi-history"></i> Muat 500 Pesan Sebelumnya
                        </button>
                    </div>
                `;
            }
            this.chatBox.innerHTML = loadMoreBtn + html;
        } 
        else {
            // Jika ini LOAD MORE, kita sisipkan di bawah tombol load more tapi di atas pesan lama
            // Cari wrapper tombol load more
            let btnWrapper = document.getElementById("btn-load-wrapper");
            if (btnWrapper) {
                // Hapus tombol lama dulu sementara
                btnWrapper.remove(); 
            }
            
            // Masukkan HTML baru di paling atas (prepend)
            let currentHtml = this.chatBox.innerHTML;
            
            // Cek lagi apakah tombol perlu muncul lagi untuk halaman berikutnya
            let nextBtn = "";
            if (this.page < this.lastPage) {
                nextBtn = `
                    <div class="text-center py-2" id="btn-load-wrapper">
                        <button class="btn btn-sm btn-light border shadow-sm" onclick="Chat.loadMore()">
                            <i class="mdi mdi-history"></i> Muat 500 Pesan Sebelumnya
                        </button>
                    </div>
                `;
            }

            this.chatBox.innerHTML = nextBtn + html + currentHtml;
        }

        // --- LOGIKA SCROLL ---
        if (forceScrollBottom) {
            this.scrollToBottom();
        }
    }

    scrollToBottom() {
        this.chatBox.scrollTop = this.chatBox.scrollHeight;
    }
}

window.Chat = new ChatHandler();