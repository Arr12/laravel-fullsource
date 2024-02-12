function confirmDelete() {
    Swal.fire({
        title: "Do you want to Delete this line?",
        showCancelButton: true,
        confirmButtonText: "Save",
    }).then((willDelete) => {
        if (willDelete) {
            document.getElementById("delete-form").submit();
        } else {
            return false;
        }
    });
}
