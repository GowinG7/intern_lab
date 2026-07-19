function bindPathologistToggle(checkboxId, selectId) {
    const checkbox = document.getElementById(checkboxId);
    const select = document.getElementById(selectId);

    if (!checkbox || !select) {
        return;
    }

    checkbox.addEventListener("change", () => {
        select.disabled = !checkbox.checked;

        if (!checkbox.checked) {
            select.selectedIndex = 0;
        }
    });
}

bindPathologistToggle("patho", "pathologist");
bindPathologistToggle("consultant_patho", "consultant_pathologist");
