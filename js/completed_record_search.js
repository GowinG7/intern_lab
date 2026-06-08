document.addEventListener("DOMContentLoaded", function () {
  const input = document.getElementById("liveSearchInput");
  const btn = document.getElementById("searchBtn");
  const tbody = document.getElementById("reportTableBody");
  const info = document.getElementById("searchResultInfo");

  if (!input || !tbody) return;

  let timer;

  function doSearch() {
    const search = input.value.trim();

    fetch("search_completed_reports.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        search: search,
      }),
    })
      .then((res) => res.json())

      .then((data) => {
        let html = "";

        if (data.success && data.data.length > 0) {
          data.data.forEach((row, index) => {
            html += `
                    <tr>

                        <td>${index + 1}</td>

                        <td>${row.record_number}</td>

                        <td>${row.report_year}</td>

                        <td>${row.full_name}</td>

                        <td>${row.hospital_number}</td>

                        <td>${row.biopsy_site || ""}</td>

                        <td>
                            <span class="status-badge Completed">
                                ${row.report_status}
                            </span>
                        </td>

                        <td>
                            <a href="view_report_pdf.php?id=${row.id}"
                               class="btn btn-warning btn-sm">
                               View
                            </a>
                        </td>

                    </tr>`;
          });

          tbody.innerHTML = html;

          if (info) {
            info.textContent = `Found ${data.data.length} result(s)`;
          }
        } else {
          tbody.innerHTML = `
                    <tr>
                        <td colspan="8" class="text-center">
                            No records found
                        </td>
                    </tr>`;

          if (info) {
            info.textContent = "No results";
          }
        }
      })

      .catch((error) => {
        console.error(error);

        tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center text-danger">
                        Error loading records
                    </td>
                </tr>`;

        if (info) {
          info.textContent = "Search failed";
        }
      });
  }

  input.addEventListener("keyup", function () {
    clearTimeout(timer);

    timer = setTimeout(doSearch, 300);
  });

  btn.addEventListener("click", doSearch);

  input.addEventListener("keypress", function (e) {
    if (e.key === "Enter") {
      e.preventDefault();

      doSearch();
    }
  });
});
