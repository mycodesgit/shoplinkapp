<script>
    // Function to use your custom toast
    function showCustomToast(message, isError = false) {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toastMessage');
        const icon = toast.querySelector('i');
        
        toastMessage.textContent = message;
        
        if (isError) {
            icon.className = 'fas fa-exclamation-circle text-red-400 mr-2';
        } else {
            icon.className = 'fas fa-check-circle text-green-400 mr-2';
        }
        
        toast.classList.remove('opacity-0', 'pointer-events-none');
        toast.classList.add('opacity-100', 'pointer-events-auto');
        
        setTimeout(() => {
            toast.classList.remove('opacity-100', 'pointer-events-auto');
            toast.classList.add('opacity-0', 'pointer-events-none');
        }, 3000);
    }

    $(document).ready(function() {
        $('#addUser').submit(function(event) {
            event.preventDefault();
            var formData = $(this).serialize();

            $.ajax({
                url: "{{ route('shop.register.create') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    if(response.success) {
                        showCustomToast(response.message);
                        console.log(response);
                        $('input[name="fname"]').val('');
                        $('input[name="mname"]').val('');
                        $('input[name="lname"]').val('');
                        $('input[name="email"]').val('');
                        $('input[name="password"]').val('');
                    } else {
                        showCustomToast(response.message, true);
                        console.log(response);
                    }
                },
                error: function(xhr, status, error, message) {
                    var errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).message : 'An error occurred';
                    showCustomToast(errorMessage, true);
                }
            });
        });
    });
</script>