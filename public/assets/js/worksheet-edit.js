// public/assets/js/worksheet-edit.js

// Globális változó a worksheet ID-hoz (ha nincs még definiálva)
var worksheetId = worksheetId || $('#worksheet_id').val();

$(document).ready(function() {
    console.log('Worksheet edit script loaded!');
    
    // Fix for number input selection error
    $.fn.extend({
        selectRange: function(start, end) {
            return this.each(function() {
                if (this.type !== 'number' && this.setSelectionRange) {
                    this.setSelectionRange(start, end);
                }
            });
        }
    });

    // Select2 inicializálás
    $('#part_service_select').select2({
        theme: 'bootstrap-5',
        language: 'hu',
        placeholder: 'Válasszon vagy kezdjen el gépelni...',
        allowClear: true,
        dropdownParent: $('#addItemModal'),
        width: '100%'
    });

    // Fő eseménykezelő - amikor kiválasztanak egy elemet
    $('#part_service_select').on('change', function() {
        var selectedOption = $(this).find(':selected');
        
        if (selectedOption.val()) {
            var price = selectedOption.attr('data-price');
            var unit = selectedOption.attr('data-unit');
            
            console.log('Item selected:', {
                value: selectedOption.val(),
                text: selectedOption.text().trim(),
                price: price,
                unit: unit
            });
            
            // Ár és mértékegység beállítása
            if (price) {
                $('#item_unit_price').val(price);
                $('#item_unit').text(unit || 'db');
                calculateItemTotal();
            }
        } else {
            // Ha nincs kiválasztva semmi
            $('#item_unit_price').val('');
            $('#item_unit').text('db');
            $('#item_total').text('0 Ft');
        }
    });

    // Mennyiség, ár, kedvezmény változásakor újraszámolás
    $('#item_quantity, #item_unit_price, #item_discount').on('input', function() {
        calculateItemTotal();
    });

    // Új tétel mezők
    $('#new_item_quantity, #new_item_price, #new_item_discount').on('input', function() {
        calculateItemTotal();
    });

    // Tab váltás
    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        var target = $(e.target).attr("data-bs-target");
        if (target === '#new-panel') {
            $('#new_item_unit').trigger('input');
        }
        calculateItemTotal();
    });

    // Mértékegység változás új tételnél
    $('#new_item_unit').on('input', function() {
        $('#new_item_unit_display').text($(this).val() || 'db');
    });

    // Form submit
    $('#addItemForm').on('submit', function(e) {
        e.preventDefault();
        
        var activeTab = $('.tab-pane.active').attr('id');
        if (activeTab === 'existing-panel') {
            addExistingItem();
        } else {
            addNewItem();
        }
    });

    // Tétel törlése
    $(document).on('click', '.delete-item', function() {
        var itemId = $(this).data('id');
        deleteItem(itemId);
    });

    // Modal megnyitásakor
    $('#addItemModal').on('shown.bs.modal', function () {
        // Ha már van kiválasztott elem, trigger a change eseményt
        var currentValue = $('#part_service_select').val();
        if (currentValue) {
            $('#part_service_select').trigger('change');
        }
    });

    // Modal bezárásakor reset
    $('#addItemModal').on('hidden.bs.modal', function () {
        resetItemForm();
        $('#existing-tab').tab('show');
    });

    // Státusz változás naplózása
    var originalStatusId = $('#status_id').val();
    $('form').on('submit', function() {
        var newStatusId = $('#status_id').val();
        if (newStatusId !== originalStatusId) {
            $('#status_note').val('Státusz változás szerkesztés során');
        }
    });
});

// Tétel összeg számítás
function calculateItemTotal() {
    var activeTab = $('.tab-pane.active').attr('id');
    var quantity, unitPrice, discount;
    
    if (activeTab === 'existing-panel') {
        quantity = parseFloat($('#item_quantity').val()) || 0;
        unitPrice = parseFloat($('#item_unit_price').val()) || 0;
        discount = parseFloat($('#item_discount').val()) || 0;
    } else {
        quantity = parseFloat($('#new_item_quantity').val()) || 0;
        unitPrice = parseFloat($('#new_item_price').val()) || 0;
        discount = parseFloat($('#new_item_discount').val()) || 0;
    }
    
    var subtotal = quantity * unitPrice;
    var discountAmount = subtotal * (discount / 100);
    var total = subtotal - discountAmount;
    
    $('#item_total').text(formatPrice(total));
}

