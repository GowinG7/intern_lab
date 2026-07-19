document.querySelectorAll("[data-date-filter]").forEach((button) => {
    button.addEventListener("click", () => {
        const today = new Date();
        const fromDate = new Date(today);

        switch (button.dataset.dateFilter) {
            case "last7d":
                fromDate.setDate(fromDate.getDate() - 7);
                break;
            case "last30d":
                fromDate.setDate(fromDate.getDate() - 30);
                break;
            case "thismonth":
                fromDate.setDate(1);
                break;
        }

        const formatDate = (date) => {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, "0");
            const day = String(date.getDate()).padStart(2, "0");

            return `${year}-${month}-${day}`;
        };
        document.getElementById("from_date").value = formatDate(fromDate);
        document.getElementById("to_date").value = formatDate(today);
        document.querySelector(".filter-form").submit();
    });
});
