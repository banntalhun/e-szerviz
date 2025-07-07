// public/assets/js/worksheet-create.js

$(document).ready(function() {
    // Ügyfél keresés Select2
    $('#customer_select').select2({
        theme: 'bootstrap-5',
        language: 'hu',
        placeholder: 'Új ügyfél vagy keresés...',
        minimumInputLength: 2,
        ajax: {
            url: '/customers/search',
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
        selectCustomer(data);
    }).on('select2:clear', function () {
        clearCustomer();
    });
    
    // Eszköz választás
    $('#device_select').on('change', function() {
        var deviceId = $(this).val();
        if (deviceId) {
            selectDevice(deviceId);
        } else {
            clearDevice();
        }
    });
    
    // Form validáció
    $('#worksheetForm').on('submit', function(e) {
        // Ellenőrizzük, hogy van-e ügyfél
        var customerId = $('#customer_id').val();
        var customerName = $('#customer_name').val();
        var customerPhone = $('#customer_phone').val();
        
        if (!customerId && (!customerName || !customerPhone)) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Hiányzó ügyfél adatok',
                text: 'Kérjük válasszon egy meglévő ügyfelet vagy adja meg az új ügyfél adatait!'
            });
            return false;
        }
        
        // Loading
        showLoading();
    });
    
    // Cég mező toggle
    $('#is_company').on('change', function() {
        toggleCompanyFields();
    });
});

function selectCustomer(customer) {
    $('#customer_id').val(customer.id);
    $('#customerDetails').show();
    $('#newCustomerForm').hide();
    
    // Ügyfél info megjelenítése
    var info = customer.name + '<br>' +
               'Tel: ' + customer.phone + '<br>';
    
    if (customer.email) {
        info += 'Email: ' + customer.email + '<br>';
    }
    
    if (customer.address) {
        info += 'Cím: ' + customer.address;
    }
    
    if (customer.priority) {
        info += '<br><span class="badge" style="background-color:' + customer.priority.color + '">' + 
                customer.priority.name + '</span>';
    }
    
    $('#selectedCustomerInfo').html(info);
    
    // Ügyfélhez tartozó eszközök betöltése
    loadCustomerDevices(customer.id);
}

function clearCustomer() {
    $('#customer_id').val('');
    $('#customerDetails').hide();
    $('#newCustomerForm').show();
    
    // Form mezők ürítése
    $('#customer_name').val('');
    $('#customer_phone').val('');
    $('#customer_email').val('');
    $('#customer_address').val('');
    $('#customer_city').val('Budapest');
    $('#customer_postal_code').val('');
    $('#is_company').prop('checked', false);
    $('#company_name').val('');
    $('#tax_number').val('');
    $('#company_address').val('');
    toggleCompanyFields();
    
    // Eszközök törlése
    $('#device_select').html('<option value="">Új eszköz...</option>');
    clearDevice();
}

function loadCustomerDevices(customerId) {
    $.get('/devices', { customer_id: customerId }, function(response) {
        var select = $('#device_select');
        select.html('<option value="">Új eszköz...</option>');
        
        if (response.items) {
            response.items.forEach(function(device) {
                var option = $('<option></option>')
                    .attr('value', device.id)
                    .text(device.name + (device.serial_number ? ' (' + device.serial_number + ')' : ''));
                select.append(option);
            });
        }
    });
}

function selectDevice(deviceId) {
    if (!deviceId) {
        clearDevice();
        return;
    }
    
    // Ajax kérés az eszköz adataiért
    $.get('/devices/' + deviceId, function(device) {
        $('#device_id').val(device.id);
        $('#deviceDetails').show();
        $('#newDeviceForm').hide();
        
        // Eszköz info megjelenítése
        var info = device.name + '<br>';
        if (device.serial_number) {
            info += 'Gyári szám: ' + device.serial_number + '<br>';
        }
        if (device.accessories) {
            info += 'Tartozékok: ' + device.accessories;
        }
        
        $('#selectedDeviceInfo').html(info);
        
        // Form mezők feltöltése
        $('#device_name').val(device.name);
        $('#serial_number').val(device.serial_number || '');
        $('#accessories').val(device.accessories || '');
        $('#condition_id').val(device.condition_id);
        $('#purchase_date').val(device.purchase_date || '');
    });
}

function clearDevice() {
    $('#device_id').val('');
    $('#deviceDetails').hide();
    $('#newDeviceForm').show();
    
    // Form mezők ürítése
    $('#device_name').val('');
    $('#serial_number').val('');
    $('#accessories').val('');
    $('#condition_id').val('');
    $('#purchase_date').val('');
}

function toggleCompanyFields() {
    var isCompany = $('#is_company').is(':checked');
    $('#companyFields').toggle(isCompany);
    
    if (!isCompany) {
        $('#company_name').val('');
        $('#tax_number').val('');
        $('#company_address').val('');
    }
}