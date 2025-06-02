const menu = document.getElementById('menu-label');
const sidebar = document.getElementsByClassName('sidebar')[0];

menu.addEventListener('click', function() {
    sidebar.classList.toggle('hide')
})

$(function () {
    $(document).on('change', '#item_type', function () {
        const selected = $(this).val();
        console.log("Selected item:", selected);

        // Hide & disable all forms
        $('#computer_form, #disk_drive_form, #processor_form, #vga_card_form, #ram_form, #monitor_form, #other_item_form')
            .hide()
            .find(':input').prop('disabled', true);

        // Show & enable selected form
        const formId = `#${selected}_form`;
        $(formId).show().find(':input').prop('disabled', false);
    });

    $(document).on('hidden.bs.modal', '#Modal', function () {
        $('#item_type').val('');
        $('#computer_form, #disk_drive_form, #processor_form, #vga_card_form, #ram_form, #monitor_form, #other_item_form')
            .hide()
            .find(':input').prop('disabled', true);
    });
});

function toggleSections(showClass, hideClass) {
    document.querySelectorAll(`.${showClass}, .${hideClass}`).forEach(section => {
        const show = section.classList.contains(showClass);
        section.hidden = !show;

        section.querySelectorAll('input, select, textarea').forEach(input => {
            input.disabled = !show;
            input.required = show && input.dataset.required === 'true';
        });
    });
}

document.querySelectorAll('input[name="item_type"]').forEach(radio => {
    radio.addEventListener('change', function () {
        if (this.value === 'computer') {
            toggleSections('computerSection', 'itemSection');
        } else {
            toggleSections('itemSection', 'computerSection');
        }
    });
});


// Transfer Allocated Computer

$('#from-location-select').on('change', function () {
    var selectedFromLocation = $(this).val();

    // Reset dan enable semua opsi di "To Location"
    $('#to-location-select option').each(function () {
        $(this).prop('disabled', false).show();
    });

    // Disable dan sembunyikan opsi yang sama di "To Location"
    $('#to-location-select option[value="' + selectedFromLocation + '"]').prop('disabled', true).hide();

    // Reset pilihan "To Location"
    $('#to-location-select').val('');

    // Load desk number untuk lokasi "From"
    if (selectedFromLocation) {
        $.ajax({
            url: '/get-desks-by-location/' + selectedFromLocation,
            type: 'GET',
            success: function (data) {
                $('#from-desk-select').empty();
                $('#from-desk-select').append('<option value="" class="text-center">Select Desk Number</option>');
                $.each(data, function (key, desk) {
                    $('#from-desk-select').append('<option value="'+ desk.desk_number +'">'+ desk.desk_number +'</option>');
                });
            }
        });
    } else {
        $('#from-desk-select').empty().append('<option value="">-- Pilih Desk Number --</option>');
    }
});

$('#to-location-select').on('change', function () {
    var locationId = $(this).val();
    if (locationId) {
        $.ajax({
            url: '/get-desks-by-location/' + locationId,
            type: 'GET',
            success: function (data) {
                $('#to-desk-select').empty();
                $('#to-desk-select').append('<option value="" class="text-center">Select Desk Number</option>');
                $.each(data, function (key, desk) {
                    $('#to-desk-select').append('<option value="'+ desk.desk_number +'">'+ desk.desk_number +'</option>');
                });
            }
        });
    } else {
        $('#to-desk-select').empty();
        $('#to-desk-select').append('<option value="">-- Pilih Desk Number --</option>');
    }
});

$('#from-desk-select, #from-location-select').on('change', function () {
    let locationId = $('#from-location-select').val();
    let deskNumber = $('#from-desk-select').val();

    if (locationId && deskNumber) {
        $.ajax({
            url: `/get-desk-components/${locationId}/${deskNumber}`,
            type: 'GET',
            success: function (data) {
                $('#computerFrom').text(data?.computer ? `${data.computer.item_name} ${data.computer.description}` : '-');
                $('#diskDrive1From').text(data?.disk_drive1 ? `${data.disk_drive1.item_name} ${data.disk_drive1.description}` : '-');
                $('#diskDrive2From').text(data?.disk_drive2 ? `${data.disk_drive2.item_name} ${data.disk_drive2.description}` : '-');
                $('#processorFrom').text(data?.processor ? `${data.processor.item_name} ${data.processor.description}` : '-');
                $('#vgaCardFrom').text(data?.vga_card ? `${data.vga_card.item_name} ${data.vga_card.description}` : '-');
                $('#ramFrom').text(data?.ram ? `${data.ram.item_name} ${data.ram.description}` : '-');
                $('#monitorFrom').text(data?.monitor ? `${data.monitor.item_name} ${data.monitor.description}` : '-');
            }
        });
    } else {
        $('#computerFrom, #diskDrive1From, #diskDrive2From, #processorFrom, #vgaCardFrom, #ramFrom, #monitorFrom').text('-');
    }
});

