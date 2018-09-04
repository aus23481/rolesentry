



        <div style="position:absolute;top:10%;left:2%;"  class="backtoplat-btn"><a href="/platform"><span class="glyphicon glyphicon-arrow-left"></span> Back to Platform</a></div>        

<div style="margin-top:10px;" class="row preferences-container">
        
               
        <!-- left side content 
        <div class="col-lg-4 col-md-12 col-sm-12" style="float: right;">
            <div id="notif-toggle-section">
                <div class="container">
                    <div class="row report-type" >
                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                            <div class="report-desc-1">
                                <h3>New Opening Report</h3>
                                <p>Daily Email detailing all the new openings from today.</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label class="toggle">
                            <input type="checkbox" id="new_opening_report" onclick="toggle(this,event)" style="display: none;"/>
                            <div data-off="Off" data-on="On">Notification</div>
                            </label>
                        </div>
                    </div>
                    <div class="row report-type">
                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                            <div class="report-desc-2">
                                <h3>Hiring Manager Report</h3>
                                <p>Daily Email detailing all active hiring managers in your area.</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label class="toggle">
                            <input type="checkbox" id="hiring_manager_report" onclick="toggle(this,event)" style="display: none;"/>
                            <div data-off="Off" data-on="On">Notification</div>
                            </label>
                        </div>
                    </div>
                    <div class="row report-type">
                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                            <div class="report-desc-3">
                                <h3>High Value Role Alert</h3>
                                <p>Instant Email alert when a new high value role opens in your area.</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label class="toggle">
                            <input type="checkbox" id="high_value_role_report" onclick="toggle(this,event)" style="display: none;"/>
                            <div data-off="Off" data-on="On">Notification</div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        left side content -->
    
        <!-- right side content -->
        
        
        <div class="col-lg-12 col-md-12 col-sm-12" style="">
                
                
            <form  action="{{ URL::to('save-user-preferences')}}"   id="user-prefrences-form" enctype="multipart/form-data" method="post">
                {{ csrf_field() }}
                
                <div style="" id="content-section1">
                    <h3>Select Your Preferences 
                    </h3>
                    <div id="scroll-box" class="preferences-table">

                            <table width="100%">
                                    <tr>
                                        <th width="22%"></th>
                                        @foreach($jobtypes as $jobtype)
                                        <th width="13%">{{$jobtype->name}}</th>
                                        @endforeach
                                    </tr>
                                    
                                @foreach($locations as $indexKey => $location)   
                                    <tr>
                                    
                                        <td><span class="title-left">{{$location->name}}</span><a href="#" id="scrldwn-tgl-btn-{{$indexKey+1}}" class="scrldwn-tgl-icon-{{$indexKey+1}}a"></a></td>
                                    @foreach($jobtypes as $jobtype)
                                            <?php 
                                            $up = "up_".$jobtype->id."_".$location->id;
                                            ?>
                                        <td>
                                        <label class="check-container">
                                            <input type="checkbox" onclick="toggleSubTypes('up_{{$jobtype->id}}_{{$location->id}}')" id="up_{{$jobtype->id}}_{{$location->id}}" name="up_{{$jobtype->id}}_{{$location->id}}" @if(isset(${$up})) checked @endif    >
        
                                        <span class="checkmark"></span></label>
                                        
                                        <div class="more-checklist checklist-tgl-{{$indexKey+1}}">
                                            @foreach($jobtype->subtypes  as $subtype)
                                            <?php 
                                            $ups = "up_".$jobtype->id."_".$location->id."_".$subtype->id;
                                            ?>
                                                <label class="check-container">
                                                <input onclick=" if($('#up_{{$jobtype->id}}_{{$location->id}}_{{$subtype->id}}').prop('checked'))  $('#up_{{$jobtype->id}}_{{$location->id}}').prop('checked', true);checkSubTypeEmpty('up_{{$jobtype->id}}_{{$location->id}}');" type="checkbox" id="up_{{$jobtype->id}}_{{$location->id}}_{{$subtype->id}}" name="up_{{$jobtype->id}}_{{$location->id}}_{{$subtype->id}}" @if(isset(${$ups})) checked @endif >
                                                    <span class="checkmark"></span> {{$subtype->name}}
                                                </label>
                                            @endforeach                                    
                                        </div>
                                        </td>
                                    @endforeach    
                                    </tr>
                                    <script>
                                    $("#scrldwn-tgl-btn-{{$indexKey+1}}").click(function () { 
        
                                        $(".scrldwn-tgl-icon-{{$indexKey+1}}a").toggleClass('scrldwn-tgl-icon-{{$indexKey+1}}b'); 
                                        $(".checklist-tgl-{{$indexKey+1}}").slideToggle();
                                       
                                        return false; 
                                    });
                                    
                                    </script>
                                @endforeach    
                                    
                                    
                                </table>
                           
                       
                    </div>
                </div>
                <div class="row pref-btn hide">
                    <button type="submit"  class="btn btn-lg">Save Preferences</button>
                </div>
            </form>
        </div> 
        <!-- right side content -->                
    </div><!-- row --> 



<script>
	
        $('#reset-check-pref').click(function(){
			$('#scroll-box').find('input[type=checkbox]:checked').removeAttr('checked');
		});
		
		//$("#scrldwn-tgl-btn-1").click(function () { $(".scrldwn-tgl-icon-1a").toggleClass('scrldwn-tgl-icon-1b'); $(".checklist-tgl-1").slideToggle(); return false; });

    
    
    var new_opening_report = parseInt('<?php print $new_opening_report?>');
	var hiring_manager_report = parseInt('<?php print $hiring_manager_report?>');
	var high_value_role_report = parseInt('<?php print $high_value_role_report?>');
	</script>
	<script src="user-preferences.js"></script>
