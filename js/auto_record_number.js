/**
 * Auto Record Number Generator
 * Fetches the next record number based on selected histopathology series (A or B)
 * and current year
 */

document.addEventListener('DOMContentLoaded', function() {
    const histopathologySelect = document.getElementById('histopathology_number');
    const recordNumberInput = document.getElementById('record_number');
    const yearInput = document.querySelector('input[name="year"]');

    // Listen for changes in histopathology number dropdown
    if (histopathologySelect) {
        histopathologySelect.addEventListener('change', function() {
            const selectedLetter = this.value;
            const currentYear = yearInput.value;

            if (selectedLetter && currentYear) {
                fetchNextRecordNumber(selectedLetter, currentYear);
            } else {
                recordNumberInput.value = '';
            }
        });
    }

    /**
     * Fetch the next record number from the server
     * @param {string} letter - The histopathology letter (A or B)
     * @param {string} year - The current year
     */
    function fetchNextRecordNumber(letter, year) {
        // Show loading state
        recordNumberInput.value = 'Loading...';
        recordNumberInput.style.color = '#6c757d';

        fetch('../admin/get_next_record_number.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                histopathology_number: letter,
                year: year
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                recordNumberInput.value = data.next_record_number;
                recordNumberInput.style.color = '#000';
            } else {
                recordNumberInput.value = '';
                console.error('Error:', data.message);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            recordNumberInput.value = '';
            recordNumberInput.style.color = '#dc3545';
        });
    }
});
