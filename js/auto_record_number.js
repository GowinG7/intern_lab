document.addEventListener("DOMContentLoaded", function () {
  // Get DOM elements
  const histopathologySelect = document.getElementById("histopathology_number");
  const recordNumberInput = document.getElementById("record_number");
  const yearInput = document.querySelector('input[name="year"]');
  const reportForm = document.getElementById("reportForm");
  const recordWarning = document.getElementById("recordWarning");
  const recordWarningText = document.getElementById("recordWarningText");

  let isProgrammaticSubmit = false;

  // Validate elements exist
  if (
    !histopathologySelect ||
    !recordNumberInput ||
    !yearInput ||
    !reportForm
  ) {
    console.error("Auto Record Number: Required form elements not found");
    return;
  }

  console.log("Auto Record Number: Initialization started", {
    histopathology_value: histopathologySelect.value,
    year_value: yearInput.value,
  });

  // Trigger record number generation on page load
  setTimeout(() => {
    console.log("Auto Record Number: Triggering initial generation");
    generateRecordNumber();
  }, 250);

  // Listen for changes in histopathology number dropdown
  histopathologySelect.addEventListener("change", function () {
    console.log("Auto Record Number: Dropdown changed to", this.value);
    generateRecordNumber();
  });

  // Listen for year field changes (if user changes year)
  yearInput.addEventListener("change", function () {
    console.log("Auto Record Number: Year changed to", this.value);
    generateRecordNumber();
  });

  reportForm.addEventListener("submit", function (event) {
    if (isProgrammaticSubmit) {
      return;
    }

    event.preventDefault();

    const selectedLetter = histopathologySelect.value;
    const currentYear = yearInput.value;

    if (!selectedLetter || selectedLetter === "select" || !currentYear) {
      return;
    }

    fetchLatestRecordNumber(selectedLetter, currentYear).then(
      (latestRecordNumber) => {
        if (!latestRecordNumber) {
          showWarning(
            "Unable to verify the record number right now. Please try again.",
          );
          return;
        }

        if (recordNumberInput.value !== latestRecordNumber) {
          recordNumberInput.value = latestRecordNumber;
          showWarning(
            `Record number was updated to ${latestRecordNumber} because the previous one was already used for this year.`,
          );
        } else {
          hideWarning();
        }

        isProgrammaticSubmit = true;
        reportForm.requestSubmit();
      },
    );
  });

  /**
   * Generate the record number
   */
  function generateRecordNumber() {
    const selectedLetter = histopathologySelect.value;
    const currentYear = yearInput.value;

    // Skip if no letter selected (empty value)
    if (
      !selectedLetter ||
      selectedLetter === "select" ||
      selectedLetter === ""
    ) {
      recordNumberInput.value = "";
      console.log("Auto Record Number: No valid selection");
      return;
    }

    if (!currentYear) {
      recordNumberInput.value = "";
      console.log("Auto Record Number: No year available");
      return;
    }

    console.log("Auto Record Number: Fetching for", {
      letter: selectedLetter,
      year: currentYear,
    });
    fetchNextRecordNumber(selectedLetter, currentYear);
  }

  /**
   * Fetch the next record number from the server
   * @param {string} letter - The histopathology letter (A or B)
   * @param {string} year - The current year
   */
  function fetchNextRecordNumber(letter, year) {
    // Show loading state
    recordNumberInput.value = "Loading...";
    recordNumberInput.style.color = "#6c757d";

    fetch("report_form.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        histopathology_number: letter,
        year: year,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          recordNumberInput.value = data.next_record_number;
          recordNumberInput.style.color = "#000";
        } else {
          recordNumberInput.value = "";
          console.error("Error:", data.message);
          recordNumberInput.style.color = "#dc3545";
        }
      })
      .catch((error) => {
        console.error("Fetch error:", error);
        recordNumberInput.value = "";
        recordNumberInput.style.color = "#dc3545";
      });
  }

  function fetchLatestRecordNumber(letter, year) {
    return fetch("report_form.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        histopathology_number: letter,
        year: year,
      }),
    })
      .then((response) => response.json())
      .then((data) => (data.success ? data.next_record_number : null))
      .catch((error) => {
        console.error("Fetch error:", error);
        return null;
      });
  }

  function showWarning(message) {
    if (!recordWarning || !recordWarningText) {
      return;
    }

    recordWarningText.textContent = message;
    recordWarning.classList.remove("d-none");
  }

  function hideWarning() {
    if (!recordWarning || !recordWarningText) {
      return;
    }

    recordWarningText.textContent = "";
    recordWarning.classList.add("d-none");
  }
});
