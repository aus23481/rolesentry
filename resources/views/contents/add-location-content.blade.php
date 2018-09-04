
        
        <div class="col-md-9 col-lg-9 col-xs-12">          
                
     <div class="clearfix"></div>
     <!--/heading-->
    
     <div class="white-box">
       <div class="pull-left"><h4>Add Location </h4></div>
       
       <div class="clearfix"></div>
       <br>
       <div class="steamline"> 
                
                    <form class="form-inline"  action="{{ URL::to('add-location')}}"   id="location-form" enctype="multipart/form-data" method="get">
                        {{ csrf_field() }}
                       
                        
                        <div class="form-group">
                            <label>Location</label>
                            <select name="location" class="form-control">
                               @foreach($locations as $location)
                            <option value="{{$location->id}}">{{$location->name}}</option>
                               @endforeach 
                            </select>

                        </div>
                        <div  class="form-group">            
                              
                                <input name="name" placeholder="Add New Locaton" size="60%" id="name" required="required" class="form-control" type="text" value="<?php if(isset($name)) print $name; ?>">

                            <button type="submit" id="btn_add_location" class="btn btn-default" data-dismiss="modal">
                                        Save Location
                                </button>
                        </div>
                    </form>
                            
       </div>
     </div>
    </div>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    