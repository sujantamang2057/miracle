@push('page_scripts')
    <script type="text/javascript">
        function saveRowReorder(table, route) {
            table.on('row-reorder', function(e, diff, edit) {
                var myArray = [];
                for (var i = 0, ien = diff.length; i < ien; i++) {
                    var rowData = table.row(diff[i].node).data();
                    myArray.push({
                        'index': rowData['DT_RowAttr']['data-index'],
                        'position': rowData['DT_RowAttr']['data-position'],
                    });
                }
                var jsonString = JSON.stringify(myArray);
                $.ajax({
                    url: route,
                    type: 'POST',
                    data: jsonString,
                    dataType: 'json',
                    success: function(json) {
                        $('#dataTableBuilder').DataTable().ajax.reload();
                        $.each(json, function(key, msg) {
                            // handle json response here!
                        });
                    }
                });
            });
        }
    </script>
@endpush
