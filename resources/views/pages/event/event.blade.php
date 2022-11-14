@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div id="alertStatus"></div>
        <div class="col-md-8">
            <!-- Modal Create Event -->
            <div class="modal fade modalCreateEvent" id="modalCreateEvent" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h1 class="modal-title fs-5" id="createModalLabel">Create Event</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" id="createEvent">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label" for="basic-default-name">Event Name</label>
                                    <input type="text" name="name" id="name" placeholder="Event Name" class="form-control"/>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="basic-default-slug">Slug</label>
                                    <input type="text" name="slug" id="slug" placeholder="Slug" class="form-control"/>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="basic-default-startAt">Started At</label>
                                    <input type="date" name="startAt" id="startAt" class="form-control"/>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="basic-default-endAt">Ended At</label>
                                    <input type="date" name="endAt" id="endAt" class="form-control" />
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="submit" id="submitButton">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal View Event -->
            <div class="modal fade modalViewEvent" id="modalViewEvent" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h1 class="modal-title fs-5" id="eventModalLabel">Event Detail</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="dataEvent">
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
                </div>
            </div>

            <!-- Modal Update Event -->
            <div class="modal fade modalUpdateEvent" id="modalUpdateEvent" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h1 class="modal-title fs-5" id="updateModalLabel">Update Event</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" id="updateEvent">
                                @method('PUT')
                                @csrf
                                <div class="form-group">
                                    <input type="hidden" name="idEvent" id="idEvent" />
                                    <label class="form-label" for="basic-default-name">Event Name</label>
                                    <input type="text" name="updateName" id="updateName" placeholder="Event Name" class="form-control"/>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="basic-default-slug">Slug</label>
                                    <input type="text" name="updateSlug" id="updateSlug" placeholder="Slug" class="form-control"/>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="basic-default-startAt">Started At</label>
                                    <input type="date" name="updateStartAt" id="updateStartAt" class="form-control"/>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="basic-default-endAt">Ended At</label>
                                    <input type="date" name="updateEndAt" id="updateEndAt" class="form-control" />
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="update" id="updateButton">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <h1>List Events</h1>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCreateEvent">
                Create Event
            </button>
            <div class="container mt-5">
                <table id="tableEvent" class="display table-responsive" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Started At</th>
                            <th>Ended At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
var oTable;
// Get All Events
$(document).ready(function () {
var table = $('#tableEvent').DataTable({
        ajax: {
            url: '/api/v1/events',
            type: 'GET',
            headers: {
                'Accept':'application/json'
            },
        },
        columns: [
            { data: 'name' },
            { data: 'slug' },
            { data: 'startAt' },
            { data: 'endAt' },
            { 
                data: 'id', render: function (data, type) {
                    return `
                        <div class="text-center">
                            <button type="button" id="viewEvent" data-view="${data}" class="btn btn-info center">
                                Show
                            </button>
                            <button type="button" id="editEvent" data-edit="${data}" class="btn btn-primary center">
                                Edit
                            </button>
                            <button type="button" id="deleteEvent" data-delete="${data}" class="btn btn-danger center">
                                Delete
                            </button>
                        </div>
                    `
                }
            }
        ]
    });
    oTable = table;
});

//View Event
$(document).on('click', '#viewEvent', function () {
    $(".modalViewEvent").modal('show');
    var id = $(this).data('view');

    $.ajax({
        url: `/api/v1/events/${id}`,
        type: 'GET',
        dataType: 'json',
        headers: {
          'Accept': 'application/json'
        },
        success: function(data) {
            $('#dataEvent').empty();
            $('#dataEvent').append(
                `
                    <div class="row">
                        <div class="col-md-12">
                            <div>Event Name: ${data.data.name}</div>
                        </div>
                        <div class="col-md-12">
                            <div>Slug: ${data.data.slug}</div>
                        </div>
                        <div class="col-md-12">
                            <div>Started At: ${data.data.startAt}</div>
                        </div>
                        <div class="col-md-12">
                            <div>Ended At: ${data.data.endAt}</div>
                        </div>
                    </div>
                `
            );
        }
    });
});

//Create Event
var formId = $('#createEvent');
var csrf = $("meta[name='csrf-token']").attr("content");

