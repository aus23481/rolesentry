

<?php
  
if(isset($company)) {     
    $career_page_url = "";
    $xpath = "";
    $name = "";
    $roles = "";
    $location_id = 0;
    unset($roles);
} 
if(isset($company_exists)) {

    $career_page_url = "";
    $xpath = "";
    $name = "";
    $roles = "";
    $location_id = 0;
    unset($roles);

}  
?>
<div class="col-md-9 col-lg-9 col-xs-12">          
            <div class="panel panel-default">
                <div class="panel-heading head-desc">
                    <h4> Add Company </h4>
                </div>

                <div class="panel-body">
                  <div style="padding-left:12%">
                     @if(isset($company))
                        <div class="alert alert-success">
                                <strong><a href="/company?id={{$company->id}}">{{ucfirst($company->name)}}</a> Successfully Saved !</strong> 
                        </div> 
                    @endif

		     @if(isset($error))
                    <div class="alert alert-warning">
                            <strong>{{$error}}</strong> 
                    </div> 
                    @endif


                    @if(isset($company_exists))
                    <div class="alert alert-warning">
                            <strong>This company exists !</strong> 
                    </div> 
                    @endif

                   @if(isset($roles))
                    @if(count($roles)>0)
                    <div class="alert alert-success">
                        <strong>Success!</strong> Roles found.
                      </div> 
                   <form  action="{{ URL::to('save-company-data')}}"   id="company-form" enctype="multipart/form-data" method="post">
                    {{ csrf_field() }}
                    <input name="name" id="name" class="form-control" type="hidden" value="<?php if(isset($name)) print $name; ?>">
                    <input name="career_page_url" id="career_page_url" class="form-control" type="hidden" value="<?php if(isset($career_page_url)) print $career_page_url; ?>">
                    <input  name="xpath" id="xpath" type="hidden" class="form-control" value="<?php if(isset($xpath)) print $xpath; ?>">
                    <input  name="location_id" id="location_id" type="hidden" class="form-control" value="<?php if(isset($location_id)) print $location_id; ?>">
                    
                    <div  class="form-group">            
                            <button type="submit" id="btn_add_company" class="btn btn-default" data-dismiss="modal">
                                    Save Company
                            </button>
                    </div>
                 </form> 
                    @foreach($roles as $role)
                        <div>{{$role}}</div>
                    @endforeach 
                   @else <div class="alert alert-warning">
                    <strong>Warning!</strong> No Data found.
                  </div>
                   @endif
                   @endif
                </div> 
                <form  action="{{ URL::to('get-company-data')}}"   id="company-form" enctype="multipart/form-data" method="post">
            
                        {{ csrf_field() }}

                            <!-- Modal -->
                            <div >
                                <div class="modal-dialog">
                                
                                <!-- Modal content-->
                                <div class="modal-content">
                                
                                    <div class="modal-body">
                                    <div class="form-group">
                                        <label for="name">Angellist Page:</label>                                       
                                        <input name="name" id="name" class="form-control" type="text" value="<?php if(isset($name)) print $name; ?>">

                                    </div>

                                    <div class="form-group">
                                        <label for="career_page_url">Company Career Page:</label>                                       
                                        <input name="career_page_url" id="career_page_url" class="form-control" type="text" value="<?php if(isset($career_page_url)) print $career_page_url; ?>">

                                    </div>

                                    <div class="form-group">
                                        <label for="xpath">Listing XPath:</label>                                        
                                        <input  name="xpath" id="xpath" type="text" class="form-control" value="<?php if(isset($xpath)) print $xpath; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="xpath">Location:</label>                                        
                                        <select class="form-control" id="location_id" name="location_id">
                                            <option value="0">...</option>
                                            @foreach($locations as $location)
                                            <option <?php if(isset($location_id)&& $location_id == $location->id) print "selected"; ?> value="{{$location->id}}">{{$location->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="form-group">            
                                    <button type="submit" id="btn_add_company" class="btn btn-default" data-dismiss="modal">
                                            Extract Roles

                                    </button>
                                    </div>

                                </div>
                                
                                </div>
                                
                                </div>
                            </div>
                        </form>       
                </div>
            </div>  
</div>
        
