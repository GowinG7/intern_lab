document.addEventListener("DOMContentLoaded", function () {
  const histopathologySelect = document.getElementById("histopathology_number");
  const recordNumberInput = document.getElementById("record_number");
  const yearInput = document.querySelector('input[name="year"]');

  if (!histopathologySelect || !recordNumberInput || !yearInput) {
    return;
  }

  generateRecordNumber();

  histopathologySelect.addEventListener("change", generateRecordNumber);

  yearInput.addEventListener("change", generateRecordNumber);

  function generateRecordNumber() {
    const letter = histopathologySelect.value;
    const year = yearInput.value;

    if (!letter || !year) {
      recordNumberInput.value = "";
      return;
    }

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
        console.log("Server Response:", data);

        if (data.success) {
          recordNumberInput.value = data.next_record_number;
        } else {
          recordNumberInput.value = "";
          console.error(data.message);
        }
      })
      .catch((error) => {
        console.error(error);
        recordNumberInput.value = "";
      });
  }
});
