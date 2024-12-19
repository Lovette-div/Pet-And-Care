document.addEventListener('DOMContentLoaded', function() {
    // Create new tip form handler
    const createTipForm = document.getElementById('createTipForm');
    if (createTipForm) {
        createTipForm.addEventListener('submit', function(e) {
            e.preventDefault();
            submitNewTip(this);
        });
    }

    // Comment form handler
    const commentForm = document.getElementById('commentForm');
    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            submitComment(this);
        });
    }
});

function submitNewTip(form) {
    const formData = new FormData(form);

    fetch('../backend/create_tip.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Tip created successfully!');
            window.location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while creating the tip.');
    });
}

function submitComment(form) {
    const formData = new FormData(form);

    fetch('../backend/add_comment.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Comment added successfully!');
            // Option to update comments dynamically instead of reloading
            window.location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while adding the comment.');
    });
}