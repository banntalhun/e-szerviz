// public/assets/js/worksheet-show.js

// Várjuk meg, hogy a jQuery betöltődjön
(function() {
    'use strict';
    
    // Ha a jQuery még nincs betöltve, várunk rá
    if (typeof jQuery === 'undefined') {
        document.addEventListener('DOMContentLoaded', function() {
            // Próbáljuk újra 100ms múlva
            setTimeout(initWorksheetShow, 100);
        });
        return;
    }
    
    // jQuery elérhető, inicializáljuk
    jQuery(document).ready(initWorksheetShow);
    
    function initWorksheetShow() {
        var $ = jQuery;
        
        // Státusz váltás form
        $('#statusForm').on('submit', function(e) {
            e.preventDefault();
            
            var formData = {
                worksheet_id: $('#worksheet_id').val(),
                status_id: $('#status_id').val(),
                note: $('#status_note').val(),
                csrf_token: $('meta[name="csrf-token"]').attr('content')
            };
            
            $.ajax({
                url: '/ajax/worksheet/update-status',
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $('#statusModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Sikeres státusz váltás!',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    }
                },
                error: function() {
                    Swal.fire('Hiba!', 'A státusz váltás sikertelen!', 'error');
                }
            });
        });
        
        // Fájl feltöltés
        $('#uploadForm').on('submit', function(e) {
            e.preventDefault();
            
            var fileInput = $('#fileInput')[0];
            if (!fileInput.files.length) {
                Swal.fire('Hiba!', 'Válasszon ki egy fájlt!', 'warning');
                return;
            }
            
            var formData = new FormData();
            formData.append('file', fileInput.files[0]);
            formData.append('csrf_token', $('meta[name="csrf-token"]').attr('content'));
            
            showLoading();
            
            $.ajax({
                url: '/worksheets/' + worksheetId + '/upload',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    hideLoading();
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Fájl sikeresen feltöltve!',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    }
                },
                error: function() {
                    hideLoading();
                    Swal.fire('Hiba!', 'A feltöltés sikertelen!', 'error');
                }
            });
        });
        
        // Tétel hozzáadása modal megnyitása
        $('#addItemBtn').on('click', function() {
            $('#itemModal').modal('show');
        });
        
        // Ellenőrizzük, hogy léteznek-e ezek az elemek
        if ($('#itemModal').length && $('#part_service_search').length) {
            // Alkatrész/szolgáltatás keresés
            $('#part_service_search').select2({
                theme: 'bootstrap-5',
                language: 'hu',
                placeholder: 'Keresés...',
                minimumInputLength: 2,
                dropdownParent: $('#itemModal'),
                ajax: {
                    url: '/parts/search',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(function(item) {
                                return {
                                    id: item.id,
                                    text: item.text,
                                    data: item
                                };
                            })
                        };
                    },
                    cache: true
                }
            }).on('select2:select', function (e) {
                var data = e.params.data.data;
                $('#item_unit_price').val(data.price);
                $('#item_unit').text(data.unit);
                calculateItemTotal();
            });
        }
        
        // Tétel összeg számítás
        $('#item_quantity, #item_unit_price, #item_discount').on('input', function() {
            calculateItemTotal();
        });
        
        // Tétel hozzáadása
        $('#itemForm').on('submit', function(e) {
            e.preventDefault();
            
            var formData = {
                worksheet_id: worksheetId,
                part_service_id: $('#part_service_search').val(),
                quantity: $('#item_quantity').val(),
                unit_price: $('#item_unit_price').val(),
                discount: $('#item_discount').val() || 0,
                csrf_token: $('meta[name="csrf-token"]').attr('content')
            };
            
            $.ajax({
                url: '/ajax/worksheet/add-item',
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $('#itemModal').modal('hide');
                        location.reload();
                    }
                },
                error: function() {
                    Swal.fire('Hiba!', 'A tétel hozzáadása sikertelen!', 'error');
                }
            });
        });
        
        // Tétel törlése
        $('.delete-item').on('click', function() {
            var itemId = $(this).data('id');
            
            Swal.fire({
                title: 'Biztosan törli?',
                text: 'A tétel véglegesen törlődik!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Igen, törlöm!',
                cancelButtonText: 'Mégsem'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/ajax/worksheet/remove-item',
                        type: 'POST',
                        data: {
                            item_id: itemId,
                            worksheet_id: worksheetId,
                            csrf_token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                location.reload();
                            }
                        },
                        error: function() {
                            Swal.fire('Hiba!', 'A törlés sikertelen!', 'error');
                        }
                    });
                }
            });
        });
    }
    
    // Globális függvények
    window.calculateItemTotal = function() {
        if (typeof jQuery === 'undefined') return;
        
        var $ = jQuery;
        var quantity = parseFloat($('#item_quantity').val()) || 0;
        var unitPrice = parseFloat($('#item_unit_price').val()) || 0;
        var discount = parseFloat($('#item_discount').val()) || 0;
        
        var total = quantity * unitPrice * (1 - discount / 100);
        $('#item_total').text(formatPrice(total));
    };
})();