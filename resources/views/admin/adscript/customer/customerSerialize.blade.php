<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right"
    };

    function customerStatusBadge(status) {
        switch (parseInt(status)) {
            case 1:
                return '<span class="badge bg-dark">Enabled</span>';
            case 2:
                return '<span class="badge bg-light text-dark border">Disabled</span>';
            case 3:
                return '<span class="badge bg-secondary text-white">Deleted</span>';
            default:
                return '<span class="badge bg-light text-dark border">Unknown</span>';
        }
    }

    function customerAvatar(row) {
        if (row.avatar) {
            var src = row.avatar.indexOf('http') === 0 ? row.avatar : avatarBaseUrl + '/' + row.avatar;
            return '<img src="' + src + '" alt="Avatar" class="rounded-circle" width="36" height="36" style="object-fit: cover;" />';
        }
        var initials = ((row.fname || '?').charAt(0) + (row.lname || '').charAt(0)).toUpperCase();
        return '<span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-dark text-white fw-bold" style="width: 36px; height: 36px; font-size: 13px;">' + initials + '</span>';
    }

    function customerFullName(row) {
        return [row.fname, row.mname, row.lname].filter(Boolean).join(' ');
    }

    $(document).ready(function () {
        var dataTable = $('#customerTable').DataTable({
            "ajax": {
                "url": customerReadRoute,
                "type": "GET",
            },
            destroy: true,
            info: true,
            responsive: true,
            lengthChange: true,
            searching: true,
            paging: true,
            order: [[6, 'desc']],
            "columns": [
                {
                    data: 'avatar',
                    orderable: false,
                    render: function (data, type, row) {
                        if (type === 'display') return customerAvatar(row);
                        return data;
                    }
                },
                {
                    data: 'fname',
                    render: function (data, type, row) {
                        if (type === 'display') return customerFullName(row);
                        return data;
                    }
                },
                { data: 'email' },
                { data: 'mobileno' },
                { data: 'gender' },
                {
                    data: 'cstatus',
                    render: function (data) { return customerStatusBadge(data); }
                },
                {
                    data: 'created_at',
                    render: function (data) {
                        if (!data) return '—';
                        return new Date(data).toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' });
                    }
                },
                {
                    data: 'id',
                    orderable: false,
                    render: function (data, type, row) {
                        if (type === 'display') {
                            var viewBtn = '<button type="button" class="btn btn-sm btn-outline-dark btn-customerview" data-id="' + row.id + '" data-toggle="tooltip" data-placement="top" title="View customer details."><i class="ti ti-eye"></i></button>&nbsp;';
                            var statusBtn = '<button type="button" class="btn btn-sm btn-dark btn-cstatusedit" data-id="' + row.id + '" data-cstatus="' + row.cstatus + '" data-name="' + customerFullName(row) + '" data-toggle="tooltip" data-placement="top" title="Enable / Disable customer."><i class="ti ti-toggle-left"></i></button>';
                            return viewBtn + statusBtn;
                        }
                        return data;
                    },
                },
            ],
            "createdRow": function (row, data, index) {
                $(row).attr('id', 'tr-' + data.id);
            }
        });

        $(document).on('customerUpdated', function () {
            dataTable.ajax.reload(null, false);
        });

        dataTable.on('draw', function () {
            $('[data-toggle="tooltip"]').tooltip();
        });

        // Cache rows for the details modal (no extra request needed)
        var rowCache = {};
        dataTable.on('xhr', function (e, settings, json) {
            rowCache = {};
            (json.data || []).forEach(function (row) { rowCache[row.id] = row; });
        });

        $(document).on('click', '.btn-customerview', function () {
            var row = rowCache[$(this).data('id')] || {};

            $('#viewFullName').text(customerFullName(row) || '—');
            $('#viewEmail').text(row.email || '—');

            if (row.avatar) {
                var src = row.avatar.indexOf('http') === 0 ? row.avatar : avatarBaseUrl + '/' + row.avatar;
                $('#viewAvatar').attr('src', src).show();
            } else {
                $('#viewAvatar').hide();
            }

            var pill = customerStatusBadge(row.cstatus);
            $('#viewStatus').attr('class', 'ms-auto d-inline-block').html(pill);

            $('#viewGender').text(row.gender || 'Not specified');
            $('#viewBday').text(row.bday || 'Not specified');
            $('#viewMobile').text(row.mobileno || 'Not specified');
            $('#viewCreated').text(row.created_at ? new Date(row.created_at).toLocaleDateString(undefined, { month: 'long', day: 'numeric', year: 'numeric' }) : '—');

            var addressParts = [row.hnum, row.brgy, row.city, row.province, row.region, row.zcode].filter(Boolean);
            var address = row.address || addressParts.join(', ');
            $('#viewAddress').text(address || 'Not specified');

            $('#viewCustomerModal').modal('show');
        });

        $(document).on('click', '.btn-cstatusedit', function () {
            $('#editCstatusId').val($(this).data('id'));
            $('#editCstatusName').val($(this).data('cstatus'));
            $('#editCstatusWho').text($(this).data('name'));
            $('#editCstatusModal').modal('show');
        });

        $('#editCstatusForm').submit(function (event) {
            event.preventDefault();
            var formData = $(this).serialize();

            $.ajax({
                url: customerStatusRoute,
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $('#editCstatusModal').modal('hide');
                        $(document).trigger('customerUpdated');
                    } else {
                        toastr.error(response.message || 'Failed to update status.');
                    }
                },
                error: function (xhr) {
                    var errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).message : 'An error occurred';
                    toastr.error(errorMessage);
                }
            });
        });
    });
</script>
