$(document).ready(function() {
    // Handle modal edit
    $('#editModal').on('show.bs.modal', function(e) {
        const button = $(e.relatedTarget);
        const id = button.data('id');
        
        $.get('<?= site_url("signage/edit/") ?>' + id, function(data) {
            if (data.error) return alert('Error: ' + data.error);
            
            $('#edit_id').val(data.id);
            $('#edit_name').val(data.name);
            $('#edit_layout').val(data.layout);
            $('#edit_status').val(data.status);
        });
    });

    // Submit form edit
    $('#editForm').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const id = $('#edit_id').val();

        $.ajax({
            url: '<?= site_url("signage/update/") ?>' + id,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                if (res.status === 'success') {
                    alert('Berhasil disimpan!');
                    $('#editModal').modal('hide');
                    location.reload();
                } else {
                    alert('Gagal menyimpan.');
                }
            },
            error: function() {
                alert('Terjadi kesalahan jaringan.');
            }
        });
    });

    // Hapus signage
    window.deleteSignage = function(id) {
        if (!confirm('Yakin hapus perangkat ini?')) return;
        
        $.post('<?= site_url("signage/delete/") ?>' + id, function(res) {
            if (res.status === 'success') {
                alert('Berhasil dihapus.');
                location.reload();
            } else {
                alert('Gagal menghapus.');
            }
        });
    };

    // Buka player preview
    window.openPlayer = function(id) {
        window.open('<?= site_url("player/view/") ?>' + id, '_blank');
    };
});