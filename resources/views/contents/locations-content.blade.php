
        
        <div class="col-md-9 col-lg-9 col-xs-12">          
                
     <div class="clearfix"></div>
     <!--/heading-->
    
     <div class="white-box">
       <div class="pull-left"><h4>Locations </h4></div>
       
       <div class="clearfix"></div>
       <br>
       <div class="steamline"> 
                
                    <form class="form-inline"  action="{{ URL::to('add-location')}}"   id="location-form" enctype="multipart/form-data" method="get">
                        {{ csrf_field() }}
                       
                        
                        <table  class="table table-striped ">
                            <tr><th>SL</th><th>Location</th></tr>	
                            @foreach($locations as $indexKey => $location)
                          <tr style="cursor:pointer" onClick="" ><td>{{$indexKey+1}}</td> <td><a href="/location?id={{$location->id}}">{{ucfirst($location->name)}}</a></td></tr>

              @endforeach
                        </table>
                        
                        
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
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    