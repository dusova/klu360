function modalAc(kisiId) {
    const modal = document.getElementById(`modal-${kisiId}`);
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden'; 
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                modalKapat(kisiId);
            }
        });
        
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modalKapat(kisiId);
            }
        });
    } else {
        console.error(`${kisiId} ID'li modal bulunamadı.`);
    }
}

function modalKapat(kisiId) {
    const modal = document.getElementById(`modal-${kisiId}`);
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = ''; 
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.kisi-modal').forEach(function(modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }
        });
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.kisi-modal').forEach(function(modal) {
                if (modal.style.display === 'flex') {
                    modal.style.display = 'none';
                    document.body.style.overflow = '';
                }
            });
        }
    });
});
