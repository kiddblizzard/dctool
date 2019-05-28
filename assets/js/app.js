const $ = require('jquery');
require('../css/app.scss');
require('bootstrap');
require('blueimp-file-upload');

$(function () {
    $('#models_excel').fileupload({
        dataType: 'json',
        add: function (e, data) {
            $(".modal-footer").empty();
            $('#progress .bar').css(
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
            $('#progress .bar').css(
                'width',
                progress + '%'
            );
        },
        done: function (e, data) {
            if (data.result.error == 1) {
                $(".modal-body").prepend('<p class="red">' + data.result.msg + '</p>');
            } else {
                var temp = "<table class=\"upload_result table table-sm\">";
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
            $('#progress .bar').css(
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
            $('#progress .bar').css(
                'width',
                progress + '%'
            );
        },
        done: function (e, data) {
            if (data.result.error == 1) {
                $(".modal-body").prepend('<p class="red">' + data.result.msg + '</p>');
            } else {
                var temp = "<table class=\"upload_result table table-sm\">";
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

              },
              error: function (result) {
                  consolse.log(id + " status change failed");
              }
          });
      })
});
