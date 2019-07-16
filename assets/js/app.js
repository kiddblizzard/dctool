const $ = require('jquery');
require('../css/app.scss');
require('bootstrap');
require('open-iconic/font/css/open-iconic-bootstrap.css');
// require('font-awesome/css/font-awesome.min.css');
require('blueimp-file-upload');
// require('bootstrap-datepicker');
require('jquery-autocompleter');

$(function () {
    $('#models_excel').fileupload({
        dataType: 'json',
        add: function (e, data) {
            $(".modal-footer").empty();
            $('.upload-progress .bar').css(
                'width',
                '0%'
            );
            data.context = $('<button/>').text('Upload')
                .appendTo(".modal-footer")
                .click(function () {
                    data.context = $('<p/>').text('Uploading...').replaceAll($(this));
                    data.submit();
                });

            $(".modal-body table").remove();
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('.upload-progress .bar').css(
                'width',
                progress + '%'
            );
        },
        done: function (e, data) {
            if (data.result.error == 1) {
                $(".modal-body").prepend('<p class="red">' + data.result.msg + '</p>');
            } else {
                var temp = "<table class=\"upload-result table table-sm\">";
                data.result.result.forEach(function(one) {
                    temp += "<tr class=\"";
                    if(one.status == "Added"){
                        temp += "table-success";
                    } else if(one.status == "Data Incomplete") {
                        temp += "table-danger";
                    }
                    temp += " \" ><td>" + one.manufacturer + "</td>";
                    temp += "<td>" + one.model + "</td>";
                    temp += "<td>" + one.type + "</td>";
                    temp += "<td>" + one.status + "</td></tr>";
                });
                temp += "</table>";
                $(".modal-body").append(temp);
            }

            data.context.text('Upload finished.');
        }
    });
});


$(function () {
    $('#devices_excel').fileupload({
        dataType: 'json',
        add: function (e, data) {
            $(".modal-footer").empty();
            $('.upload-progress .bar').css(
                'width',
                '0%'
            );
            data.context = $('<button/>').text('Upload')
                .appendTo(".modal-footer")
                .click(function () {
                    data.context = $('<p/>').text('Uploading...').replaceAll($(this));
                    data.submit();
                });

            $(".modal-body table").remove();
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('.upload-progress .bar').css(
                'width',
                progress + '%'
            );
        },
        done: function (e, data) {
            if (data.result.error == 1) {
                $(".modal-body").prepend('<p class="red">' + data.result.msg + '</p>');
            } else {
                var temp = "<table class=\"upload-result table table-sm\">";
                data.result.result.forEach(function(one) {
                    temp += "<tr class=\"";
                    if(one.status == "Added"){
                        temp += "table-success";
                    } else if(one.status == "Data Incomplete") {
                        temp += "table-danger";
                    } else if(one.status == "Model Not Existing") {
                        temp += "table-secondary";
                    } else if(one.status == "Conflict") {
                        temp += "table-warning";
                    } else if(one.status == "Updated") {
                        temp += "table-info";
                    }
                    temp += " \" ><td>" + one.device_name + "</td>";
                    temp += "<td>" + one.serial_number + "</td>";
                    temp += "<td>" + one.model + "</td>";
                    temp += "<td>" + one.status + "</td></tr>";
                });
                temp += "</table>";
                $(".modal-body").append(temp);
            }

            data.context.text('Upload finished.');
        }
    });
});


$(function () {
    $(".list_receiving_access").on('change', function() {

      var id = $(this).parents("tr").data("id");
      var jsonstr = {"id": id, "access": $(this).val()};
      $.ajax({
          url: "/ajax/receiving/"+ id +"/access",
          dataType: "json",
          type: "PUT",
          contentType:"application/json",
          data:JSON.stringify(jsonstr),
          success:function (result) {
              window.location.reload();
          },
          error: function (result) {
              consolse.log(id + " access change failed");
          }
      });
    })
});

$(function () {
    $(".list_receiving_status").on('change', function() {

      var id = $(this).parents("tr").data("id");
      var jsonstr = {"id": id, "status": $(this).val()};
      $.ajax({
          url: "/ajax/receiving/"+ id +"/status",
          dataType: "json",
          type: "PUT",
          contentType:"application/json",
          data:JSON.stringify(jsonstr),
          success:function (result) {
              window.location.reload();
          },
          error: function (result) {
              consolse.log(id + " status change failed");
          }
      });
    })
});

$(function () {
    $(".list_bau_status").on('change', function() {

      var id = $(this).parents("tr").data("id");
      var jsonstr = {"id": id, "status": $(this).val()};
      $.ajax({
          url: "/ajax/bau/"+ id +"/status",
          dataType: "json",
          type: "PUT",
          contentType:"application/json",
          data:JSON.stringify(jsonstr),
          success:function (result) {
              window.location.reload();
          },
          error: function (result) {
              consolse.log(id + " status change failed");
          }
      });
    })
});

