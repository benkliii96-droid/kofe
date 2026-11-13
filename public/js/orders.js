function initOrderForm(config) {
    var products = [];
    var tbody = document.querySelector('#items-table tbody');
    var addBtn = document.getElementById('add-item');
    var readonly = !!config.readonly;
    var index = 0;

    fetch(config.apiUrl)
        .then(function (r) { return r.json(); })
        .then(function (data) {
            products = data;
            if (config.items && config.items.length) {
                config.items.forEach(function (item) {
                    addRow(item);
                });
            } else if (!readonly) {
                addRow(null);
            }
        });

    if (addBtn) {
        addBtn.addEventListener('click', function () {
            addRow(null);
        });
    }

    function addRow(item) {
        var i = index++;
        var tr = document.createElement('tr');
        var selectedId = item ? item.product_id : '';
        var discount = item ? item.discount : 0;
        var quantity = item ? item.quantity : 1;

        var options = '<option value="">Выберите</option>';
        products.forEach(function (p) {
            options += '<option value="' + p.id + '"' + (String(p.id) === String(selectedId) ? ' selected' : '') + '>' + p.name + '</option>';
        });

        tr.innerHTML =
            '<td><select name="items[' + i + '][product_id]" class="product-select" required ' + (readonly ? 'disabled' : '') + '>' + options + '</select>' +
            (readonly && selectedId ? '<input type="hidden" name="items[' + i + '][product_id]" value="' + selectedId + '">' : '') + '</td>' +
            '<td class="photo-cell"><img src="" alt="" class="thumb" style="display:none"></td>' +
            '<td class="unit-cell">—</td>' +
            '<td class="price-cell">—</td>' +
            '<td><input type="number" step="0.01" min="0" max="100" name="items[' + i + '][discount]" value="' + discount + '" class="discount-input" ' + (readonly ? 'readonly' : '') + '></td>' +
            '<td><input type="number" min="1" name="items[' + i + '][quantity]" value="' + quantity + '" class="qty-input" required ' + (readonly ? 'readonly' : '') + '></td>' +
            '<td class="sum-cell">0</td>' +
            '<td>' + (readonly ? '' : '<button type="button" class="btn btn-small btn-danger remove-item">×</button>') + '</td>';

        tbody.appendChild(tr);
        bindRow(tr);

        if (selectedId) {
            updateRow(tr);
        }
    }

    function bindRow(tr) {
        var select = tr.querySelector('.product-select');
        var discount = tr.querySelector('.discount-input');
        var qty = tr.querySelector('.qty-input');
        var remove = tr.querySelector('.remove-item');

        select.addEventListener('change', function () { updateRow(tr); });
        discount.addEventListener('input', function () { updateRow(tr); });
        qty.addEventListener('input', function () { updateRow(tr); });

        if (remove) {
            remove.addEventListener('click', function () {
                tr.remove();
            });
        }
    }

    function updateRow(tr) {
        var select = tr.querySelector('.product-select');
        var product = products.find(function (p) { return String(p.id) === String(select.value); });
        var img = tr.querySelector('.photo-cell img');
        var discount = parseFloat(tr.querySelector('.discount-input').value) || 0;
        var qty = parseInt(tr.querySelector('.qty-input').value, 10) || 1;

        if (!product) {
            tr.querySelector('.unit-cell').textContent = '—';
            tr.querySelector('.price-cell').textContent = '—';
            tr.querySelector('.sum-cell').textContent = '0';
            img.style.display = 'none';
            return;
        }

        tr.querySelector('.unit-cell').textContent = product.unit;
        tr.querySelector('.price-cell').textContent = product.price.toFixed(2);
        img.src = product.photo;
        img.style.display = 'block';

        var sum = (product.price - (product.price / 100 * discount)) * qty;
        tr.querySelector('.sum-cell').textContent = sum.toFixed(2);
    }
}
