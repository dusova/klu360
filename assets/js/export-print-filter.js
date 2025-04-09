


document.addEventListener('DOMContentLoaded', function() {
    initTableFilters();
    initExportButtons();
    initPrintButtons();
});


function initTableFilters() {
    
    const filterDropdowns = document.querySelectorAll('.dropdown-menu[aria-labelledby*="FilterDropdown"] .dropdown-item');
    
    filterDropdowns.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            
            const parent = this.closest('.dropdown-menu');
            parent.querySelectorAll('.dropdown-item').forEach(el => {
                el.classList.remove('active');
            });
            this.classList.add('active');
            
            
            const dropdownBtn = document.getElementById(parent.getAttribute('aria-labelledby'));
            if (dropdownBtn) {
                const oldIcon = dropdownBtn.querySelector('i').outerHTML;
                dropdownBtn.innerHTML = oldIcon + ' ' + this.textContent.trim();
            }
            
            
            const filterType = this.getAttribute('data-filter-type');
            const filterValue = this.getAttribute('data-filter-value');
            
            
            filterTable(filterType, filterValue);
        });
    });
}


function filterTable(filterType, filterValue) {
    const table = document.querySelector('.table');
    if (!table) return;
    
    const rows = table.querySelectorAll('tbody tr');
    
    
    if (filterValue === 'all') {
        rows.forEach(row => {
            row.style.display = '';
        });
        return;
    }
    
    
    rows.forEach(row => {
        let showRow = false;
        
        
        if (filterType === 'role') {
            
            const roleCell = row.querySelector('td:nth-child(4)'); 
            if (roleCell && roleCell.textContent.toLowerCase().includes(filterValue.toLowerCase())) {
                showRow = true;
            }
        } else if (filterType === 'status') {
            
            const statusCell = row.querySelector('td:nth-child(5)'); 
            if (statusCell && statusCell.textContent.toLowerCase().includes(filterValue.toLowerCase())) {
                showRow = true;
            }
        } else if (filterType === 'campus') {
            
            const campusCell = row.querySelector('td:nth-child(3)'); 
            if (campusCell && campusCell.textContent.toLowerCase().includes(filterValue.toLowerCase())) {
                showRow = true;
            }
        } else if (filterType === 'type') {
            
            const typeCell = row.querySelector('td:nth-child(2)'); 
            if (typeCell && typeCell.textContent.toLowerCase().includes(filterValue.toLowerCase())) {
                showRow = true;
            }
        } else if (filterType === 'custom') {
            
            const filterData = row.getAttribute('data-filter');
            if (filterData && filterData.toLowerCase().includes(filterValue.toLowerCase())) {
                showRow = true;
            }
        }
        
        
        row.style.display = showRow ? '' : 'none';
    });
    
    
    checkEmptyTable();
}


function checkEmptyTable() {
    const table = document.querySelector('.table');
    if (!table) return;
    
    const tbody = table.querySelector('tbody');
    const rows = tbody.querySelectorAll('tr:not([style*="display: none"])');
    const existingEmptyMessage = tbody.querySelector('.empty-filter-message');
    
    if (rows.length === 0) {
        
        if (!existingEmptyMessage) {
            const emptyRow = document.createElement('tr');
            emptyRow.className = 'empty-filter-message';
            emptyRow.innerHTML = `
                <td colspan="${table.querySelectorAll('th').length}" class="text-center py-4">
                    <i class="bi bi-filter text-muted mb-2" style="font-size: 2rem;"></i>
                    <p class="mb-0">Filtreleme kriterlerine uygun sonuç bulunamadı.</p>
                    <button class="btn btn-sm btn-outline-secondary mt-2 reset-filter">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Filtreyi Sıfırla
                    </button>
                </td>
            `;
            tbody.appendChild(emptyRow);
            
            
            emptyRow.querySelector('.reset-filter').addEventListener('click', function() {
                const allFilterBtn = document.querySelector('.dropdown-item[data-filter-value="all"]');
                if (allFilterBtn) {
                    allFilterBtn.click();
                } else {
                    
                    table.querySelectorAll('tbody tr').forEach(row => {
                        row.style.display = '';
                    });
                    emptyRow.remove();
                }
            });
        }
    } else if (existingEmptyMessage) {
        
        existingEmptyMessage.remove();
    }
}


