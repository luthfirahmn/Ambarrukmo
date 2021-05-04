$(document).ready(function () {
	$("#example1")
		.DataTable({
			responsive: true,
			lengthChange: false,
			autoWidth: false,
			serverSide: true,
			processing: true,
			// "order": [[ 3, "desc" ]],
			paging: true,
			columnDefs: [
				{ "width": "10px", "targets": 0 }
			  ],
			searching: { regex: true },
			lengthMenu: [
				[10, 25, 50, 100, -1],
				[10, 25, 50, 100, "All"],
			],
			pageLength: 10,
			dom:
				"<'row'<'col-sm-6'B><'col-sm-6'f>>" +
				"<'row'<'col-sm-12'tr>>" +
				"<'row'<'col-sm-4'i><'col-sm-4 text-center'l><'col-sm-4'p>>",
			buttons: btnAdd,
			ajax: {
				url: urls,
				type: "POST",
			},
		})
		.buttons()
		.container()
		.appendTo("#example1_wrapper .col-md-6:eq(0)");
});


function myActive(id, param, urls) {
	$.ajax({
		url: urls,
		type: "POST",
		data: {
			DID: id,
			active: param,
		},

		success: function (result) {
			var response = $.parseJSON(result);
			if (response) {
				reload_table();
				swal({
					title: "Success",
					text: "success updated..",
					type: "success",
					showConfirmButton: false,
					confirmButtonText: false,
					timer: 2000,
				});
			} else {
				swal({
					title: "Error",
					text: "error updated..",
					type: "error",
					showConfirmButton: false,
					confirmButtonText: false,
					timer: 2000,
				});
			}
		},
		error: function (xhr, Status, err) {
			$("Terjadi error : " + Status);
		},
	});
}

function myDelete(id, urls) {
	swal.fire({
			title: "Are you sure?",
			text: "Are you sure you want to delete this?",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Yes, delete it!",
		})
		.then((result) => {
			if (result.value) {
				$.ajax({
					url: urls,
					type: "POST",
					data: {
						DID: id,
					},
					success: function (result) {
						reload_table();
						swal({
							title: "Success",
							text: "success deleted..",
							type: "success",
							showConfirmButton: false,
							confirmButtonText: false,
							timer: 2000,
						});
					},
					error: function (xhr, Status, err) {
						$("Terjadi error : " + Status);
					},
				});
			} else {
				return false;
			}
		});
}

function reload_table() {
	$("#example1").DataTable().ajax.reload(); //reload datatable ajax
}
