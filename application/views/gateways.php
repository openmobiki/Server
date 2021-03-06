<?php $this->load->view('layout/up') ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <button type="button" id="btn_add_gateway" data-toggle='modal' data-target='#addGatewayModal' class="btn btn-primary btn-sm">Add Gateway</button></h6>
            </div>
            <div class="card-body">
                <link rel="stylesheet" href="">
                <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <td>Status</td>
                            <td>Serial No</td>
                            <td>Gateway Name</td>
                            <td>Location</td>
                            <td>Description</td>
                            <td>Type</td>
                            <td>Zone Name</td>
                            <td>Config</td>
                            <td>Edit</td>
                        </tr>
                    </thead>
                    <tbody id="gateways">
                        <?php
                        foreach (@$gateways as $key => $value) {
                            $gateway_type_name ="";
                            $gateway_type_id ="";
                            foreach ($gateway_types as $gtkey => $gateway_type) {
                                if ($gateway_type["id"]==$value["type"]) {
                                    $gateway_type_name=$gateway_type["name"];
                                    $gateway_type_id=$gateway_type["id"];
                                }
                            }
                            if (@$value["zone_id"] == 0) {
                                $zone_name = "";
                            } else {
                                foreach (@$zones as $key => $dvalue) {
                                    if ($dvalue["id"] == $value["zone_id"]) {
                                        $zone_name = $dvalue["name"];
                                        break;
                                    } else {
                                        $zone_name = "";
                                    }
                                }
                            }



                            echo "<tr>";
                            if (@$value["status"]) {
                                echo "<td>" . "<b  title='1' style='color:green;'>&#11044;</b>"  . "</td>";
                            } else {
                                echo "<td>" . "<b title='0' style='color:red;'>&#11044;</b>" . "</td>";
                            }
                            echo "<td>" . @$value["mac"] . "</td>";
                            echo "<td>" . @$value["name"] . "</td>";
                            echo "<td>" . @$value["lat"] . "," . @$value["lng"] . "</td>";
                            echo "<td>" . @$value["description"] . "</td>";
                            echo "<td>" . @$gateway_type_name . "</td>";
                            if (@$zone_name == "") {
                                echo "<td style='background-color: red;'>";
                            } else {
                                echo "<td>";
                            }
                            echo @$zone_name . "</td>";
                            echo "<td>" ."<a href='gateways/config?mac=".@$value["mac"]."' type='button' class='btn btn-warning btn-sm'>Config</a>". "</td>";
                            echo "<td>" . "<button type='button' 
                            data-toggle='modal' 
                            data-target='#addGatewayModal' 
                            data-id='" . @$value["id"] . "' 
                            data-mac='" . @$value["mac"] . "' 
                            data-name='" . @$value["name"] . "' 
                            data-lat='" . @$value["lat"] . "' 
                            data-lng='" . @$value["lng"] . "' 
                            data-zone_id='" . @$value["zone_id"] . "' 
                            data-gateway_type_id='" . @$gateway_type_id . "' 
                            data-description='" . @$value["description"] . "' 
                            data-status='" . $value["status"] . "' 
                            class='btn btn-success btn-sm'>Edit</button>" . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-modal-lg" id="addGatewayModal" tabindex="-1" role="dialog" aria-labelledby="addGatewayModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel" id="modal_title">Add Gateway</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="addmodalbody">
                <form id="form_edit" action="<?php echo base_url("gateways/add"); ?>" method="post">
                    <input type="hidden" id="eid" name="id" value="" />
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="mac">Gateway Serial Number</label>
                                <input type="text" class="form-control" id="emac" name="mac" placeholder="">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="name">Gateway Name</label>
                                <input type="text" class="form-control" id="ename" name="name" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="lat">Latitude</label>
                                <input type="number" step="any" class="form-control" id="elat" name="lat" placeholder="">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="lng">Longitude</label>
                                <input type="number" step="any" class="form-control" id="elng" name="lng" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="description">Description</label>
                                <input type="text" class="form-control" id="edescription" name="description" placeholder="">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="gateway_type_id">Type</label>
                                <?php if (count($gateway_types) > 0) { ?>
                                    <select class="form-control" id="egateway_type_id" name="gateway_type_id">
                                        <?php foreach ($gateway_types as $key => $gateway_type) {  ?>
                                            <option value="<?php echo $gateway_type["id"]; ?>"><?php echo $gateway_type["name"]; ?></option>
                                        <?php } ?>
                                    </select>
                                <?php } else { ?>
                                    <p> <a href="type">Click to add a type.</a></p>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="zone_id">Zone</label>
                                <?php if (count($zones) > 0) { ?>
                                    <select class="form-control" id="ezone_id" name="zone_id">
                                        <?php foreach ($zones as $key => $zvalue) {  ?>
                                            <option value="<?php echo $zvalue["id"]; ?>"><?php echo $zvalue["name"]; ?></option>
                                        <?php } ?>
                                    </select>
                                <?php } else { ?>
                                    <p> <a href="zones">Click to add a zone.</a></p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="custom-control custom-switch">
                                <input type="hidden" id="estatus" name="status" value="" />
                                <input type="checkbox" class="custom-control-input" id="ecstatus" name="cstatus" checked />
                                <label class="custom-control-label" for="ecstatus">Active</label>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row" style="text-align: right;">
                        <div class="col-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" id="btn_gateway_add">Add Gateway</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div id="delet_gateway">
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <form action="gateways/delete" method="post">
                                <input type="hidden" id="did" name="id" value="" />
                                <button type="submit" class="btn btn-danger">Delete Gateway</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php $this->load->view('layout/down') ?>
<script>
    $("#ecstatus").change(function() {
        if (this.checked) {
            $("#estatus").val("1");
        } else {
            $("#estatus").val("0");
        }
    });

    $('.btn-success').each(function() {
        var $this = $(this);

        $this.on("click", function() {
            $('#form_edit').attr('action', '<?php echo base_url('gateways/edit'); ?>');
            $('#did').val($(this).data('id'));
            $('#eid').val($(this).data('id'));
            $('#emac').val($(this).data('mac'));
            $('#ename').val($(this).data('name'));
            $('#elat').val($(this).data('lat'));
            $('#elng').val($(this).data('lng'));
            $('#ezone_id').val($(this).data('zone_id'));
            $('#egateway_type_id').val($(this).data('gateway_type_id'));
            $('#edescription').val($(this).data('description'));

            $('#estatus').val($(this).data('status'));

            if ($(this).data('status') == 1) {
                $('#ecstatus').prop('checked', true);
            } else {
                $('#ecstatus').prop('checked', false);
            }

            $('#btn_gateway_add').html("Save");
            $('#modal_title').html("Edit Gateway");
            $('#delet_gateway').show();
        });
    });


    $('#btn_add_gateway').on("click", function() {
        $('#modal_title').html("Add Gateway");
        $('#btn_gateway_add').html("Add Gateway");
        $('#form_edit').attr('action', '<?php echo base_url('gateways/add'); ?>');
        $('#did').val("");
        $('#eid').val("");
        $('#emac').val("");
        $('#ename').val("");
        $('#elat').val("");
        $('#elng').val("");
        $('#ezone_id').val(1);
        $('#egateway_type_id').val(1);
        $('#estatus').val("1");
        $('#ecstatus').prop('checked', true);
        $('#edescription').val("");

        $('#delet_gateway').hide();


        $("#etype_id").load("<?php echo base_url('assets/list_asset_type'); ?>");
        $('#etype_id').val(0);
    });


    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>