document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('liveSearchInput');
    const tableRows = document.querySelectorAll('table tbody tr');

    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase().trim();
            let visibleCount = 0;

            tableRows.forEach(row => {
                const cells = row.querySelectorAll('td');
                let rowMatch = false;

                // Search in: Patient Name (index 3), Record Number (index 1), Hospital Number (index 4), Year (index 2)
                const patientName = cells[3]?.textContent.toLowerCase() || '';
                const recordNumber = cells[1]?.textContent.toLowerCase() || '';
                const hospitalNumber = cells[4]?.textContent.toLowerCase() || '';
                const year = cells[2]?.textContent.toLowerCase() || '';

                if (searchTerm === '' || 
                    patientName.includes(searchTerm) || 
                    recordNumber.includes(searchTerm) || 
                    hospitalNumber.includes(searchTerm) ||
                    year.includes(searchTerm)) {
                    row.style.display = '';
                    rowMatch = true;
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Update result count
            const resultInfo = document.getElementById('searchResultInfo');
            if (resultInfo) {
                if (searchTerm === '') {
                    resultInfo.textContent = '';
                } else {
                    resultInfo.textContent = `Found ${visibleCount} result(s)`;
                }
            }
        });
    }
});
