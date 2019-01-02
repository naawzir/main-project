@extends('layouts.app')
@section('content')

    <style type="text/css">
        #savedMessage{
            background:#94D78A;
            height:80px;
            width:200px;
            position:fixed;
            bottom:10px;
            right:10px;
            display:none;
        }

        #savedMessage p {
            text-align:center;
            color:#000000;
        }

        #failedToSaveMessage{
            background:red;
            height:80px;
            width:200px;
            position:fixed;
            bottom:10px;
            right:10px;
            display:none;
        }

        #failedToSaveMessage p {
            text-align:center;
            color:#000000;
        }
    </style>

    <div id="page-content">
        <div id='wrap'>
            <div id="page-heading">
                <ol class="breadcrumb">
                    <li><a href="">Dashboard</a></li>
                </ol>
                <h1>Case Management</h1>
                <div class="options">
                    <div class="btn-toolbar">
                        <a href="/cases" class="btn btn-default"><i class="fa fa-table"></i> View all cases </a>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">

                    <div class="col-xs-12">
                        <div class="panel panel-midnightblue">
                            <div class="panel-heading">
                                <h4>edit case</h4>
                                <div class="options">

                                </div>
                            </div>
                            <div class="panel-body">

                                {{--@include('admin.inc.errors')--}}


                                <form method="POST" id="form" action="/cases/users" enctype="multipart/form-data" >

                                    {{ csrf_field() }}

                                    <input type="hidden" id="userId" name="userId" value="create">
                                    <input type="hidden" id="caseId" name="caseId" value="create">
                                    <input type="hidden" id="addressId" name="addressId" value="create">


                                    <div id="clientDetails">

                                        <h3>Confirm Client Details</h3>
                                        <table>
                                            <tr>
                                                <th>Title</th>
                                                <th>Forename</th>
                                                <th>Surname</th>
                                                <th>Email</th>
                                            </tr>
                                            <tr>

                                                <td style="padding-right:10px;">
                                                    <div class="form-group">
                                                        <select name="title" id="title" class="form-control client-detail" >
                                                            <option value="">Please select</option>
                                                            <option value="mr">Mr</option>
                                                            <option value="mrs">Mrs</option>
                                                            <option value="miss">Miss</option>
                                                            <option value="ms">Ms</option>
                                                        </select>
                                                    </div>
                                                </td>

                                                <td style="padding-right:10px;">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control client-detail" id="forename" name="forename" value="{{ old('forename') }}">
                                                    </div>
                                                </td>

                                                <td style="padding-right:10px;">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control client-detail" id="surname" name="surname" value="{{ old('surname') }}">
                                                    </div>
                                                </td>

                                                <td>
                                                    <div class="form-group">
                                                        <input type="email" class="form-control client-detail" id="email" name="email" value="{{ old('email') }}">
                                                    </div>
                                                </td>

                                            </tr>
                                        </table>


                                        <div id="savedMessage">
                                            <p>Successfully saved<br><i class="fa fa-check" style="font-size:39px;" aria-hidden="true"></i></p>
                                        </div>

                                        <div id="failedToSaveMessage">
                                            <p>Failed to save<br><i class="fa fa-times" aria-hidden="true" style="font-size:39px;"></i></p>
                                        </div>
                                    </div>


                                    <div id="transactionDetails" style="display:none;">
                                        <h3>Confirm Transaction Details</h3>

                                        <table>
                                            <tr>
                                                <th>Case Type</th>
                                                <th>Lead Source</th>
                                                <th>Price</th>
                                                <th>Tenure</th>
                                            </tr>

                                            <tr>
                                                <td style="padding-right:10px;">
                                                    <div class="form-group">
                                                        <select name="case_type" id="case_type" class="form-control transaction-detail" >
                                                            <option value="">Please select</option>
                                                            <option value="sale">Sale</option>
                                                            <option value="purchase">Purchase</option>
                                                            <option value="remortgage">Remortgage</option>
                                                        </select>
                                                    </div>
                                                </td>

                                                <td style="padding-right:10px;">
                                                    <div class="form-group">
                                                        <select name="lead_source" id="lead_source" class="form-control transaction-detail" >
                                                            <option value="">Please select</option>
                                                            <option value="seller_sstc">Seller SSTC</option>
                                                            <option value="buyer_sstc">Buyer SSTC</option>
                                                            <option value="seller_new_take_on">Seller New Take On</option>
                                                            <option value="seller_welcome_call">Seller Welcome Call</option>
                                                            <option value="seller_already">Seller Already on the Market</option>
                                                            <option value="none">None</option>
                                                        </select>
                                                    </div>
                                                </td>

                                                <td style="padding-right:10px;">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control transaction-detail" id="price" name="price" value="{{ old('price') }}">
                                                    </div>
                                                </td>

                                                <td style="padding-right:10px;">
                                                    <div class="form-group">
                                                        <select name="tenure" id="tenure" class="form-control transaction-detail" >
                                                            <option value="">Please select</option>
                                                            <option value="freehold">Freehold</option>
                                                            <option value="leasehold">Leasehold</option>
                                                        </select>
                                                    </div>
                                                </td>

                                            </tr>
                                        </table>

                                    </div>


                                    <div id="addressTransactionDetails" style="display:none;">
                                        <h3>Confirm Transaction Address Details</h3>

                                        <table>
                                            <tr>
                                                <th>House No / Name</th>
                                                <th>Address Line 1</th>
                                                <th>Address Line 2</th>
                                                <th>Town</th>
                                                <th>County</th>
                                                <th>Postcode</th>
                                            </tr>

                                            <tr>
                                                <td style="padding-right:10px;">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control transaction-address-detail" id="hnum" name="hnum" value="{{ old('hnum') }}">
                                                    </div>
                                                </td>

                                                <td style="padding-right:10px;">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control transaction-address-detail" id="address_line_1" name="address_line_1" value="{{ old('address_line_1') }}">
                                                    </div>
                                                </td>

                                                <td style="padding-right:10px;">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control transaction-address-detail" id="address_line_2" name="address_line_2" value="{{ old('address_line_2') }}">
                                                    </div>
                                                </td>

                                                <td style="padding-right:10px;">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control transaction-address-detail" id="town" name="town" value="{{ old('town') }}">
                                                    </div>
                                                </td>

                                                <td style="padding-right:10px;">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control transaction-address-detail" id="county" name="county" value="{{ old('county') }}">
                                                    </div>
                                                </td>

                                                <td>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control transaction-address-detail" id="postcode" name="postcode" value="{{ old('postcode') }}">
                                                    </div>
                                                </td>


                                            </tr>
                                        </table>


                                    </div>


                                    <div id="agencyDetails" style="display:none;">
                                        <h3>Confirm Agency Details</h3>

                                        <table>
                                            <tr>
                                                <th>Agency</th>
                                                <th>Agency Branch</th>
                                                <th>Agent</th>
                                            </tr>

                                            <tr>
                                                <td style="padding-right:10px;">
                                                    <div class="form-group">
                                                        <select name="agency_id" id="agency_id" class="form-control agency-detail" >
                                                            <option value="">Please select</option>
                                                     {{--       @foreach($agencies as $agency)
                                                                <option value="{{ $agency->id }}">{{ $agency->name }}</option>
                                                            @endforeach--}}
                                                        </select>
                                                    </div>
                                                </td>

                                                <td style="padding-right:10px;">
                                                    <div class="form-group">
                                                        <select name="agency_branch_id" id="agency_branch_id" class="form-control agency-detail" >
                                                            <option value="">Please select</option>
                                                            {{--  @foreach($branches as $branch)
                                                                  <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                                              @endforeach--}}
                                                        </select>
                                                    </div>
                                                </td>

                                                <td>
                                                    <div class="form-group">
                                                        <select name="user_id_agent" id="user_id_agent" class="form-control agency-detail" >
                                                            <option value="">Please select</option>
                                                           {{-- @foreach($users as $user)
                                                                <option value="{{ $user->id }}">{{ $user->fullname() }}</option>
                                                            @endforeach--}}
                                                        </select>
                                                    </div>
                                                </td>

                                            </tr>
                                        </table>
                                    </div>



                                </form>

                            </div>
                            <div class="panel-footer  main_form_body">
                                <div class="row">

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">

        $(document).ready(function() {

            $("#agency_id").change(function(){
                var agencyId = $(this).val();
                $.get('/agencies/getbranchesforagency',
                    {
                        agencyId: agencyId
                    },
                    function(resp){
                        $("#agency_branch_id").html(resp);
                    });
            });

            $("#agency_branch_id").change(function(){
                var agencyBranchId = $(this).val();
                $.get('/branches/getusersforbranch',
                    {
                        agencyBranchId: agencyBranchId
                    },
                    function(resp){
                        $("#user_id_agent").html(resp);
                    });
            });

            $(".client-detail").blur(function () {

                var userId = $("#userId").val();
                var title = $("#title").val();
                var forename = $("#forename").val();
                var surname = $("#surname").val();
                var email = $("#email").val();

                var datastring = $("#form").serialize();
                if (title != '' && forename != '' && surname != '' && email != '') {
                    if (userId == 'create') {
                        $.ajax({
                            type: "POST",
                            url: "/cases/user/create",
                            data: datastring,
                            //dataType: "json",
                            success: function (data) {
                                $("#userId").val(data);
                                $("#transactionDetails").fadeIn();
                                $("#savedMessage").fadeIn().fadeOut(5000);
                            },
                            error: function () {
                                $("#failedToSaveMessage").fadeIn().fadeOut(5000);
                            }
                        });
                    } else {
                        $.ajax({
                            type: "POST",
                            url: "/cases/user/" + userId,
                            data: datastring,
                            //dataType: "json",
                            success: function (data) {
                                $("#savedMessage").fadeIn().fadeOut(5000);
                            },
                            error: function () {
                                $("#failedToSaveMessage").fadeIn().fadeOut(5000);
                            }
                        });
                    }
                }
            });

            $(".transaction-detail").blur(function () {

                var caseId = $("#caseId").val();
                var case_type = $("#case_type").val();
                var lead_source = $("#lead_source").val();
                var price = $("#price").val();
                var tenure = $("#tenure").val();

                var datastring = $("#form").serialize();
                if (case_type != '' && lead_source != '' && price != '' && tenure != '') {

                    if (caseId == 'create') {
                        $.ajax({
                            type: "POST",
                            url: "/cases/case/create",
                            data: datastring,
                            //dataType: "json",
                            success: function (data) {
                                $("#caseId").val(data);
                                $("#addressTransactionDetails").fadeIn();
                                $("#savedMessage").fadeIn().fadeOut(5000);
                            },
                            error: function () {
                                $("#failedToSaveMessage").fadeIn().fadeOut(5000);
                            }
                        });
                    } else {
                        $.ajax({
                            type: "POST",
                            url: "/cases/case/" + caseId,
                            data: datastring,
                            //dataType: "json",
                            success: function (data) {
                                $("#savedMessage").fadeIn().fadeOut(5000);
                            },
                            error: function () {
                                $("#failedToSaveMessage").fadeIn().fadeOut(5000);
                            }
                        });
                    }
                }
            });


            $(".transaction-address-detail").blur(function () {

                var addressId = $("#addressId").val();
                var hnum = $("#hnum").val();
                var address_line_1 = $("#address_line_1").val();
                var address_line_2 = $("#address_line_2").val();
                var town = $("#town").val();
                var county = $("#county").val();
                var postcode = $("#postcode").val();

                var datastring = $("#form").serialize();
                if (hnum != '' && address_line_1 != '' && town !='' && postcode != '') {

                    if (addressId == 'create') {
                        $.ajax({
                            type: "POST",
                            url: "/cases/transactionaddress/create",
                            data: datastring,
                            //dataType: "json",
                            success: function (data) {
                                $("#addressId").val(data);
                                $("#agencyDetails").fadeIn();
                                $("#savedMessage").fadeIn().fadeOut(5000);
                            },
                            error: function () {
                                $("#failedToSaveMessage").fadeIn().fadeOut(5000);
                            }
                        });
                    } else {
                        $.ajax({
                            type: "POST",
                            url: "/cases/transactionaddress/" + addressId,
                            data: datastring,
                            //dataType: "json",
                            success: function (data) {
                                $("#savedMessage").fadeIn().fadeOut(5000);
                            },
                            error: function () {
                                $("#failedToSaveMessage").fadeIn().fadeOut(5000);
                            }
                        });
                    }
                }
            });


            $(".agency-detail").blur(function () {

                var agency_id = $("#agency_id").val();
                var agency_branch_id = $("#agency_branch_id").val();
                var user_id_agent = $("#user_id_agent").val();
                var caseId = $("#caseId").val();

                var datastring = $("#form").serialize();
                if (agency_id != '' && agency_branch_id != '' && user_id_agent !='') {

                    /*                    if (caseId == 'create') {
                     $.ajax({
                     type: "POST",
                     url: "/cases/transactionaddress/create",
                     data: datastring,
                     //dataType: "json",
                     success: function (data) {
                     $("#addressId").val(data);
                     $("#savedMessage").fadeIn().fadeOut(5000);
                     },
                     error: function () {
                     alert('error handing here');
                     }
                     });
                     } else {*/
                    $.ajax({
                        type: "POST",
                        url: "/cases/agency/" + caseId,
                        data: datastring,
                        //dataType: "json",
                        success: function (data) {
                            $("#savedMessage").fadeIn().fadeOut(5000);
                        },
                        error: function () {
                            $("#failedToSaveMessage").fadeIn().fadeOut(5000);
                        }
                    });
                    //}
                }
            });

        });
    </script>
@endsection
