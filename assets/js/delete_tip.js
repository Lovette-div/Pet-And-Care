// Delete a tip
document.querySelectorAll('.delete-tip-button').forEach(button => {
    button.addEventListener('click', function() {
        const confirmation = confirm("Are you sure you want to delete this tip?");
        if (confirmation) {
            const tipId = this.dataset.tipId; // Fetch the tip ID from a data attribute

            // Send the delete request via AJAX
            fetch('../actions/tips_backend.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ tip_id: tipId }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const row = this.closest('tr'); // Find the table row containing the tip
                    row.remove(); // Remove the row from the table
                    alert("Tip deleted successfully.");
                } else {
                    alert("Failed to delete the tip: " + data.message);
                }
            })
            .catch(error => {
                console.error("Error deleting tip:", error);
                alert("An error occurred. Please try again.");
            });
        }
    });
});
