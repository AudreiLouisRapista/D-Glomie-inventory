  let rc = 0;

        const suppliers = {
            acme: {
                addr: '123 Supplier St., Manila',
                tin: '123-456-789-000'
            },
            global: {
                addr: '45 Trade Ave., Cebu City',
                tin: '456-789-012-000'
            },
            prime: {
                addr: '7 Prime Rd., Davao City',
                tin: '789-012-345-000'
            },
        };

        function fillSupplier(sel) {
            const show = !!sel.value;
            document.getElementById('sup-extra').style.display = show ? 'flex' : 'none';
            if (!show) return;
            document.getElementById('sup-addr').value = suppliers[sel.value].addr;
            document.getElementById('sup-tin').value = suppliers[sel.value].tin;
        }

        function addRow() {
            rc++;
            const tbody = document.getElementById('items-body');
            const tr = document.createElement('tr');
            tr.id = 'r' + rc;
            tr.innerHTML = `
            <td>
                <input type="text" class="form-control" placeholder="Item description">
            </td>
            <td>
                <input type="number" class="form-control text-center" value="1" min="1" oninput="calc(${rc})">
            </td>
            <td>
                <input type="number" class="form-control text-center" value="0" min="0" step="0.01" oninput="calc(${rc})">
            </td>
            <td class="td-total" id="tot${rc}">₱0.00</td>
            <td class="text-center">
                <button type="button" class="btn-del" onclick="delRow(${rc})">
                    <i class="fas fa-trash"></i>
                </button>
            </td>`;
            tbody.appendChild(tr);
            recalc();
        }

        function delRow(id) {
            const tr = document.getElementById('r' + id);
            if (tr) tr.remove();
            recalc();
        }

        function calc(id) {
            const tr = document.getElementById('r' + id);
            if (!tr) return;
            const inputs = tr.querySelectorAll('input[type=number]');
            const qty = parseFloat(inputs[0].value) || 0;
            const price = parseFloat(inputs[1].value) || 0;
            const total = qty * price;
            document.getElementById('tot' + id).textContent = formatPHP(total);
            recalc();
        }

        function recalc() {
            let net = 0;
            document.querySelectorAll('#items-body tr').forEach(function(tr) {
                const id = tr.id.replace('r', '');
                const el = document.getElementById('tot' + id);
                if (el) net += parseFloat(el.textContent.replace(/[₱,]/g, '')) || 0;
            });
            const vat = net * 0.12;
            const gross = net + vat;
            document.getElementById('s-net').textContent = formatPHP(net);
            document.getElementById('s-vat').textContent = formatPHP(vat);
            document.getElementById('s-gross').textContent = formatPHP(gross);
        }

        function formatPHP(value) {
            return '₱' + value.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }

        function saveInvoice() {
            const invoiceNo = document.getElementById('invoice_number').value.trim();
            const supplier = document.getElementById('supplier_select').value;
            const invoiceDt = document.getElementById('invoice_date').value;
            const dueDt = document.getElementById('due_date').value;

            if (!invoiceNo || !supplier || !invoiceDt || !dueDt) {
                alert('Please fill in all required fields.');
                return;
            }

            // TODO: Replace with your actual AJAX / form submission
            alert('Invoice saved successfully!');
        }

        // Start with 1 empty row
        addRow();
  