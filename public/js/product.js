$(document).ready(function() {
    $('#example2').DataTable({
        
        "aaSorting": [] // This stops DataTables from overriding your backend order on load
    });
});