// Meglévő tétel hozzáadása
function addExistingItem() {
    var formData = {
        worksheet_id: worksheetId,
        part_service_id: $('#part_service_select').val(),
        quantity: $('#item_quantity').val(),
        unit_price: $('#item_unit_price').val(),
        discount: $('#item_discount').val() || 0,
        is_internal: $('#is_internal').is(':checked') ? 1 : 0,
        csrf_token: $('meta[name="csrf-token"]').attr('content')
    };
    
    // Validáció
    if (!formData.part_service_id) {
        Swal.fire('Hiba!', 'Válasszon ki egy alkatrészt vagy szolgáltatást!', 'error');
        return;
    }
    
    if (formData.quantity <= 0) {
        Swal.fire('Hiba!', 'A mennyiség nagyobb kell legyen nullánál!', 'error');
        return;
    }
    
    if (formData.unit_price < 0) {
        Swal.fire('Hiba!', 'Az egységár nem lehet negatív!', 'error');
        return;
    }
    
    showLoading();
    
    $.ajax({
        url: '/ajax/worksheet/add-item',
        type: 'POST',
        data: formData,
        success: function(response) {
            hideLoading();
            if (response.success) {
                handleAddItemSuccess(response);
            }
        },
        error: function(xhr) {
            hideLoading();
            handleAddItemError(xhr);
        }
    });
}

// Új tétel hozzáadása
function addNewItem() {
    var formData = {
        worksheet_id: worksheetId,
        new_item: true,
        name: $('#new_item_name').val(),
        sku: $('#new_item_sku').val(),
        type: $('#new_item_type').val(),
        unit: $('#new_item_unit').val(),
        description: $('#new_item_description').val(),
        quantity: $('#new_item_quantity').val(),
        unit_price: $('#new_item_price').val(),
        discount: $('#new_item_discount').val() || 0,
        is_internal: $('#is_internal').is(':checked') ? 1 : 0,
        csrf_token: $('meta[name="csrf-token"]').attr('content')
    };
    
    // Validáció
    if (!formData.name) {
        Swal.fire('Hiba!', 'A megnevezés kötelező!', 'error');
        return;
    }
    
    if (!formData.unit) {
        Swal.fire('Hiba!', 'A mértékegység kötelező!', 'error');
        return;
    }
    
    if (formData.quantity <= 0) {
        Swal.fire('Hiba!', 'A mennyiség nagyobb kell legyen nullánál!', 'error');
        return;
    }
    
    if (formData.unit_price <= 0) {
        Swal.fire('Hiba!', 'Az egységár nagyobb kell legyen nullánál!', 'error');
        return;
    }
    
    showLoading();
    
    $.ajax({
        url: '/ajax/worksheet/add-item',
        type: 'POST',
        data: formData,
        success: function(response) {
            hideLoading();
            if (response.success) {
                handleAddItemSuccess(response);
                
                // Ha új tétel volt, frissítjük a select listát
                if (response.new_part_id) {
                    var newOption = new Option(
                        formData.name + ' - ' + formatPrice(formData.unit_price) + '/' + formData.unit,
                        response.new_part_id,
                        false,
                        false
                    );
                    $(newOption).attr({
                        'data-price': formData.unit_price,
                        'data-unit': formData.unit,
                        'data-type': formData.type
                    });
                    $('#part_service_select').append(newOption);
                }
            }
        },
        error: function(xhr) {
            hideLoading();
            handleAddItemError(xhr);
        }
    });
}

// Sikeres hozzáadás kezelése
function handleAddItemSuccess(response) {
    $('#addItemModal').modal('hide');
    updateItemsTable(response.items);
    updateTotals(response.total_price, response.stats);
    
    Swal.fire({
        icon: 'success',
        title: 'Tétel hozzáadva!',
        showConfirmButton: false,
        timer: 1500
    });
    
    resetItemForm();
}

// Hiba kezelése
function handleAddItemError(xhr) {
    var message = 'Hiba történt a tétel hozzáadása során!';
    if (xhr.responseJSON && xhr.responseJSON.error) {
        message = xhr.responseJSON.error;
    }
    Swal.fire('Hiba!', message, 'error');
}

