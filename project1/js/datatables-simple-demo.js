window.addEventListener('DOMContentLoaded', event => {
    // Get the table element by its id
    const datatablesSimple = document.getElementById('datatablesSimple');

    // Check if the table element exists
    if (datatablesSimple) {
        // Initialize a new instance of the DataTable on the table element
        new simpleDatatables.DataTable(datatablesSimple);
    }
});
