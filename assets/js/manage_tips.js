$(document).ready(function() {
    // Add Tip
    // $('#addTipForm').submit(function(e) {
    //     e.preventDefault();
        
    //     var formData = $(this).serialize(); // Serialize form data

    //     $.ajax({
    //         url: 'manage_tips_backend.php', // PHP backend file for creating a tip
    //         type: 'POST',
    //         data: formData + '&action=create',
    //         success: function(response) {
    //             var res = JSON.parse(response);
    //             alert(res.message);
    //             if (res.success) {
    //                 $('#addTipModal').modal('hide');
    //                 location.reload(); // Reload the page to see the new tip
    //             }
    //         },
    //         error: function() {
    //             alert('Error creating tip');
    //         }
    //     });
    // });

    // Edit Tip (Open Modal)
    $('.editBtn').click(function() {
        var tip_id = $(this).closest('tr').data('tip-id');
        
        // Fetch current tip data
        var row = $(this).closest('tr');
        var title = row.find('td:eq(1)').text();
        var content = row.find('td:eq(2)').text();
        
        // Set values in the edit form
        $('#editTipModal').find('#edit_tip_id').val(tip_id);
        $('#editTipModal').find('#edit_title').val(title);
        $('#editTipModal').find('#edit_content').val(content);

        $('#editTipModal').modal('show');
    });

    // Update Tip
    $('#editTipForm').submit(function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize(); // Serialize form data

        $.ajax({
            url: '../actions/tips_backend.php', // PHP backend file for updating the tip
            type: 'POST',
            data: formData + '&action=update',
            success: function(response) {
                var res = JSON.parse(response);
                alert(res.message);
                if (res.success) {
                    $('#editTipModal').modal('hide');
                    location.reload(); // Reload the page to see the updated tip
                }
            },
            error: function() {
                alert('Error updating tip');
            }
        });
    });

    // Delete Tip
    window.deleteTip = function(tip_id) {
        if (confirm("Are you sure you want to delete this tip?")) {
            $.ajax({
                url: '../actions/tips_backend.php',
                type: 'POST',
                data: { action: 'delete', tip_id: tip_id },
                success: function(response) {
                    var res = JSON.parse(response);
                    alert(res.message);
                    if (res.success) {
                        location.reload(); // Reload the page to see the changes
                    }
                },
                error: function() {
                    alert("Error deleting tip");
                }
            });
        }
    };
});
