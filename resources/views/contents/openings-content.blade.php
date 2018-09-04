<?php

	$user = Auth::user();
	
	//1 admin
	//2 Subscriber
    //print $user->type;
   
?>  

  
<div class="col-md-9 col-lg-9 col-xs-12">          
            <div class="panel panel-default">
                <div class="panel-heading head-desc">
                    <h4> Openings  </h4>
                </div>
                
                <form class="form-horizontal"  action="{{ URL::to('openings')}}"   id="opening-form" enctype="multipart/form-data" method="get">
                  {{ csrf_field() }} 
                <div class="col-md-5">
                    <label class="control-label col-sm-3" for="company">Location:</label>
                      <div class="col-sm-9"> 
                        <select class="form-control" name="location_id" >
                          @foreach($locations as $location)
                          <option @if(isset($_REQUEST["location_id"]) == $location->id) selected @endif value="{{$location->id}}" >{{$location->name}}</option>
                          @endforeach
                      </select>
                    </div>     
                </div>

                <div class="col-md-5">                    
                    <label class="control-label col-sm-6" for="company">Job Type:</label>
                    <div class="col-sm-6">
                    <select class="form-control"  name="job_type_id" >
                            @foreach($job_types as $type)
                        <option  @if(isset($_REQUEST["job_type_id"]) == $type->id) selected @endif value="{{$type->id}}" >{{$type->name}}</option>
                            @endforeach
                        </select>
                      </div>     
                </div>
                <div class="col-md-2">
                    <button type="submit" name="sort" class="btn btn-default">sort</button>
                </div>
              </form>  

              <hr>
                
                <div class="panel-body" style="font-size:8px;">
                  <span></span>
                  <div class="col-md-4" style="border:1px solid black;border-radius:5px;min-height:500px;margin-right:5px;">
                    
                    <h4>Not Approved</h4>
                    <hr />
                    @foreach($openings_not_approved as $opening)
                  <div><a href="/opening-edit?id={{$opening->id}}">{{$opening->id}} - {{$opening->title}} - {{$opening->company->name}}</a> </div>
                    @endforeach
                    <div class="text-right"> {{ $openings_not_approved->links() }} </div>  
                  </div>


                  <div class="col-md-4" style="border:1px solid black;border-radius:5px;min-height:500px;margin-right:5px;">
                  
                    <h4>Approved</h4>
                    <hr />
                    @foreach($openings_approved as $opening)
                     <div><a href="/opening-edit?id={{$opening->id}}">{{$opening->id}} - {{$opening->title}} - {{$opening->company->name}} </a></div>
                    @endforeach
                    <div class="text-right"> {{ $openings_approved->links() }} </div>  
                                        
                  </div>


                  <div class="col-md-3" style="border:1px solid black;border-radius:5px;min-height:500px;">
                    
                    <h4>Skipped</h4>
                    <hr />
                    @foreach($openings_skipped as $opening)
                     <div> <a href="/opening-edit?id={{$opening->id}}">{{$opening->id}} - {{$opening->title}} - {{$opening->company->name}} </a> </div>
                    @endforeach
                    <div class="text-right"> {{ $openings_skipped->links() }} </div>  
                                        
                  </div>
                    
                </div>
            </div>  
</div>
        
