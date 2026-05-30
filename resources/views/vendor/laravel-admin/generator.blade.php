@extends('layouts.backend')

@section('content')
<div class="content">
    <!-- Layouts -->
    <div class="block block-rounded block-bordered">
        <div class="block-header block-header-default">
            <h3 class="block-title">Tools</h3>
        </div>
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb push">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/admin') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="breadcrumb-item active" aria-current="page"">Generator</a>
                    </li>
                </ol>
            </nav>
        </div>
        <hr>
        <div class="block-content">
            <!-- Label on top Layout -->
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <!-- Form Labels on top - Default Style -->
                    <form class="mb-5" action="{{ url('/admin/generator') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="crud_name">CRUD Name</label>
                            <input type="text" name="crud_name" class="form-control" id="crud_name" required="true">
                        </div>
                        <div class="form-group">
                            <label for="controller_namespace">Controller Namespace</label>
                            <input type="text" name="controller_namespace" class="form-control" id="controller_namespace"
                                placeholder="Default : Admin">
                        </div>
                        <div class="form-group">
                            <label for="route_group">Route Group Prefix</label>
                            <input type="text" name="route_group" class="form-control" id="route_group" placeholder="Default : admin">
                        </div>
                        <div class="form-group">
                            <label for="view_path">View Path</label>
                            <input type="text" name="view_path" class="form-control" id="view_path" placeholder="Default : admin">
                        </div>
                        <div class="form-group">
                            <label for="route">Want to add route?</label>
                            <select name="route" class="form-control m-b-sm" id="route">
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <!-- <label for="relationships">Relationships</label> -->
                            <input type="hidden" name="relationships" class="form-control" id="relationships" placeholder="example: [method]#[relationType]#[Model]">
                        </div>
                        <div class="form-group">
                            <label for="form_helper">Form Helper</label>
                            <input type="text" name="form_helper" class="form-control" id="form_helper" value="laravelcollective"
                                readonly>
                        </div>
                        <div class="form-group">
                            <label for="soft_deletes">Want to use soft deletes?</label>
                            <select name="soft_deletes" class="form-control m-b-sm" id="soft_deletes">
                                <option value="no">No</option>
                                <option value="yes">Yes</option>
                            </select>
                        </div>
                        <hr>
                        <div class="form-group">
                            <h4 class="text-center">Table Fields</h4><br>
                            <div class="col-md-12 text-center table-responsive">
                                <table class="display table tambah">
                                    <tr>
                                        <th>Field Name</th>
                                        <th>Type</th>
                                        <th>Required</th>
                                    </tr>
                                    <tr class="entry">
                                        <td>
                                            <input class="form-control" name="fields[]" type="text" placeholder="field_name"
                                                required="true">
                                        </td>
                                        <td>
                                            <select name="fields_type[]" class="form-control">
                                                <option value="string">string</option>
                                                <option value="char">char</option>
                                                <option value="varchar">varchar</option>
                                                <option value="password">password</option>
                                                <option value="email">email</option>
                                                <option value="date">date</option>
                                                <option value="datetime">datetime</option>
                                                <option value="time">time</option>
                                                <option value="timestamp">timestamp</option>
                                                <option value="text">text</option>
                                                <option value="mediumtext">mediumtext</option>
                                                <option value="longtext">longtext</option>
                                                <option value="json">json</option>
                                                <option value="jsonb">jsonb</option>
                                                <option value="binary">binary</option>
                                                <option value="number">number</option>
                                                <option value="integer">integer</option>
                                                <option value="bigint">bigint</option>
                                                <option value="mediumint">mediumint</option>
                                                <option value="tinyint">tinyint</option>
                                                <option value="smallint">smallint</option>
                                                <option value="boolean">boolean</option>
                                                <option value="decimal">decimal</option>
                                                <option value="double">double</option>
                                                <option value="float">float</option>
                                                <option value="file">file</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="fields_required[]" class="form-control">
                                                <option value="0">No</option>
                                                <option value="1">Yes</option>
                                            </select>
                                        </td>
                                    </tr>
                                </table>
                                <br>
                            </div>
                            <p class="help text-center">It will automatically assume form fields based on the migration
                                field type.</p>
                            <br>
                            <div class="form-group">
                                <div class="col-md-12" align="right">
                                    <button class="btn btn-success btn-add btn-addon btn-sm"><i class="fa fa-plus"></i>
                                        Add Field</button>
                                    <button class="btn btn-danger btn-addon btn-sm" onclick="hapus()"><i class="fa fa-minus"></i>
                                        Remove Field</button>
                                    <button type="submit" class="btn btn-primary btn-addon btn-sm" name="generate"><i
                                            class="fa fa-check"></i> Generate</button>
                                </div>
                            </div>
                    </form>
                    <!-- END Form Labels on top - Default Style -->
                </div>
            </div>
            <!-- END Label on top Layout -->
        </div>
    </div>
    <!-- END Layouts -->
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    var index = 0;
    $(".btn-add").click(function () {
        $(".tambah").append(`
                <tr class="tr_` + index +
            `">
                    <td>
                        <input class="form-control" name="fields[]" type="text" placeholder="field_name" required="true">
                    </td>
                    <td>
                    <select name="fields_type[]" class="form-control">
                        <option value="string">string</option>
                        <option value="char">char</option>
                        <option value="varchar">varchar</option>
                        <option value="password">password</option>
                        <option value="email">email</option>
                        <option value="date">date</option>
                        <option value="datetime">datetime</option>
                        <option value="time">time</option>
                        <option value="timestamp">timestamp</option>
                        <option value="text">text</option>
                        <option value="mediumtext">mediumtext</option>
                        <option value="longtext">longtext</option>
                        <option value="json">json</option>
                        <option value="jsonb">jsonb</option>
                        <option value="binary">binary</option>
                        <option value="number">number</option>
                        <option value="integer">integer</option>
                        <option value="bigint">bigint</option>
                        <option value="mediumint">mediumint</option>
                        <option value="tinyint">tinyint</option>
                        <option value="smallint">smallint</option>
                        <option value="boolean">boolean</option>
                        <option value="decimal">decimal</option>
                        <option value="double">double</option>
                        <option value="float">float</option>
                        <option value="file">file</option>
                    </select>
                </td>
                <td>
                    <select name="fields_required[]" class="form-control">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </td>
            </tr>
            `
        )
        index++
    })

    function hapus() {
        index--
        $(".tr_" + index).remove();
    }

</script>
@endsection
