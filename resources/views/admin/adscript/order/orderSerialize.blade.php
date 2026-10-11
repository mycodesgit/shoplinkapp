<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right"
    };

    var orderDataTable = null;
    var orderStatusFilter = 'all';
    var orderRowCache = {};

    var orderStatusPills = {
        'pending': ['Pending', 'bg-light text-dark border'],
        'accepted': ['Accepted', 'bg-secondary text-white'],
        'preparing': ['Preparing', 'bg-secondary text-white'],
        'ready_to_claim': ['Ready to Claim', 'bg-dark text-white'],
        'ready_to_deliver': ['Ready to Deliver', 'bg-dark text-white'],
        'out_for_delivery': ['Out for Delivery', 'bg-dark text-white'],
        'delivered': ['Delivered', 'bg-dark text-white'],
        'cancelled': ['Cancelled', 'bg-light text-dark border']
    };

    var orderActionLabels = {
        'accepted': 'Accept',
        'preparing': 'Prepare',
        'ready_to_claim': 'Ready for Pickup',
        'ready_to_deliver': 'Ready for Delivery',
        'out_for_delivery': 'Dispatch',
        'delivered': 'Complete',
        'cancelled': 'Cancel'
    };

    function orderStatusBadge(status) {
        var pill = orderStatusPills[status] || [status, 'bg-light text-dark border'];
        return '<span class="badge ' + pill[1] + '">' + pill[0] + '</span>';
    }

    function orderActionButtons(row) {
        var html = '<button type="button" class="btn btn-sm btn-outline-dark btn-orderview" data-id="' + row.id + '" data-toggle="tooltip" data-placement="top" title="View order details."><i class="ti ti-eye"></i></button>&nbsp;';
        html += '<button type="button" class="btn btn-sm btn-outline-dark btn-orderprint" data-id="' + row.id + '" data-toggle="tooltip" data-placement="top" title="Print packing slip."><i class="ti ti-printer"></i></button>&nbsp;';
        if ((row.next_statuses || []).length) {
            html += '<button type="button" class="btn btn-sm btn-dark btn-ordermenu" data-id="' + row.id + '" data-toggle="tooltip" data-placement="top" title="More actions."><i class="ti ti-dots-vertical"></i></button>';
        }
        return html;
    }

    // Floating action menu (appended to body so the responsive table never clips it)
    function orderMenuEl() {
        var menu = document.getElementById('orderMenuFloat');
        if (!menu) {
            menu = document.createElement('div');
            menu.id = 'orderMenuFloat';
            menu.className = 'card shadow';
            menu.style.cssText = 'display:none; position:fixed; z-index:1055; min-width:190px;';
            menu.innerHTML = '<div class="list-group list-group-flush" id="orderMenuItems"></div>';
            document.body.appendChild(menu);
        }
        return menu;
    }

    function hideOrderMenu() {
        var menu = document.getElementById('orderMenuFloat');
        if (menu) menu.style.display = 'none';
    }

    function openOrderMenu(btn) {
        var menu = orderMenuEl();
        var list = document.getElementById('orderMenuItems');
        var id = $(btn).data('id');
        var row = orderRowCache[id] || null;
        if (!row) {
            var tr = btn.closest('tr');
            row = (tr && orderDataTable) ? orderDataTable.row(tr).data() : null;
        }
        if (!row) return;

        list.innerHTML = (row.next_statuses || []).map(function (status) {
            var label = orderActionLabels[status] || status;
            var danger = status === 'cancelled';
            return '<button type="button" class="list-group-item list-group-item-action btn-orderstatus ' + (danger ? 'text-danger' : '') + '" ' +
                'data-id="' + row.id + '" data-status="' + status + '" data-number="' + row.order_number + '">' +
                (danger ? '<i class="ti ti-x me-2"></i>' : '<i class="ti ti-arrow-right me-2"></i>') + label +
            '</button>';
        }).join('');

        menu.style.display = 'block';
        var rect = btn.getBoundingClientRect();
        var mw = menu.offsetWidth, mh = menu.offsetHeight;
        var top = rect.bottom + 4;
        if (top + mh > window.innerHeight - 8) top = Math.max(8, rect.top - mh - 4);
        var left = Math.min(Math.max(8, rect.right - mw), window.innerWidth - mw - 8);
        menu.style.top = top + 'px';
        menu.style.left = left + 'px';
    }

    $(document).on('click', '.btn-ordermenu', function (e) {
        e.stopPropagation();
        var menu = document.getElementById('orderMenuFloat');
        if (menu && menu.style.display === 'block' && menu.dataset.for === String($(this).data('id'))) {
            hideOrderMenu();
            return;
        }
        openOrderMenu(this);
        document.getElementById('orderMenuFloat').dataset.for = String($(this).data('id'));
    });

    $(document).on('click', function (e) {
        if (!e.target.closest('#orderMenuFloat') && !e.target.closest('.btn-ordermenu')) hideOrderMenu();
    });

    $(document).on('keydown', function (e) {
        if (e.key === 'Escape') hideOrderMenu();
    });

    $(window).on('scroll resize', hideOrderMenu);

    $(document).on('orderUpdated', function () {
        hideOrderMenu();
    });

    function changeOrderStatus(id, status, orderNumber) {
        var label = orderActionLabels[status] || status;
        var isCancel = status === 'cancelled';

        Swal.fire({
            title: isCancel ? 'Cancel this order?' : 'Move order to "' + label + '"?',
            text: isCancel
                ? 'Order ' + orderNumber + ' will be cancelled. This cannot be undone.'
                : 'Order ' + orderNumber + ' will be marked as "' + label + '".',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#000',
            cancelButtonColor: '#d33',
            confirmButtonText: isCancel ? 'Yes, cancel it!' : 'Yes, continue!'
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: orderStatusRoute,
                type: 'POST',
                data: { id: id, status: status },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $(document).trigger('orderUpdated');
                        $('#orderDetailsModal').modal('hide');
                    } else {
                        toastr.error(response.message || 'Failed to update order.');
                    }
                },
                error: function (xhr) {
                    var errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).message : 'An error occurred';
                    toastr.error(errorMessage);
                }
            });
        });
    }

    function peso(value) {
        return '₱' + Number(value || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function openOrderDetails(id) {
        $.ajax({
            url: orderDetailsRoute.replace('__ID__', id),
            type: 'GET',
            success: function (response) {
                if (!response.success) {
                    toastr.error(response.message || 'Failed to load order.');
                    return;
                }
                var order = response.order;

                $('#detailOrderNumber').text(order.order_number);
                $('#detailOrderDate').text(order.created_at);
                var pill = orderStatusPills[order.status] || [order.status, 'bg-light text-dark border'];
                $('#detailOrderStatus').attr('class', 'badge ' + pill[1]).text(pill[0]);

                $('#detailCustomer').text(order.customer_name || '—');
                $('#detailContact').text([order.customer_phone, order.customer_email].filter(Boolean).join(' · ') || '—');
                $('#detailDelivery').text(order.delivery_method === 'delivery'
                    ? 'Delivery — ' + (order.delivery_address || 'No address provided')
                    : 'Store pickup');
                $('#detailPayment').text((order.payment_method || '—') + ' · ' + (order.payment_status || '—'));

                var itemsHtml = '';
                (order.items || []).forEach(function (item) {
                    itemsHtml += '<tr>' +
                        '<td>' + (item.product_name || '—') + '</td>' +
                        '<td><small class="text-muted">' + (item.variation_name || '—') + '</small></td>' +
                        '<td class="text-center">' + item.quantity + '</td>' +
                        '<td class="text-end">' + peso(item.price) + '</td>' +
                        '<td class="text-end fw-semibold">' + peso(item.subtotal) + '</td>' +
                    '</tr>';
                });
                if (!itemsHtml) {
                    itemsHtml = '<tr><td colspan="5" class="text-center text-muted">No items.</td></tr>';
                }
                $('#detailItems').html(itemsHtml);

                $('#detailSubtotal').text(peso(order.subtotal));
                $('#detailTax').text(peso(order.tax));
                $('#detailFee').text(peso(order.delivery_fee));
                $('#detailDiscount').text('-' + peso(order.discount));
                $('#detailTotal').text(peso(order.total));
                $('#detailNotes').text(order.notes ? 'Note: ' + order.notes : '');

                var actionsHtml = '<button type="button" class="btn btn-outline-dark btn-orderprint" data-id="' + order.id + '"><i class="ti ti-printer"></i> Print Receipt</button>';
                (order.next_statuses || []).forEach(function (status) {
                    var label = orderActionLabels[status] || status;
                    actionsHtml += '<button type="button" class="btn ' + (status === 'cancelled' ? 'btn-outline-danger' : 'btn-dark') + ' btn-orderstatus" data-id="' + order.id + '" data-status="' + status + '" data-number="' + order.order_number + '">' + label + '</button>';
                });
                if (!actionsHtml) {
                    actionsHtml = '<span class="text-muted small">No further actions — order is ' + order.status + '.</span>';
                }
                $('#detailActions').html(actionsHtml);

                $('#orderDetailsModal').modal('show');
            },
            error: function (xhr) {
                var errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).message : 'An error occurred';
                toastr.error(errorMessage);
            }
        });
    }

    // Status group filter (All / Pending / Active / Ready / Delivered / Cancelled)
    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex, rowData) {
        if (settings.nTable.id !== 'orderTable' || orderStatusFilter === 'all') return true;
        var status = (rowData && rowData.status) || '';
        if (orderStatusFilter === 'active') return ['accepted', 'preparing'].includes(status);
        if (orderStatusFilter === 'ready') return ['ready_to_claim', 'ready_to_deliver', 'out_for_delivery'].includes(status);
        return status === orderStatusFilter;
    });

    $(document).ready(function () {
        orderDataTable = $('#orderTable').DataTable({
            "ajax": {
                "url": orderReadRoute,
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
                { data: 'order_number' },
                { data: 'customer_name' },
                { data: 'items_count' },
                {
                    data: 'total',
                    render: function (data) { return peso(data); }
                },
                {
                    data: 'payment_method',
                    render: function (data, type, row) {
                        return (data || '—') + ' <small class="text-muted">· ' + (row.payment_status || '') + '</small>';
                    }
                },
                {
                    data: 'status',
                    render: function (data) { return orderStatusBadge(data); }
                },
                { data: 'created_at' },
                {
                    data: 'id',
                    orderable: false,
                    render: function (data, type, row) {
                        if (type === 'display') return orderActionButtons(row);
                        return data;
                    },
                },
            ],
            "createdRow": function (row, data, index) {
                $(row).attr('id', 'tr-' + data.id);
            }
        });

        $(document).on('orderUpdated', function () {
            orderDataTable.ajax.reload(null, false);
        });

        orderDataTable.on('xhr', function (e, settings, json) {
            orderRowCache = {};
            (json.data || []).forEach(function (row) { orderRowCache[row.id] = row; });
        });

        orderDataTable.on('draw', function () {
            $('[data-toggle="tooltip"]').tooltip();
        });

        $('.order-filter').on('click', function () {
            $('.order-filter').removeClass('active btn-dark').addClass('btn-outline-dark');
            $(this).removeClass('btn-outline-dark').addClass('active btn-dark');
            orderStatusFilter = $(this).data('filter');
            orderDataTable.draw();
        });
    });

    $(document).on('click', '.btn-orderview', function () {
        openOrderDetails($(this).data('id'));
    });

    $(document).on('click', '.btn-orderstatus', function () {
        changeOrderStatus($(this).data('id'), $(this).data('status'), $(this).data('number'));
    });
</script>
