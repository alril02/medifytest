<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#table').DataTable({
            searching: false,
            order: [[0, 'desc']],
        });
        getData();
    });

    $('.btn-get-data').click(function() {
        getData();
    });

    function getData() {
        $('#loading-filter').show();
        var dataTableObj = $('#table').DataTable();
        var filter_nama = $('#filter-nama').val();
        var filter_kode = $('#filter-kode').val();
        dataTableObj.clear().draw();

        $.ajax({
            url: '{{ url("kategori/search") }}',
            dataType: 'json',
            tryCount: 0,
            retryLimit: 3,
            data: 'nama=' + encodeURIComponent(filter_nama) + '&kode=' + encodeURIComponent(filter_kode),
            success: function(results) {
                var data = results.data;

                $.each(data, function(index, item) {
                    var array_temp = [];
                    var html = '<a href="{{ url("kategori/view") }}/' + item.id + '" class="btn btn-sm btn-primary me-1">View</a>';
                    html += '<a href="{{ url("kategori/form/edit") }}/' + item.id + '" class="btn btn-sm btn-secondary me-1">Edit</a>';
                    html += '<a href="{{ url("kategori/delete") }}/' + item.id + '" class="btn btn-sm btn-danger" onclick="return confirm(\'Apakah Anda yakin ingin menghapus kategori ini?\');">Delete</a>';

                    array_temp.push(item.id);
                    array_temp.push(item.nama);
                    array_temp.push(item.kode);
                    array_temp.push(html);

                    dataTableObj.row.add(array_temp).draw(true);
                });
                $('#loading-filter').hide();
            },
            error: function(xhr, textStatus, errorThrown) {
                this.tryCount++;
                if (this.tryCount <= this.retryLimit) {
                    $.ajax(this);
                    return;
                }
                alert('Terjadi kesalahan server, tidak dapat mengambil data');
                $('#loading-filter').hide();
            }
        });
    }
</script>