$(function () {
    $(".nav_site").on('change', function() {

      var id = $(this).val();
      var jsonstr = {"site": $(this).val()};
      $.ajax({
          url: "/ajax/session/site",
          dataType: "json",
          type: "PUT",
          contentType:"application/json",
          data:JSON.stringify(jsonstr),
          success:function (result) {
              window.location.reload();
          },
          error: function (result) {
              consolse.log(" site change failed");
          }
      });
    })
});

$(function () {
    $(".rack-select").on('change', function() {
        window.location = '/racks/' + $(this).val() + '/devices';
    });
    // $('.datetimepicker').datetimepicker();
});

$(function () {
    $(".device-model-select").on('change', function() {
        $.ajax({
            url: "/ajax/model/" + $(this).val(),
            dataType: "json",
            type: "GET",
            contentType:"application/json",
            success:function (result) {
                var type = result.result.type;
                if ("BLADE" == type) {
                    $('.device-children').addClass("d-none");
                    $('.device-parent').removeClass("d-none");
                } else if ("ENCLOSURE" == type) {
                    $('.device-parent').addClass("d-none");
                    $('.device-children').removeClass("d-none");
                } else {
                    $('.device-parent').addClass("d-none");
                    $('.device-children').addClass("d-none");
                }
            },
            error: function (result) {
                console.log("model check failed");
            }
        });
    });
});

$(function () {
    $('.enclosure-save').on('click', function() {
        var id = $('#form_id').val();
        var obj = {};
            obj.id = id;
            obj.parent = $('#form_parent').val();
        $.ajax({
            url: "/ajax/devices/" + id + "/parent",
            dataType: "json",
            type: "POST",
            contentType:"application/json",
            data:JSON.stringify(obj),
            success:function (result) {
                console.log(result);
                if (result.error == 0) {
                    $("#enclosure-msg")
                        .text(result.msg)
                        .addClass('alert alert-success');
                } else if (result.error == 1) {
                    $("#enclosure-msg")
                        .text(result.msg)
                        .addClass('alert alert-danger');
                }
            },
            error: function (result) {
                console.log("model check failed");
            }
        });
    });
});

$(function() {
    var deviceId = $("#blade-input").data('id');
    var obj = {};
        obj.id = deviceId;

    $('#blade-input').autocompleter({
        source: '/ajax/autocomplete/' + deviceId + '/blades',
        customLabel: name,
        cache: false,
        minLength: 4,
        callback: function(value, index, object) {
            obj.child = object.value.id;
            $.ajax({
                url: '/ajax/devices/' + deviceId + '/child',
                dataType: "json",
                type: "POST",
                contentType:"application/json",
                data:JSON.stringify(obj),
                success:function (result) {
                    if (result.error == 0) {
                        $("#blade-msg")
                            .text(result.msg)
                            .addClass('alert alert-success');
                    } else if (result.error == 1) {
                        $("#blade-msg")
                            .text(result.msg)
                            .addClass('alert alert-danger');
                    }

                    var temp = getChildrenHtml(result.device.children);
                    $(".child-p").remove();
                    $('#blade-input').before(temp);
                    $('#blade-input').val("");
                },
                error: function (result) {
                    console.log("model check failed");
                }
            });
        }
    });

    $(".children-list").on('click', '.remove-child', function() {
        var deviceId = $("#blade-input").data('id');
        var obj = {};
            obj.id = deviceId;
            obj.child = $(this).data('id');
        $.ajax({
            url: '/ajax/devices/' + deviceId + '/child',
            dataType: "json",
            type: "DELETE",
            contentType:"application/json",
            data:JSON.stringify(obj),
            success:function (result) {
                console.log(result);
                if (result.error == 0) {
                    $("#blade-msg")
                        .text(result.msg)
                        .addClass('alert alert-success');

                    var temp = getChildrenHtml(result.device.children);
                    $(".child-p").remove();
                    $("#blade-input").before(temp);
                } else if (result.error == 1) {
                    $("#blade-msg")
                        .text(result.msg)
                        .addClass('alert alert-danger');
                }
            },
            error: function (result) {
                console.log("model check failed");
            }

        });
    });

    $('#bladeModal').on('hidden.bs.modal', function (e) {
        window.location.reload();
    });

    $('#enclosureModal').on('hidden.bs.modal', function (e) {
        window.location.reload();
    });


    var getChildrenHtml = function (devices) {
        var html = "";

        devices.forEach(function(device) {
            html += "<p class=\"child-p\">" + device.name;
            html += " <button type=\"button\" class=\"remove-child close float-none\"";
            html += "aria-label=\"Close\" data-id=\"" + device.id + "\" >";
            html += "<span aria-hidden=\"true\">&times;</span></button></p>";
        });

        return html;
    }
});

$(function () {
    $('.example-popover').popover({
        container: 'body'
    })
})
