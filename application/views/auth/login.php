<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AUDIO</title>
    <link rel="shortcut icon" href="<?= base_url('assets/images/logo_pulo_gebang.jpg') ?>" type="image/jpeg">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            /* Menambahkan Background Image bertema Studio/Announcer */
            background-image: linear-gradient(rgba(15, 23, 42, 0.75), rgba(15, 23, 42, 0.75)), 
                              url('https://images.unsplash.com/photo-1478737270239-2f02b77fc618?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .glass-card {
            /* Membuat kartu lebih transparan agar efek glassmorphism terasa */
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-md">
        <div class="glass-card shadow-[0_20px_50px_rgba(0,0,0,0.3)] rounded-3xl overflow-hidden">
            <div class="p-8 pb-0 text-center">
                <div class="inline-flex items-center justify-center w-24 h-24 mb-4 rounded-3xl overflow-hidden shadow-lg border border-slate-100 bg-white">
                    <img src="<?= base_url('assets/images/logo_pulo_gebang.jpg') ?>" alt="Logo TTPG" class="w-full h-full object-cover">
                </div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">TTPG AUDIO</h1>
                <p class="text-slate-500 text-sm mt-1">Sistem Management Announcer</p>
            </div>

            <div class="p-8">
                <form id="loginForm" class="space-y-5">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Username</label>
                        <input type="text" name="username" placeholder="Masukkan username" 
                            class="w-full px-4 py-3 rounded-xl bg-slate-100/50 border border-slate-200 focus:border-blue-500 focus:bg-white focus:ring-0 transition duration-300 text-slate-700 outline-none" required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Password</label>
                        <input type="password" name="password" placeholder="••••••••" 
                            class="w-full px-4 py-3 rounded-xl bg-slate-100/50 border border-slate-200 focus:border-blue-500 focus:bg-white focus:ring-0 transition duration-300 text-slate-700 outline-none" required>
                    </div>

                    <button type="submit" id="btnSubmit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-blue-500/30 transition duration-300 transform active:scale-[0.98]">
                        Masuk ke Dashboard
                    </button>
                </form>
            </div>
        </div>
        <p class="text-center text-white/70 text-xs mt-8">&copy; 2026 TTPG AUDIO. Audio System.</p>
    </div>

    <div id="toast" class="fixed top-5 right-5 transform translate-x-full transition-transform duration-500 z-50">
        <div id="toast-content" class="px-6 py-3 rounded-2xl shadow-2xl text-white text-sm font-medium flex items-center gap-3">
            <span id="toast-icon"></span>
            <span id="toast-message"></span>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function showToast(message, type = 'success') {
                const toast = $('#toast');
                const content = $('#toast-content');
                
                if(type === 'success') {
                    content.addClass('bg-emerald-500').removeClass('bg-rose-500');
                    $('#toast-icon').html('✓');
                } else {
                    content.addClass('bg-rose-500').removeClass('bg-emerald-500');
                    $('#toast-icon').html('✕');
                }

                $('#toast-message').text(message);
                toast.removeClass('translate-x-full');
                
                setTimeout(() => {
                    toast.addClass('translate-x-full');
                }, 3000);
            }

            $('#loginForm').on('submit', function(e) {
                e.preventDefault();
                const btn = $('#btnSubmit');
                btn.html('Memproses...').attr('disabled', true).addClass('opacity-70');

                $.ajax({
                    url: '<?= site_url("auth/login") ?>',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'success') {
                            showToast('Berhasil! Mengalihkan...');
                            setTimeout(() => window.location.href = '<?= site_url("dashboard") ?>', 1000);
                        } else {
                            showToast(res.message, 'error');
                            btn.html('Masuk ke Dashboard').attr('disabled', false).removeClass('opacity-70');
                        }
                    },
                    error: function() {
                        showToast('Gagal terhubung ke server', 'error');
                        btn.html('Masuk ke Dashboard').attr('disabled', false).removeClass('opacity-70');
                    }
                });
            });
        });
    </script>
</body>
</html>