$('#to-desk-select, #to-location-select').on('change', function () {
    let locationId = $('#to-location-select').val();
    let deskNumber = $('#to-desk-select').val();

    if (locationId && deskNumber) {
        $.ajax({
            url: `/get-desk-components/${locationId}/${deskNumber}`,
            type: 'GET',
            success: function (data) {
                $('#computerTo').text(data?.computer ? `${data.computer.item_name} ${data.computer.description}` : 'Empty');
                $('#diskDrive1To').text(data?.disk_drive1 ? `${data.disk_drive1.item_name} ${data.disk_drive1.description}` : 'Empty');
                $('#diskDrive2To').text(data?.disk_drive2 ? `${data.disk_drive2.item_name} ${data.disk_drive2.description}` : 'Empty');
                $('#processorTo').text(data?.processor ? `${data.processor.item_name} ${data.processor.description}` : 'Empty');
                $('#vgaCardTo').text(data?.vga_card ? `${data.vga_card.item_name} ${data.vga_card.description}` : 'Empty');
                $('#ramTo').text(data?.ram ? `${data.ram.item_name} ${data.ram.description}` : 'Empty');
                $('#monitorTo').text(data?.monitor ? `${data.monitor.item_name} ${data.monitor.description}` : 'Empty');
            }
        });
    } else {
        $('#computerTo, #diskDrive1To, #diskDrive2To, #processorTo, #vgaCardTo, #ramTo, #monitorTo').text('-');
    }
});

// Transfer Allocated Item

$(document).ready(function () {
    $('#from-select').on('change', function () {
        let locationId = $(this).val();
        $('#from-item-select').html('<option disabled selected>Loading...</option>');

        if (locationId) {
            $.ajax({
                url: '/get-items-by-location/' + locationId,
                method: 'GET',
                success: function (data) {
                    $('#from-item-select').html('<option class="text-center" disabled selected>Select Item</option>');

                    if (data.length > 0) {
                        data.forEach(item => {
                            if (item.other_item) {
                                $('#from-item-select').append(
                                    `<option value="${item.id}">
                                        ${['disk_drive', 'processor', 'vga_card', 'ram', 'monitor'].includes(item.other_item.item_type)
                                            ? `${item.other_item.item_name} ${item.other_item.description}`
                                            : item.other_item.item_name} (Quantity: ${item.stock})
                                    </option>`
                                );
                            }
                        });
                    } else {
                        $('#from-item-select').append('<option disabled>No items found</option>');
                    }
                },
                error: function () {
                    $('#from-item-select').html('<option disabled>Error loading items</option>');
                }
            });
        }
    });
});

$('#location_id').on('change', function () {
    let locationId = $(this).val();

    fetch(`/get-available-desks/${locationId}`)
        .then(response => response.json())
        .then(data => {
            let deskSelect = $('#desk_number');
            deskSelect.empty().append('<option value="" disabled selected>Select Desk Number</option>');

            data.forEach(function(desk) {
                deskSelect.append(`<option value="${desk}">${desk}</option>`);
            });
        });
});

$(document).ready(function () {
    $('#item_type').on('change', function () {
        var selectedType = $(this).val();

        if (selectedType === 'computer_device') {
            $('#form-computer-device').show();
            $('#form-other-items').hide();
        } else if (selectedType === 'other_items') {
            $('#form-other-items').show();
            $('#form-computer-device').hide();
        } else {
            $('#form-computer-device, #form-other-items').hide();
        }
    });
});

$(document).ready(function () {
    const originalToOptions = $('#to-location-select').html(); // Simpan semua opsi awal

    $('#from-select').on('change', function () {
        const selectedFromLocation = $(this).val();

        // Reset semua opsi ke semula
        $('#to-select').html(originalToOptions);

        // Hilangkan opsi yang dipilih di From dari To
        $('#to-select option').each(function () {
            if ($(this).val() === selectedFromLocation) {
                $(this).remove();
            }
        });
    });
});


function togglePassword(el) {
    const input = el.previousElementSibling;
    const isPassword = input.type === "password";

    input.type = isPassword ? "text" : "password";
    el.classList.toggle("fa-eye");
    el.classList.toggle("fa-eye-slash");
}

document.addEventListener('DOMContentLoaded', function () {
    const checkAll = document.getElementById('checkAllComponents');
    const checkboxes = document.querySelectorAll('input[name="components[]"]');

    checkAll.addEventListener('change', function () {
        checkboxes.forEach(checkbox => {
            checkbox.checked = checkAll.checked;
        });
    });
});