$(document).ready(function() {
    if (formId.length) {
        formId.validate({
            rules: {
                name: {
                    required: true
                },
                slug: {
                    required: true
                },
                startAt: {
                    required: true,
                    date: true
                },
                endAt: {
                    required: true,
                    date: true
                }
            },
            message: {
                name: {
                    required: "Please enter event name",
                },
                slug: {
                    required: "Please enter slug",
                },
                startAt: {
                    required: "Please enter started at",
                    date: "Please enter valid date"
                },
                endAt: {
                    required: "Please enter ended at",
                    date: "Please enter valid date"
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    }
});

$(document).on('submit', '#createEvent', function(e) {
    e.preventDefault();
    var name = $("#name");
    var slug = $("#slug");
    var startAt = $("#startAt");
    var endAt = $("#endAt");

    $("#submitButton").attr('disabled', true);

    $.ajax({
        url: `/api/v1/events`,
        type: 'POST',
        dataType: 'json',
        headers: {
          'Accept': 'application/json',
          'X-CSRF-TOKEN': `${csrf}`
        },
        data: {
          "name": name.val(),
          "slug": slug.val(),
          "startAt": startAt.val(),
          "endAt": endAt.val(),
        },
        success: function(data) {
            $(".modalCreateEvent").modal('hide');
            oTable.ajax.reload();
            $("#submitButton").attr('disabled', false);
            $('#alertStatus').append(
                `
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!!</strong> Data Berhasil disimpan
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                `
            );
            window.setTimeout(function() {
                $(".alert").fadeTo(500, 0).slideUp(500, function(){
                    $(this).remove();
                });
            }, 4000);

            document.getElementById('name').value = '';
            document.getElementById('slug').value = '';
            document.getElementById('startAt').value = '';
            document.getElementById('endAt').value = '';
        }
    });
});

//Update Event
$(document).on('click', '#editEvent', function () {
    $(".modalUpdateEvent").modal('show');
    var id = $(this).data('edit');

    $.ajax({
        url: `/api/v1/events/${id}`,
        type: 'GET',
        dataType: 'json',
        headers: {
          'Accept': 'application/json'
        },
        success: function(data) {
            document.getElementById('idEvent').value = data.data.id;
            document.getElementById('updateName').value = data.data.name;
            document.getElementById('updateSlug').value = data.data.slug;
            document.getElementById('updateStartAt').value = data.data.startAt;
            document.getElementById('updateEndAt').value = data.data.endAt;
        }
    });
});

var formIdUpdate = $('#updateEvent');

$(document).ready(function() {
    if (formIdUpdate.length) {
        formIdUpdate.validate({
            rules: {
                updateName: {
                    required: true
                },
                updateSlug: {
                    required: true
                },
                updateStartAt: {
                    required: true,
                    date: true
                },
                updateEndAt: {
                    required: true,
                    date: true
                }
            },
            message: {
                updateName: {
                    required: "Please enter event name",
                },
                updateSlug: {
                    required: "Please enter slug",
                },
                updateStartAt: {
                    required: "Please enter started at",
                    date: "Please enter valid date"
                },
                updateEndAt: {
                    required: "Please enter ended at",
                    date: "Please enter valid date"
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    }
});

$('#updateEvent').submit(function (e) {
    e.preventDefault();
    var updateName = $("#updateName");
    var updateSlug = $("#updateSlug");
    var updateStartAt = $("#updateStartAt");
    var updateEndAt = $("#updateEndAt");
    var id = $("#idEvent");

    $("#updateButton").attr('disabled', true);

    $.ajax({
        url: `/api/v1/events/${id.val()}`,
        type: 'PUT',
        dataType: 'json',
        headers: {
          'Accept': 'application/json',
          'X-CSRF-TOKEN': `${csrf}`
        },
        data: {
          "name": updateName.val(),
          "slug": updateSlug.val(),
          "startAt": updateStartAt.val(),
          "endAt": updateEndAt.val(),
        },
        success: function(data) {
            $(".modalUpdateEvent").modal('hide');
            oTable.ajax.reload();
            $("#updateButton").attr('disabled', false);
            $('#alertStatus').append(
                `
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!!</strong> Data Berhasil diupdate
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                `
            );
            window.setTimeout(function() {
                $(".alert").fadeTo(500, 0).slideUp(500, function(){
                    $(this).remove();
                });
            }, 4000);

            document.getElementById('updateName').value = '';
            document.getElementById('updateSlug').value = '';
            document.getElementById('updateStartAt').value = '';
            document.getElementById('updateEndAt').value = '';
        }
    });
});

//Delete Event
$(document).on('click', '#deleteEvent', function (e) {
    e.preventDefault();
    var id = $(this).data('delete');

    $.ajax({
        url: `/api/v1/events/${id}`,
        type: "DELETE",
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN':`${csrf}`,
            'Accept':'application/json'
        },
        success:function () {
            oTable.ajax.reload();
            $('#alertStatus').append(
                `
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!!</strong> Data Berhasil didelete
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                `
            );
            window.setTimeout(function() {
                $(".alert").fadeTo(500, 0).slideUp(500, function(){
                    $(this).remove();
                });
            }, 4000);
        }
    });
})
</script>
@endsection
