
        
        <div class="col-md-9 col-lg-9 col-xs-12">          
                
     <div class="clearfix"></div>
     <!--/heading-->
    
     <div class="white-box">
       <div class="pull-left"><h4>Locations </h4></div>
       
       <div class="clearfix"></div>
       <br>
       <div class="steamline"> 
                
                    <form   action="{{ URL::to('save-location-detail')}}" onsubmit="return confirm('Do you really want to submit the form?');"   id="location-form" enctype="multipart/form-data" method="get">
                        {{ csrf_field() }}
                       
                        <input name="id"  id="id"  class="form-control" type="hidden" value="{{$location->id}}">
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input name="name"  id="name" required="required" class="form-control" type="text" value="{{$location->name}}">
                        </div>

                        <div class="form-group">
                            <label for="careerjet_location_name"> careerjet_location_name:</label>
                            <input name="careerjet_location_name"    class="form-control" type="text" value="{{$location->careerjet_location_name}}">
                        </div>

                        

                        <div class="form-group">
                            <label for="careerjet_location_name"> dice_location_name:</label>
                            <input name="dice_location_name"    class="form-control" type="text" value="{{$location->dice_location_name}}">
                        </div>

                        <div class="form-group">
                            <label for="careerjet_location_name"> nytimes_location_name:</label>
                            <input name="nytimes_location_name"    class="form-control" type="text" value="{{$location->nytimes_location_name}}">
                        </div>

                        <div class="form-group">
                            <label for="careerjet_location_name">  indeed_location_name:</label>
                            <input name="indeed_location_name"    class="form-control" type="text" value="{{$location->indeed_location_name}}">
                        </div>

                        <div class="form-group">
                            <label for="careerjet_location_name"> careerbuilder_location_name:</label>
                            <input name="careerbuilder_location_name"    class="form-control" type="text" value="{{$location->careerbuilder_location_name}}">
                        </div>

                        <div class="form-group">
                            <label for="careerjet_location_name"> simplyhired_location_name:</label>
                            <input name="simplyhired_location_name"    class="form-control" type="text" value="{{$location->simplyhired_location_name}}">
                        </div>

                        <div class="form-group">
                            <label for="careerjet_location_name"> monster_location_name:</label>
                            <input name="monster_location_name"    class="form-control" type="text" value="{{$location->monster_location_name}}">
                        </div>

                        <div class="form-group">
                            <label for="careerjet_location_name">  stackoverflow_location_name:</label>
                            <input name="stackoverflow_location_name"    class="form-control" type="text" value="{{$location->stackoverflow_location_name}}">
                        </div>

                        <div class="form-group">
                            <label for="careerjet_location_name"> hiring_manager_report_time:</label>
                            <input name="hiring_manager_report_time"    class="form-control" type="text" value="{{$location->hiring_manager_report_time?$location->hiring_manager_report_time:0}}">
                        </div>

                        <div class="form-group">
                            <label for="careerjet_location_name">  show_in_preferences:</label>
                            <input name="show_in_preferences"    class="form-control" type="text" value="{{$location->show_in_preferences}}">
                        </div>
                        <div class="form-group">
                            <label for="careerjet_location_name"> priority:</label>
                            <input name="priority"    class="form-control" type="text" value="{{$location->priority}}">
                        </div>

                        <div class="form-group">
                            <label for="careerjet_location_name"> latitude:</label>
                            <input name="latitude"    class="form-control" type="text" value="{{$location->latitude}}">
                        </div>

                        <div class="form-group">
                            <label for="careerjet_location_name">  longitude:</label>
                            <input name="longitude"    class="form-control" type="text" value="{{$location->longitude}}">
                        </div>

                        
                        
                         <div  class="form-group">            
                                <button type="submit" id="btn_add_location" class="btn btn-default" data-dismiss="modal">
                                        Save Location
                                </button>
                        </div>
                    </form>

                   

                    <table class="table table-striped ">
                        <tr><th><h3> Last Job Board Alerts </h3></th><th> <button onclick="RunManualScrapeLaction({{$location->id}})" type="button" class="btn btn-primary" > Manual Scrape </button></th></tr>  
                    </table>

                    <table  class="table table-striped ">
                     
                     @foreach($alerts as $indexKey => $alert)
                        <tr><td><h4>Job Board Alert Id:: {{$alert->id}} </h4> </td></tr>
                        <tr><td>Job Description on Company Page:: {{$alert->job_description_on_company_page}} </td></tr>
                        <tr><td>Job Descrpition on Job Board Page:: {{$alert->job_descrpition_on_job_board_page}} </td></tr>
                        <tr><td>Job Board::  {{$alert->job_board}} </td></tr>
                        <tr><td>Created At:: {{$alert->created_at}} </td></tr>
                        <tr><td colspan="2"><hr> </td></tr>
                     @endforeach
                    </table>     
                            
       </div>
     </div>
    </div>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    