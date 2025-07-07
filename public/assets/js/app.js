// public/assets/js/app.js

// Várjuk meg, hogy a jQuery betöltődjön
(function() {
    'use strict';
    
    // Ha a jQuery még nincs betöltve, várunk rá
    function waitForJQuery(callback) {
        if (typeof jQuery !== 'undefined') {
            callback(jQuery);
        } else {
            setTimeout(function() {
                waitForJQuery(callback);
            }, 100);
        }
    }
    
    // DOM betöltése után
    document.addEventListener('DOMContentLoaded', function() {
        waitForJQuery(function($) {
            initializeApp($);
        });
    });
    
    function initializeApp($) {
        // CSRF token beállítása AJAX kérésekhez
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // Tooltipek inicializálása
        if (typeof bootstrap !== 'undefined') {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Popoverek inicializálása
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
        }
        
        // Flash üzenetek automatikus elrejtése
        setTimeout(function() {
            $('.alert-dismissible').fadeOut('slow');
        }, 5000);
        
        // Megerősítés törlés előtt
        $('.delete-confirm').on('click', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            var name = $(this).data('name') || 'ezt az elemet';
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Biztosan törli?',
                    text: `Biztosan törölni szeretné: ${name}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Igen, törlöm!',
                    cancelButtonText: 'Mégsem'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            } else if (confirm(`Biztosan törölni szeretné: ${name}?`)) {
                form.submit();
            }
        });
        
        // Select2 alapértelmezett inicializálás
        if ($.fn.select2) {
            $('.select2').select2({
                theme: 'bootstrap-5',
                language: 'hu',
                width: '100%'
            });
        }
        
        // Datepicker inicializálás
        if (typeof flatpickr !== 'undefined') {
            $('.datepicker').flatpickr({
                locale: 'hu',
                dateFormat: 'Y-m-d',
                altFormat: 'Y.m.d',
                altInput: true
            });
        }
        
        // Számformázás
        $('.price-input').on('input', function() {
            var value = $(this).val().replace(/[^\d]/g, '');
            if (value) {
                value = parseInt(value).toLocaleString('hu-HU');
                $(this).val(value);
            }
        });
        
        // Telefonszám formázás
        $('.phone-input').on('input', function() {
            var value = $(this).val().replace(/[^\d+]/g, '');
            if (value.length > 0 && value[0] !== '+') {
                value = '+36' + value;
            }
            $(this).val(value);
        });
        
        // Form validáció
        $('.needs-validation').on('submit', function(event) {
            if (!this.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            $(this).addClass('was-validated');
        });
        
        // Táblázat sorok kattinthatóvá tétele
        $('.clickable-row').on('click', function() {
            window.location = $(this).data('href');
        });
        
        // Loading overlay
        window.showLoading = function() {
            $('body').append('<div class="loading-overlay"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Betöltés...</span></div></div>');
        };
        
        window.hideLoading = function() {
            $('.loading-overlay').remove();
        };
        
        // AJAX error handler
        $(document).ajaxError(function(event, jqxhr, settings, thrownError) {
            hideLoading();
            if (jqxhr.status === 401) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Munkamenet lejárt',
                        text: 'A munkamenete lejárt, kérjük jelentkezzen be újra.',
                        icon: 'warning',
                        confirmButtonText: 'Bejelentkezés'
                    }).then(() => {
                        window.location.href = '/login';
                    });
                } else {
                    alert('A munkamenete lejárt, kérjük jelentkezzen be újra.');
                    window.location.href = '/login';
                }
            } else if (jqxhr.status === 403) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Hozzáférés megtagadva', 'Nincs jogosultsága ehhez a művelethez.', 'error');
                } else {
                    alert('Nincs jogosultsága ehhez a művelethez.');
                }
            } else if (jqxhr.status === 404) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Nem található', 'A keresett elem nem található.', 'error');
                } else {
                    alert('A keresett elem nem található.');
                }
            } else if (jqxhr.status >= 500) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Szerver hiba', 'Váratlan hiba történt, kérjük próbálja újra később.', 'error');
                } else {
                    alert('Váratlan hiba történt, kérjük próbálja újra később.');
                }
            }
        });
        
        // Print funkció
        window.printElement = function(elementId) {
            var printContents = document.getElementById(elementId).innerHTML;
            var originalContents = document.body.innerHTML;
            
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            
            location.reload();
        };
        
        // Billentyűparancsok
        $(document).on('keydown', function(e) {
            // Ctrl+S - Mentés
            if (e.ctrlKey && e.key === 's') {
                e.preventDefault();
                $('form:visible').first().submit();
            }
            
            // ESC - Bezárás/Mégsem
            if (e.key === 'Escape') {
                $('.modal:visible').modal('hide');
                if ($('.btn-cancel:visible').length) {
                    $('.btn-cancel:visible').first().click();
                }
            }
            
            // F2 - Új elem
            if (e.key === 'F2') {
                e.preventDefault();
                if ($('.btn-create:visible').length) {
                    window.location = $('.btn-create:visible').first().attr('href');
                }
            }
        });
    }
    
    // Globális függvények
    window.formatPrice = function(price) {
        return new Intl.NumberFormat('hu-HU', { 
            style: 'currency', 
            currency: 'HUF',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(price);
    };
    
    window.formatDate = function(date, format) {
        format = format || 'Y.m.d';
        var d = new Date(date);
        var year = d.getFullYear();
        var month = ('0' + (d.getMonth() + 1)).slice(-2);
        var day = ('0' + d.getDate()).slice(-2);
        var hours = ('0' + d.getHours()).slice(-2);
        var minutes = ('0' + d.getMinutes()).slice(-2);
        
        return format
            .replace('Y', year)
            .replace('m', month)
            .replace('d', day)
            .replace('H', hours)
            .replace('i', minutes);
    };
    
    // HTML escape függvény - XSS védelem
    window.escapeHtml = function(text) {
        if (!text) return '';
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.toString().replace(/[&<>"']/g, m => map[m]);
    };
    
    // Export to CSV
    window.exportTableToCSV = function(tableId, filename) {
        var csv = [];
        var rows = document.querySelectorAll(`#${tableId} tr`);
        
        for (var i = 0; i < rows.length; i++) {
            var row = [], cols = rows[i].querySelectorAll('td, th');
            
            for (var j = 0; j < cols.length; j++) {
                var data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s)/gm, ' ');
                data = data.replace(/"/g, '""');
                row.push('"' + data + '"');
            }
            
            csv.push(row.join(';'));
        }
        
        var csv_string = csv.join('\n');
        var downloadLink = document.createElement('a');
        downloadLink.download = filename;
        downloadLink.href = 'data:text/csv;charset=utf-8;base64,' + btoa(unescape(encodeURIComponent('\ufeff' + csv_string)));
        downloadLink.style.display = 'none';
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    };
})();