function initExportButtons() {
    
    const excelBtns = document.querySelectorAll('[data-export="excel"]');
    excelBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const table = getTableForExport(this);
            if (!table) return;
            
            exportTableToExcel(table, this.getAttribute('data-filename') || 'tablo-verisi');
        });
    });
    
    
    const pdfBtns = document.querySelectorAll('[data-export="pdf"]');
    pdfBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const table = getTableForExport(this);
            if (!table) return;
            
            exportTableToPDF(table, this.getAttribute('data-filename') || 'tablo-verisi');
        });
    });
}


function getTableForExport(button) {
    
    const tableId = button.getAttribute('data-table');
    if (tableId) {
        return document.getElementById(tableId);
    }
    
    
    
    const card = button.closest('.card');
    if (card) {
        const table = card.querySelector('table');
        if (table) return table;
    }
    
    
    const contentBox = button.closest('.content-box');
    if (contentBox) {
        const table = contentBox.querySelector('table');
        if (table) return table;
    }
    
    
    return document.querySelector('table');
}


function exportTableToExcel(table, filename) {
    const rows = Array.from(table.querySelectorAll('tr:not(.empty-filter-message)'));
    
    
    const visibleRows = rows.filter(row => {
        return window.getComputedStyle(row).display !== 'none';
    });
    
    let csvContent = "data:text/csv;charset=utf-8,\uFEFF"; 
    
    visibleRows.forEach(row => {
        const cells = Array.from(row.querySelectorAll('th, td'));
        const rowData = cells.map(cell => {
            
            const badge = cell.querySelector('.badge');
            if (badge) {
                return badge.textContent.trim();
            }
            
            
            let content = cell.innerText.replace(/\\n/g, ' ').trim();
            
            
            content = '"' + content.replace(/"/g, '""') + '"';
            
            return content;
        });
        
        csvContent += rowData.join(',') + '\r\n';
    });
    
    
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement('a');
    link.setAttribute('href', encodedUri);
    link.setAttribute('download', filename + '.csv');
    document.body.appendChild(link);
    
    
    link.click();
    
    
    document.body.removeChild(link);
}


function exportTableToPDF(table, filename) {
    
    printTable(table, filename);
}


function initPrintButtons() {
    const printBtns = document.querySelectorAll('[data-print="table"]');
    printBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const table = getTableForExport(this);
            if (!table) return;
            
            printTable(table, this.getAttribute('data-filename') || 'Tablo Yazdırma');
        });
    });
}


function printTable(table, title) {
    
    const printWindow = window.open('', '_blank');
    
    
    const rows = Array.from(table.querySelectorAll('tr'));
    const visibleRows = rows.filter(row => {
        return window.getComputedStyle(row).display !== 'none';
    });
    
    
    printWindow.document.write(`
        <!DOCTYPE html>
        <html lang="tr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>${title}</title>
            <style>
                body {
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    margin: 20px;
                }
                h1 {
                    font-size: 18px;
                    margin-bottom: 15px;
                    color: #333;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }
                th, td {
                    padding: 10px;
                    border: 1px solid #ddd;
                    text-align: left;
                }
                th {
                    background-color: #f5f5f5;
                    font-weight: bold;
                }
                .print-footer {
                    margin-top: 20px;
                    font-size: 12px;
                    color: #777;
                    text-align: center;
                }
                @media print {
                    .no-print {
                        display: none;
                    }
                }
            </style>
        </head>
        <body>
            <h1>${title}</h1>
            <table>
                ${visibleRows.map(row => {
                    const cells = Array.from(row.querySelectorAll('th, td'));
                    return `<tr>${cells.map(cell => {
                        
                        const badge = cell.querySelector('.badge');
                        if (badge) {
                            return `<td>${badge.textContent.trim()}</td>`;
                        }
                        
                        return `<${cell.nodeName.toLowerCase()}>${cell.innerHTML}</${cell.nodeName.toLowerCase()}>`;
                    }).join('')}</tr>`;
                }).join('')}
            </table>
            <div class="print-footer">
                Yazdırma Tarihi: ${new Date().toLocaleString('tr-TR')}
            </div>
            <div class="no-print" style="margin-top: 20px; text-align: center;">
                <button onclick="window.print();" style="padding: 8px 16px;">Yazdır</button>
                <button onclick="window.close();" style="padding: 8px 16px; margin-left: 10px;">Kapat</button>
            </div>
            <script>
                window.onload = function() {
                    
                    window.print();
                };
            </script>
        </body>
        </html>
    `);
    
    printWindow.document.close();
}