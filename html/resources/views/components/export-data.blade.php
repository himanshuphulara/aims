<script>
    function formatDateTime() {
    var now = new Date();
    var year = now.getFullYear();
    var month = String(now.getMonth() + 1).padStart(2, '0'); // Months are 0-based
    var day = String(now.getDate()).padStart(2, '0');
    var hours = String(now.getHours()).padStart(2, '0');
    var minutes = String(now.getMinutes()).padStart(2, '0');
    var seconds = String(now.getSeconds()).padStart(2, '0');

    // return `${year}-${month}-${day}_${hours}-${minutes}-${seconds}`; // e.g., "2024-10-03_14-30-45"
    return `${day}-${month}-${year}`; // e.g., "2024-10-03_14-30-45"
}
var table = $('#grid-selection').DataTable();
var columns = @json($columns);
$("#btnExport").click(function(e) {
    // Convert filteredData to an array if it's not already
    var filteredData = Array.from(table.rows({ filter: 'applied' }).data());
    const name = $(this).attr('ledger');
    if(filteredData.length > 0){
    // Start building the Excel data
    var excelData = '<table border=1><thead><tr>';
    
    // Create header row using columns
    columns.forEach(function(column) {
        excelData += '<th>' + column + '</th>'; // Use the column name directly
    });
    excelData += '</tr></thead><tbody>';
    var total = 0;
    // Create data rows
    filteredData.forEach(function(item) {
        excelData += '<tr>';
        columns.forEach(function(column) {
            if(column=='amt'){
                total+=parseFloat(item[column]);                                
            }
            excelData += '<td>' + (item[column] !== undefined ? item[column] : '') + '</td>'; // Ensure the property exists
        });
        excelData += '</tr>';
    });
    if(total!=0){
        excelData +=`<th colspan=6>Total</th><th>${total}</th>`;
    }
    excelData += `</tbody></table>`;
    // console.log(total)
    var dataURI = 'data:application/vnd.ms-excel;charset=utf-8,' + encodeURIComponent(excelData);

    // Create a temporary link element to trigger the download
    var link = document.createElement("a");
    link.href = dataURI;
    var timestamp = formatDateTime();
    link.download = `${name}-export-${timestamp}.xls`; // Specify the file name

    // Append to the body
    document.body.appendChild(link);
    
    // Trigger the download
    link.click();
    
    // Clean up and remove the link
    document.body.removeChild(link);
    toastr.success('File Export Successfully', 'Success');
    }else{
        toastr.error('Current selection is Empty', 'Error');
    }
});


</script>