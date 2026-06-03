document.addEventListener("DOMContentLoaded", function () {
//   console.log("Live search script loaded.");

  const searchInput = document.getElementById("liveSearchInput");
  const tableRows = document.querySelectorAll("table tbody tr");

//   console.log("Rows found:", tableRows.length);

  if (searchInput) {
    searchInput.addEventListener("keyup", function () {
      const searchTerm = this.value.toLowerCase().trim();
      let visibleCount = 0;

      tableRows.forEach((row) => {
        const cells = row.querySelectorAll("td");

        const patientName = cells[3]?.textContent.toLowerCase().trim() || "";
        const recordNumber = cells[1]?.textContent.toLowerCase().trim() || "";
        const hospitalNumber = cells[4]?.textContent.toLowerCase().trim() || "";
        const year = cells[2]?.textContent.toLowerCase().trim() || "";
        const biopsySite = cells[5]?.textContent.toLowerCase().trim() || "";

        // console.log("Biopsy Site:", biopsySite);

        const match =
          searchTerm === "" ||
          patientName.includes(searchTerm) ||
          recordNumber.includes(searchTerm) ||
          hospitalNumber.includes(searchTerm) ||
          year.includes(searchTerm) ||
          biopsySite.includes(searchTerm);

        if (match) {
          row.style.display = "";
          visibleCount++;
        } else {
          row.style.display = "none";
        }
      });

      const resultInfo = document.getElementById("searchResultInfo");

      if (resultInfo) {
        resultInfo.textContent =
          searchTerm === "" ? "" : `Found ${visibleCount} result(s)`;
      }
    });
  }
});