// Tétel törlése
function deleteItem(itemId) {
    Swal.fire({
        title: 'Biztosan törli?',
        text: 'A tétel véglegesen törlődik a munkalapról!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Igen, törlöm!',
        cancelButtonText: 'Mégsem'
    }).then((result) => {
        if (result.isConfirmed) {
            showLoading();
            
            $.ajax({
                url: '/ajax/worksheet/remove-item',
                type: 'POST',
                data: {
                    item_id: itemId,
                    worksheet_id: worksheetId,
                    csrf_token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    hideLoading();
                    if (response.success) {
                        updateItemsTable(response.items);
                        updateTotals(response.total_price, response.stats);
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Tétel törölve!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                },
                error: function() {
                    hideLoading();
                    Swal.fire('Hiba!', 'A tétel törlése sikertelen!', 'error');
                }
            });
        }
    });
}

// Táblázat frissítése
function updateItemsTable(items) {
    var tbody = $('#itemsTableBody');
    tbody.empty();
    
    if (items.length === 0) {
        tbody.append(
            '<tr><td colspan="8" class="text-center text-muted">' +
            'Még nincsenek tételek hozzáadva a munkalaphoz.' +
            '</td></tr>'
        );
    } else {
        items.forEach(function(item) {
            var row = $('<tr>').attr('data-item-id', item.id);
            
            row.append($('<td>').text(item.name));
            
            var typeHtml = item.type === 'part' 
                ? '<span class="badge bg-info">Alkatrész</span>'
                : '<span class="badge bg-success">Szolgáltatás</span>';
            row.append($('<td>').html(typeHtml));
            
            row.append($('<td>').addClass('text-center').text(item.quantity + ' ' + item.unit));
            row.append($('<td>').addClass('text-end').text(formatPrice(item.unit_price)));
            
            var discountText = item.discount > 0 ? item.discount + '%' : '-';
            row.append($('<td>').addClass('text-center').text(discountText));
            
            var internalHtml = item.is_internal == 1
                ? '<span class="badge bg-warning" title="Belső tétel - nem jelenik meg a nyomtatott munkalapon"><i class="fas fa-eye-slash"></i></span>'
                : '<span class="badge bg-secondary" title="Publikus tétel - megjelenik a munkalapon"><i class="fas fa-eye"></i></span>';
            row.append($('<td>').addClass('text-center').html(internalHtml));
            
            row.append($('<td>').addClass('text-end').text(formatPrice(item.total_price)));
            
            var actionHtml = '<button type="button" class="btn btn-sm btn-danger delete-item" data-id="' + item.id + '" title="Törlés">' +
                           '<i class="fas fa-trash"></i></button>';
            row.append($('<td>').addClass('text-center').html(actionHtml));
            
            tbody.append(row);
        });
    }
}

// Összegek frissítése
function updateTotals(totalPrice, stats) {
    $('#totalPrice').text(formatPrice(totalPrice));
    $('#summaryTotal').text(formatPrice(totalPrice));
    
    if (stats) {
        $('#partCount').text(stats.part_count || 0);
        $('#serviceCount').text(stats.service_count || 0);
        $('#internalCount').text(stats.internal_count || 0);
    }
}

// Form reset
function resetItemForm() {
    $('#addItemForm')[0].reset();
    
    $('#part_service_select').val(null).trigger('change');
    $('#item_quantity').val(1);
    $('#item_unit_price').val('');
    $('#item_discount').val(0);
    
    $('#new_item_name').val('');
    $('#new_item_sku').val('');
    $('#new_item_type').val('service');
    $('#new_item_unit').val('db');
    $('#new_item_description').val('');
    $('#new_item_quantity').val(1);
    $('#new_item_price').val('');
    $('#new_item_discount').val(0);
    
    $('#is_internal').prop('checked', false);
    $('#item_unit').text('db');
    $('#new_item_unit_display').text('db');
    $('#item_total').text('0 Ft');
}

// Ár formázás
function formatPrice(price) {
    return new Intl.NumberFormat('hu-HU', {
        style: 'currency',
        currency: 'HUF',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(price);
}

// Globális tesztelő funkció (egyszerűbb verzió)
window.testPriceSet = function() {
    console.log('Manual price test...');
    $('#item_unit_price').val('5000');
    $('#item_unit').text('db');
    calculateItemTotal();
    console.log('Price input value after set:', $('#item_unit_price').val());
};