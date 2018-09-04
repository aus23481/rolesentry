
        
        <div class="col-md-9 col-lg-9 col-xs-12">          
                
     <div class="clearfix"></div>
     <!--/heading-->
    
     <div class="white-box">
            
       <div class="clearfix"></div>
       <br>
       <div class="steamline"> 
                
                    <form class="form-inline"  action="{{ URL::to('add-bantext')}}"   id="bantext-form" enctype="multipart/form-data" method="get">
                        {{ csrf_field() }}
                        <input type="hidden" name="bantype" value="url">
                        
                        <div class="form-group">
                            <h4>Ban Terms In URL</h4>
                             @if(isset($banurls))
                               @foreach($banurls as $bt)
                                <div>{{$bt->term}}  <a href="/delete-bantext/{{$bt->id}}/url"><i class="fa fa-trash-o"></i></a> </div>
                               @endforeach 
                             @endif 

                             <div  class="form-group">            
                              
                                <input id="term" name="term" placeholder="Ban Term for URL" size="40%" id="name" required="required" class="form-control" type="text" value="<?php if(isset($name)) print $name; ?>">

                            <button type="submit" id="btn_add_ban_term" class="btn btn-default" data-dismiss="modal">
                                        Save Ban Term 
                                </button>
                         </div>
                        </form>    
                         <hr>
                             <h4>Ban Terms In Job Title</h4>
                             @if(isset($banjobtitles))
                               @foreach($banjobtitles as $bt)
                                <div>{{$bt->term}}  <a href="/delete-bantext/{{$bt->id}}/job_title"><i class="fa fa-trash-o"></i></a> </div>
                               @endforeach 
                             @endif 

                             <form class="form-inline"  action="{{ URL::to('add-bantext')}}"   id="bantext-form" enctype="multipart/form-data" method="get">
                              {{ csrf_field() }}
                              <input type="hidden" name="bantype" value="job_title">
                             <div  class="form-group">            
                              
                               <input id="term" name="term" placeholder="Ban Term for Job Title" size="40%" id="name" required="required" class="form-control" type="text" value="<?php if(isset($name)) print $name; ?>">

                            <button type="submit" id="btn_add_ban_term" class="btn btn-default" data-dismiss="modal">
                                        Save Ban Term 
                                </button>
                        </div>
                      </form> 
                        <hr>
                             <h4>Ban Terms In Company Name</h4>
                             @if(isset($bancompanynames))
                               @foreach($bancompanynames as $bt)
                                <div>{{$bt->term}}  <a href="/delete-bantext/{{$bt->id}}/company_name"><i class="fa fa-trash-o"></i></a> </div>
                               @endforeach 
                             @endif 
                            

                        </div>
                        <hr>
                        <form class="form-inline"  action="{{ URL::to('add-bantext')}}"   id="bantext-form" enctype="multipart/form-data" method="get">
                          {{ csrf_field() }} 
                          <input type="hidden" name="bantype" value="company_name">
                        <div  class="form-group">            
                              
                               <input id="term" name="term" placeholder="Ban Term for Company" size="40%" id="name" required="required" class="form-control" type="text" value="<?php if(isset($name)) print $name; ?>">

                            <button type="submit" id="btn_add_ban_term" class="btn btn-default" data-dismiss="modal">
                                        Save Ban Term 
                                </button>
                        </div>
                    </form>
                            
       </div>
     </div>
    </div>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    