function showModalFaq() {
    document.getElementById('addFaqModal').style.display = 'flex';
}

function hideFaqModal() {
    document.getElementById('addFaqModal').style.display = 'none';
    
}

document.getElementById('addFaqModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideFaqModal();
    }
});

function closeErrorAlert() {
    document.getElementById('errorAlert').style.display = 'none';
}
function closeSuccessAlert() {
    document.getElementById('successAlert').style.display = 'none';
}
